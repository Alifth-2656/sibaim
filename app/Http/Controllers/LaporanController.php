<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use App\Models\Barang;
use App\Models\LaporanStokHabis;
use App\Models\RiwayatOut;
use App\Exports\LaporanStokExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class LaporanController extends Controller
{

    public function laporanStok(Request $request)
    {
        $query = Barang::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('kode_barang', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'aman') {
                $query->whereColumn('qty', '>=', 'min')->whereColumn('qty', '<=', 'max')->where('qty', '>', 0);
            } elseif ($request->status === 'kurang') {
                $query->whereColumn('qty', '<', 'min')->where('qty', '>', 0);
            } elseif ($request->status === 'habis') {
                $query->where('qty', 0);
            } elseif ($request->status === 'over') {
                $query->whereColumn('qty', '>', 'max');
            }
        }

        $barangs = $query->orderBy('kode_barang')->paginate(15)->withQueryString();

        return view('admin.laporan.stok', compact('barangs'));
    }

    public function exportStok(Request $request)
    {
        return Excel::download(
            new LaporanStokExport($request->search, $request->status),
            'laporan-stok-' . now()->format('d-m-Y') . '.xlsx'
        );
    }

    public function pendingPermintaan(Request $request)
    {
        $tab     = $request->input('tab', 'permintaan');
        $showAll = $request->boolean('show_all');
        $tanggal = $request->input('tanggal', now()->toDateString());

        // --- Permintaan ---
        $queryPermintaan = Permintaan::with('details.barang')->latest();
        if (!$showAll) {
            $queryPermintaan->whereDate('created_at', $tanggal);
        }
        $permintaans = $queryPermintaan->paginate(15, ['*'], 'perm_page')->withQueryString();

        // --- Stok Habis / Tidak Ditemukan ---
        $queryLaporan = LaporanStokHabis::with('barang')
            ->where('type', 'tidak_ditemukan')
            ->latest();
        if (!$showAll) {
            $queryLaporan->whereDate('created_at', $tanggal);
        }
        $laporans = $queryLaporan->paginate(15, ['*'], 'lapor_page')->withQueryString();

        return view('admin.laporan.pending_permintaan', compact(
            'permintaans',
            'laporans',
            'tab',
            'showAll',
            'tanggal'
        ));
    }

    /**
     * Admin konfirmasi permintaan:
     * status open → close, stok dikurangi, RiwayatOut dicatat
     */
    public function konfirmasiPermintaan($id)
    {
        $permintaan = Permintaan::with('details.barang')->findOrFail($id);

        if ($permintaan->status === 'close') {
            return back()->with('error', 'Permintaan sudah dikonfirmasi sebelumnya.');
        }

        DB::beginTransaction();
        try {
            foreach ($permintaan->details as $detail) {
                $barang = $detail->barang;

                if (!$barang || $barang->qty < $detail->qty) {
                    DB::rollBack();
                    return back()->with('error', 'Stok ' . ($barang->nama_barang ?? 'barang') . ' tidak cukup saat konfirmasi.');
                }

                $barang->decrement('qty', $detail->qty);

                RiwayatOut::create([
                    'barang_id'  => $barang->id,
                    'qty'        => $detail->qty,
                    'pic'        => $permintaan->pic,
                    'keterangan' => 'Permintaan Barang',
                ]);
            }

            $permintaan->update(['status' => 'close']);

            DB::commit();
            return back()->with('success', 'Permintaan #' . $permintaan->id . ' berhasil dikonfirmasi dan stok telah dikurangi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function stokHabis(Request $request)
    {
        $query = \App\Models\LaporanStokHabis::with('barang')->latest();

        if ($request->filled('filter_type')) {
            $query->where('type', $request->filter_type);
        }

        $laporan = $query->paginate(15)->withQueryString();

        return view('admin.laporan.stok_habis', compact('laporan'));
    }

    public function tanganiStokHabis($id)
    {
        $laporan = \App\Models\LaporanStokHabis::findOrFail($id);
        $laporan->update(['status' => 'ditangani']);

        return back()->with('success', 'Laporan ditandai sudah ditangani.');
    }
}
