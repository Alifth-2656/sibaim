<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\LaporanStokHabis;
use App\Models\Notifikasi;
use App\Models\Permintaan;
use App\Models\PermintaanDetail;
use App\Models\RiwayatOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermintaanBarangController extends Controller
{
    public function index()
    {
        return view('comodity.permintaan.index');
    }

    public function pilih(Request $request)
    {
        $barangs = Barang::all();

        return view('comodity.permintaan.pilih', [
            'barangs'   => $barangs,
            'pic'       => $request->pic,
            'commodity' => $request->commodity,
        ]);
    }

    /**
     * Setelah comodity pilih barang, cek stok dulu.
     * - Stok ada   → masuk session, arahkan ke konfirmasi
     * - Stok habis → catat laporan_stok_habis (type: stok_habis), info ke comodity
     */
    public function cekStok(Request $request)
    {
        $request->validate([
            'pic'       => 'required|string',
            'commodity' => 'required|string',
            'items'     => 'required|array',
        ]);

        $itemsValid = [];
        $itemsHabis = [];

        foreach ($request->items as $barangId => $qty) {
            $qty = (int) $qty;
            if ($qty <= 0) continue;

            $barang = Barang::find($barangId);
            if (!$barang) continue;

            if ((int) $barang->qty >= $qty) {
                $itemsValid[] = [
                    'barang_id'   => $barang->id,
                    'nama_barang' => $barang->nama_barang,
                    'kode_barang' => $barang->kode_barang,
                    'satuan'      => $barang->satuan ?? '-',
                    'qty_diminta' => $qty,
                    'qty_sistem'  => $barang->qty,
                ];
            } else {
                // Catat stok habis — type: stok_habis
                LaporanStokHabis::create([
                    'barang_id'   => $barang->id,
                    'pic'         => $request->pic,
                    'commodity'   => $request->commodity,
                    'qty_diminta' => $qty,
                    'type'        => 'stok_habis',
                    'status'      => 'pending',
                ]);

                $itemsHabis[] = $barang->nama_barang;
            }
        }

        if (empty($itemsValid) && empty($itemsHabis)) {
            return back()->with('error', 'Tidak ada item yang dipilih.');
        }

        $pesanHabis = null;
        if (!empty($itemsHabis)) {
            $pesanHabis = 'Barang berikut stoknya tidak cukup dan sudah dilaporkan ke admin: ' . implode(', ', $itemsHabis);
        }

        if (empty($itemsValid)) {
            return redirect()->route('comodity.permintaan.index')
                ->with('warning', $pesanHabis . '. Tidak ada barang lain yang bisa diproses.');
        }

        session([
            'permintaan_pending' => [
                'pic'       => $request->pic,
                'commodity' => $request->commodity,
                'items'     => $itemsValid,
            ]
        ]);

        return redirect()->route('comodity.permintaan.konfirmasi')
            ->with('warning_habis', $pesanHabis);
    }

    /**
     * Halaman konfirmasi — comodity review + toggle "tidak ditemukan" per item
     */
    public function konfirmasi()
    {
        $pending = session('permintaan_pending');

        if (!$pending) {
            return redirect()->route('comodity.permintaan.index')
                ->with('error', 'Sesi permintaan tidak ditemukan. Silakan ulangi.');
        }

        return view('comodity.permintaan.konfirmasi', [
            'pic'       => $pending['pic'],
            'commodity' => $pending['commodity'],
            'items'     => $pending['items'],
        ]);
    }

    /**
     * Submit final.
     *
     * Tiap item punya status dari form:
     *   - 'diambil'         → masuk permintaan dengan status 'open', stok BELUM dikurangi
     *   - 'tidak_ditemukan' → catat laporan_stok_habis (type: tidak_ditemukan), skip
     *
     * Stok baru dikurangi setelah admin konfirmasi (status → 'close').
     */
    public function store(Request $request)
    {
        $pending = session('permintaan_pending');

        if (!$pending) {
            return redirect()->route('comodity.permintaan.index')
                ->with('error', 'Sesi permintaan tidak ditemukan. Silakan ulangi.');
        }

        // item_status: array keyed by barang_id → 'diambil' | 'tidak_ditemukan'
        $itemStatus          = $request->input('item_status', []);
        $itemsDiambil        = [];
        $itemsTidakDitemukan = [];

        foreach ($pending['items'] as $item) {
            $status = $itemStatus[$item['barang_id']] ?? 'diambil';
            if ($status === 'tidak_ditemukan') {
                $itemsTidakDitemukan[] = $item;
            } else {
                $itemsDiambil[] = $item;
            }
        }

        DB::beginTransaction();

        try {
            // --- Proses item tidak ditemukan (catat laporan, tidak kurangi stok) ---
            foreach ($itemsTidakDitemukan as $item) {
                LaporanStokHabis::create([
                    'barang_id'   => $item['barang_id'],
                    'pic'         => $pending['pic'],
                    'commodity'   => $pending['commodity'],
                    'qty_diminta' => $item['qty_diminta'],
                    'type'        => 'tidak_ditemukan',
                    'status'      => 'pending',
                ]);
            }

            // --- Kalau semua tidak ditemukan, tidak perlu buat permintaan ---
            if (empty($itemsDiambil)) {
                DB::commit();
                session()->forget('permintaan_pending');

                $namaTidakAda = implode(', ', array_column($itemsTidakDitemukan, 'nama_barang'));
                return redirect()->route('comodity.permintaan.index')
                    ->with('warning', 'Semua barang tidak ditemukan di gudang (' . $namaTidakAda . '). Laporan sudah dikirim ke admin.');
            }

            // --- Re-validasi stok (anti race condition) ---
            foreach ($itemsDiambil as $item) {
                $barang = Barang::find($item['barang_id']);
                if (!$barang || (int) $barang->qty < $item['qty_diminta']) {
                    DB::rollBack();
                    return redirect()->route('comodity.permintaan.index')
                        ->with('error', 'Stok ' . ($barang->nama_barang ?? 'barang') . ' sudah berubah. Silakan ulangi permintaan.');
                }
            }

            // --- Buat record permintaan (status: open, stok belum dikurangi) ---
            $permintaan = Permintaan::create([
                'pic'       => $pending['pic'],
                'commodity' => $pending['commodity'],
                'tanggal'   => now()->toDateString(),
                'status'    => 'open',
            ]);

            $namaList = [];

            foreach ($itemsDiambil as $item) {
                $barang = Barang::find($item['barang_id']);
                $qty    = $item['qty_diminta'];

                PermintaanDetail::create([
                    'permintaan_id' => $permintaan->id,
                    'barang_id'     => $barang->id,
                    'qty'           => $qty,
                ]);

                // Stok & RiwayatOut diproses saat admin konfirmasi
                $namaList[] = "{$barang->nama_barang} (Kode: {$barang->kode_barang}, Qty: {$qty})";
            }

            // --- Notifikasi ke admin & improvement ---
            $pesanNotif = "Comodity ({$pending['pic']}) mengajukan permintaan barang: " . implode('; ', $namaList);

            if (!empty($itemsTidakDitemukan)) {
                $namaTidakAda = implode(', ', array_column($itemsTidakDitemukan, 'nama_barang'));
                $pesanNotif  .= ". Tidak ditemukan di gudang: {$namaTidakAda}";
            }

            Notifikasi::create([
                'judul'         => 'Permintaan Barang Baru',
                'pesan'         => $pesanNotif,
                'tipe'          => 'permintaan',
                'permintaan_id' => $permintaan->id,
                'for_roles'     => ['admin', 'improvement'],
                'is_read'       => false,
            ]);

            DB::commit();
            session()->forget('permintaan_pending');

            $successMsg = 'Permintaan berhasil diajukan dan menunggu konfirmasi admin.';
            if (!empty($itemsTidakDitemukan)) {
                $namaTidakAda = implode(', ', array_column($itemsTidakDitemukan, 'nama_barang'));
                $successMsg  .= ' Barang tidak ditemukan (' . $namaTidakAda . ') sudah dilaporkan ke admin.';
            }

            return redirect()->route('comodity.permintaan.index')
                ->with('success', $successMsg);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function riwayat()
    {
        $data = Permintaan::with('details.barang')->latest()->get();
        return view('comodity.permintaan.riwayat', compact('data'));
    }
}