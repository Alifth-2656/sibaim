<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\RiwayatOut;
use App\Models\RiwayatMove;
use App\Models\RiwayatIn;
use App\Models\RiwayatSto;
use Illuminate\Http\Request;
use App\Models\Permintaan;
use App\Models\PermintaanDetail;

class HistoryController extends Controller
{
    public function index()
    {
        $data = [
            'totalBarang' => Barang::count(),
            'totalIn'     => RiwayatIn::count(),
            'totalOut'    => RiwayatOut::count(),
            'totalMove'   => RiwayatMove::count(),
            'totalSto'    => RiwayatSto::count(),
        ];

        return view('admin.history.index', $data);
    }

    public function inIndex(Request $request)
    {
        $query = RiwayatIn::with('barang');

        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $data = $query->latest()->paginate(10)->withQueryString();
        return view('admin.history.history_in', compact('data'));
    }

    public function outIndex(Request $request)
    {
        $query = RiwayatOut::with('barang');

        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $data = $query->latest()->paginate(10)->withQueryString();
        return view('admin.history.history_out', compact('data'));
    }

    public function moveIndex(Request $request)
    {
        $query = RiwayatMove::with('barang');

        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $data = $query->latest()->paginate(10)->withQueryString();
        return view('admin.history.history_move', compact('data'));
    }

    public function stoIndex(Request $request)
    {
        $query = RiwayatSto::latest();

        if ($request->filled('bulan')) {
            [$year, $month] = explode('-', $request->bulan);
            $query->whereYear('tanggal', $year)
                  ->whereMonth('tanggal', $month);
        }

        $riwayat     = $query->paginate(10)->withQueryString();
        $filterBulan = $request->bulan;

        return view('admin.history.history_sto', compact('riwayat', 'filterBulan'));
    }

    public function stoDetail($id)
    {
        $sto = RiwayatSto::with('details.barang')->findOrFail($id);
        return view('admin.history.history_sto_detail', compact('sto'));
    }

    public function exportIn(Request $request)
    {
        $fileName = 'riwayat_masuk_' . date('Y-m-d') . '.csv';

        $query = RiwayatIn::with('barang');
        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }
        $data = $query->latest()->get();

        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'PIC', 'Barcode', 'Nama Barang', 'Qty', 'Keterangan']);
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->created_at,
                    $row->pic,
                    $row->barcode,
                    $row->barang->nama_barang ?? '-',
                    $row->qty,
                    $row->keterangan,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportOut(Request $request)
    {
        $fileName = 'riwayat_keluar_' . date('Y-m-d') . '.csv';

        $query = RiwayatOut::with('barang');
        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }
        $data = $query->latest()->get();

        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'PIC', 'Barcode', 'Nama Barang', 'Qty', 'Keterangan']);
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->created_at,
                    $row->pic,
                    $row->barcode,
                    $row->barang->nama_barang ?? '-',
                    $row->qty,
                    $row->keterangan,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportMove(Request $request)
    {
        $fileName = 'riwayat_pindah_' . date('Y-m-d') . '.csv';

        $query = RiwayatMove::with('barang');
        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }
        $data = $query->latest()->get();

        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'PIC', 'Barcode', 'Nama Barang', 'Qty', 'Dari', 'Ke']);
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->created_at,
                    $row->pic,
                    $row->barcode,
                    $row->barang->nama_barang ?? '-',
                    $row->qty,
                    $row->from,
                    $row->to,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function historyPermintaan(Request $request)
    {
        $query = Permintaan::with('details.barang')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('pic', 'like', '%' . $request->search . '%')
                  ->orWhere('commodity', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date,
            ]);
        }

        $permintaans = $query->paginate(10)->withQueryString();

        return view('admin.history.history_permintaan', compact('permintaans'));
    }
}