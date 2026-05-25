<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use App\Models\PermintaanDetail;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Exports\LaporanStokExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function laporanPermintaan(Request $request)
    {
        $query = Permintaan::with('details.barang')->latest();

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('pic', 'like', '%' . $request->search . '%')
                    ->orWhere('commodity', 'like', '%' . $request->search . '%');
            });
        }

        // Filter tanggal
        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        $permintaans = $query->paginate(10)->withQueryString();

        return view('admin.laporan.permintaan', compact('permintaans'));
    }

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
}
