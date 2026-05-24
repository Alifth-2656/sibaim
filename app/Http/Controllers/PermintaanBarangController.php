<?php

namespace App\Http\Controllers;

use App\Models\Barang;
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

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'pic'       => 'required|string',
                'commodity' => 'required|string',
                'items'     => 'required|array',
            ]);

            // ✅ FIX 1: Inisialisasi $itemsList sebelum foreach
            $itemsList = [];

            // ✅ FIX 2: Validasi items sebelum membuat Permintaan
            foreach ($request->items as $barangId => $qty) {
                $qty = (int) $qty;
                if ($qty <= 0) continue;

                $barang = Barang::find($barangId);
                if (!$barang) {
                    DB::rollBack();
                    return back()->with('error', "Barang dengan ID {$barangId} tidak ditemukan.");
                }

                if ((int) $barang->qty < $qty) {
                    DB::rollBack();
                    return back()->with('error', 'Stok tidak cukup untuk: ' . $barang->nama_barang);
                }

                $itemsList[] = [
                    'barang'      => $barang,
                    'qty'         => $qty,
                    'nama_barang' => $barang->nama_barang,
                    'kode_barang' => $barang->kode_barang,
                ];
            }

            // ✅ FIX 3: Guard jika tidak ada item valid
            if (empty($itemsList)) {
                DB::rollBack();
                return back()->with('error', 'Tidak ada item yang valid. Pastikan qty lebih dari 0.');
            }

            // Buat permintaan hanya setelah validasi semua item lolos
            $permintaan = Permintaan::create([
                'pic'       => $request->pic,
                'commodity' => $request->commodity,
                'tanggal'   => now()->toDateString(),
            ]);

            foreach ($itemsList as $item) {
                $barang = $item['barang'];
                $qty    = $item['qty'];

                // Simpan detail
                PermintaanDetail::create([
                    'permintaan_id' => $permintaan->id,
                    'barang_id'     => $barang->id,
                    'qty'           => $qty,
                ]);

                // Kurangi stok
                $barang->decrement('qty', $qty);

                // Catat riwayat out
                RiwayatOut::create([
                    'barang_id'  => $barang->id,
                    'qty'        => $qty,
                    'pic'        => $request->pic,
                    'barcode'    => $barang->barcode ?? '-',
                    'keterangan' => 'Permintaan Barang - ID: ' . $permintaan->id,
                ]);
            }

            // Buat pesan notifikasi
            $namaBarang = collect($itemsList)
                ->map(fn($item) => "{$item['nama_barang']} (Kode: {$item['kode_barang']}, Qty: {$item['qty']})")
                ->implode('; ');

            Notifikasi::create([
                'judul'         => 'Permintaan Barang Baru',
                'pesan'         => "Comodity ({$request->pic}) mengambil barang: {$namaBarang}",
                'tipe'          => 'permintaan',
                'permintaan_id' => $permintaan->id,
                'for_roles'     => ['admin', 'improvement'],
                'is_read'       => false,
            ]);

            DB::commit();

            return redirect()->route('comodity.permintaan.index')
                ->with('success', 'Permintaan berhasil disimpan.');

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