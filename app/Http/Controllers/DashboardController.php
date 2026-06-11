<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\RiwayatIn;
use App\Models\RiwayatOut;
use App\Models\RiwayatSto;
use App\Models\Permintaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // =====================
    // COMODITY
    // =====================
    public function comodity()
    {
        $data = Cache::remember('dashboard_comodity', now()->addMinutes(5), function () {
            return [
                'totalJenis'  => Barang::count(),
                'totalStok'   => Barang::sum('qty'),
                'stokMenipis' => Barang::whereColumn('qty', '<=', 'min')->count(),
            ];
        });

        $permintaanTerakhir = Permintaan::latest()->take(5)->get();

        return view('comodity.dashboard', array_merge($data, compact('permintaanTerakhir')));
    }


    // =====================
    // ADMIN
    // =====================
    public function admin(Request $request)
    {
        // Filter tanggal — default: hari ini
        $dari   = $request->input('dari', now()->toDateString());
        $sampai = $request->input('sampai', now()->toDateString());

        $dariCarbon   = \Carbon\Carbon::parse($dari)->startOfDay();
        $sampaiCarbon = \Carbon\Carbon::parse($sampai)->endOfDay();

        // Hitung rentang hari untuk grafik (max 60 hari)
        $diffDays = $dariCarbon->diffInDays($sampaiCarbon);
        $diffDays = min($diffDays, 59);

        // Data grafik masuk/keluar per hari dalam rentang filter
        $riwayatMasuk = RiwayatIn::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('SUM(qty) as total')
        )
            ->whereBetween('created_at', [$dariCarbon, $sampaiCarbon])
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $riwayatKeluar = RiwayatOut::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('SUM(qty) as total')
        )
            ->whereBetween('created_at', [$dariCarbon, $sampaiCarbon])
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $labels     = collect();
        $dataMasuk  = collect();
        $dataKeluar = collect();

        for ($i = $diffDays; $i >= 0; $i--) {
            $tgl = $sampaiCarbon->copy()->subDays($i)->format('Y-m-d');
            $labels->push(\Carbon\Carbon::parse($tgl)->format('d M'));
            $dataMasuk->push((int) ($riwayatMasuk[$tgl] ?? 0));
            $dataKeluar->push((int) ($riwayatKeluar[$tgl] ?? 0));
        }

        // =========================
        // BARANG MASUK VS KELUAR
        // =========================

        $barangMasukKeluar = Barang::select('id', 'nama_barang')
            ->get()
            ->map(function ($barang) use ($dariCarbon, $sampaiCarbon) {

                $masuk = RiwayatIn::where('barang_id', $barang->id)
                    ->whereBetween('created_at', [$dariCarbon, $sampaiCarbon])
                    ->sum('qty');

                $keluar = RiwayatOut::where('barang_id', $barang->id)
                    ->whereBetween('created_at', [$dariCarbon, $sampaiCarbon])
                    ->sum('qty');

                return [
                    'nama_barang' => $barang->nama_barang,
                    'masuk' => (int) $masuk,
                    'keluar' => (int) $keluar,
                ];
            })
            ->sortByDesc(fn($item) => $item['masuk'] + $item['keluar'])
            ->take(8)
            ->values();

        $commodityLabels = $barangMasukKeluar->pluck('nama_barang');
        $commodityMasuk = $barangMasukKeluar->pluck('masuk');
        $commodityKeluar = $barangMasukKeluar->pluck('keluar');


        // =========================
        // STOK KOSONG
        // =========================

        $stokKosong = Barang::where('qty', '<=', 0)
            ->orderBy('nama_barang')
            ->get();

        // Stat cards — selalu cached (tidak tergantung filter tanggal)
        $stats = Cache::remember('dashboard_admin_stats', now()->addMinutes(5), function () {
            return [
                'totalBarang'  => Barang::count(),
                'totalStok'    => Barang::sum('qty'),
                'totalUser'    => User::count(),
                'barangMasuk'  => RiwayatIn::whereMonth('created_at', now()->month)->sum('qty'),
                'barangKeluar' => RiwayatOut::whereMonth('created_at', now()->month)->sum('qty'),
                'stockAman'    => Barang::whereColumn('qty', '>=', 'min')->count(),
                'stockKurang'  => Barang::whereColumn('qty', '<', 'min')->where('qty', '>', 0)->count(),
                'stockHabis'   => Barang::where('qty', 0)->count(),
                'lowStocks'    => Barang::whereColumn('qty', '<=', 'min')->orderBy('qty')->limit(5)->get()->toArray(),
            ];
        });

        $stats['lowStocks'] = collect($stats['lowStocks'])->map(fn($b) => (object) $b);

        $permintaanTerbaru = Cache::remember('dashboard_permintaan_admin', now()->addMinutes(2), function () {
            return Permintaan::latest()->limit(5)->get()->toArray();
        });
        $permintaanTerbaru = collect($permintaanTerbaru)->map(fn($p) => (object) $p);
        return view('admin.dashboard', array_merge($stats, compact(
            'permintaanTerbaru',

            'labels',
            'dataMasuk',
            'dataKeluar',

            'commodityLabels',
            'commodityMasuk',
            'commodityKeluar',

            'stokKosong',

            'dari',
            'sampai'
        )));
    }
}
