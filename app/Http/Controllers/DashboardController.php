<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\RiwayatIn;
use App\Models\RiwayatOut;
use App\Models\RiwayatSto;
use App\Models\Permintaan;
use App\Models\User;
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
    // IMPROVEMENT
    // =====================
    public function improvement()
    {
        // Cache stat cards & chart — 5 menit
        $stats = Cache::remember('dashboard_improvement_stats', now()->addMinutes(5), function () {
            $riwayatMasuk = RiwayatIn::select(
                    DB::raw('DATE(created_at) as tanggal'),
                    DB::raw('SUM(qty) as total')
                )
                ->where('created_at', '>=', now()->subDays(6)->startOfDay())
                ->groupBy('tanggal')
                ->pluck('total', 'tanggal');

            $riwayatKeluar = RiwayatOut::select(
                    DB::raw('DATE(created_at) as tanggal'),
                    DB::raw('SUM(qty) as total')
                )
                ->where('created_at', '>=', now()->subDays(6)->startOfDay())
                ->groupBy('tanggal')
                ->pluck('total', 'tanggal');

            $labels = collect();
            $dataMasuk = collect();
            $dataKeluar = collect();

            for ($i = 6; $i >= 0; $i--) {
                $tgl = now()->subDays($i)->format('Y-m-d');
                $labels->push(now()->subDays($i)->format('d M'));
                $dataMasuk->push($riwayatMasuk[$tgl] ?? 0);
                $dataKeluar->push($riwayatKeluar[$tgl] ?? 0);
            }

            return [
                'totalBarang'  => Barang::count(),
                'totalStok'    => Barang::sum('qty'),
                'barangMasuk'  => RiwayatIn::whereMonth('created_at', now()->month)->sum('qty'),
                'barangKeluar' => RiwayatOut::whereMonth('created_at', now()->month)->sum('qty'),
                'stockAman'    => Barang::whereColumn('qty', '>=', 'min')->count(),
                'stockKurang'  => Barang::whereColumn('qty', '<', 'min')->where('qty', '>', 0)->count(),
                'stockHabis'   => Barang::where('qty', 0)->count(),
                'stokBarang'   => Barang::select('nama_barang', 'qty', 'min')->orderByDesc('qty')->limit(8)->get(),
                'labels'       => $labels,
                'dataMasuk'    => $dataMasuk,
                'dataKeluar'   => $dataKeluar,
            ];
        });

        // Permintaan terbaru — cache 2 menit (key per role supaya tidak collision)
        $permintaanTerbaru = Cache::remember('dashboard_permintaan_improvement', now()->addMinutes(2), function () {
            return Permintaan::latest()->limit(5)->get();
        });

        // STO reminder — cache 10 menit
        $stoData = Cache::remember('dashboard_sto_reminder', now()->addMinutes(10), function () {
            $stoBulanIni = RiwayatSto::whereYear('tanggal', now()->year)
                ->whereMonth('tanggal', now()->month)
                ->latest('tanggal')
                ->first();
            return [
                'reminderSto' => now()->day >= 25 && is_null($stoBulanIni),
                'stoTerakhir' => $stoBulanIni,
            ];
        });

        return view('improvement.dashboard', array_merge(
            $stats,
            compact('permintaanTerbaru'),
            $stoData
        ));
    }

    // =====================
    // ADMIN
    // =====================
    public function admin()
    {
        $stats = Cache::remember('dashboard_admin_stats', now()->addMinutes(5), function () {
            $riwayatMasuk = RiwayatIn::select(
                    DB::raw('DATE(created_at) as tanggal'),
                    DB::raw('SUM(qty) as total')
                )
                ->where('created_at', '>=', now()->subDays(6)->startOfDay())
                ->groupBy('tanggal')
                ->pluck('total', 'tanggal');

            $riwayatKeluar = RiwayatOut::select(
                    DB::raw('DATE(created_at) as tanggal'),
                    DB::raw('SUM(qty) as total')
                )
                ->where('created_at', '>=', now()->subDays(6)->startOfDay())
                ->groupBy('tanggal')
                ->pluck('total', 'tanggal');

            $labels = collect();
            $dataMasuk = collect();
            $dataKeluar = collect();

            for ($i = 6; $i >= 0; $i--) {
                $tgl = now()->subDays($i)->format('Y-m-d');
                $labels->push(now()->subDays($i)->format('d M'));
                $dataMasuk->push($riwayatMasuk[$tgl] ?? 0);
                $dataKeluar->push($riwayatKeluar[$tgl] ?? 0);
            }

            return [
                'totalBarang'  => Barang::count(),
                'totalStok'    => Barang::sum('qty'),
                'totalUser'    => User::count(),
                'barangMasuk'  => RiwayatIn::whereMonth('created_at', now()->month)->sum('qty'),
                'barangKeluar' => RiwayatOut::whereMonth('created_at', now()->month)->sum('qty'),
                'stockAman'    => Barang::whereColumn('qty', '>=', 'min')->count(),
                'stockKurang'  => Barang::whereColumn('qty', '<', 'min')->where('qty', '>', 0)->count(),
                'stockHabis'   => Barang::where('qty', 0)->count(),
                'labels'       => $labels,
                'dataMasuk'    => $dataMasuk,
                'dataKeluar'   => $dataKeluar,
                'topKeluar'    => RiwayatOut::select('barang_id', DB::raw('SUM(qty) as total'))
                                    ->whereMonth('created_at', now()->month)
                                    ->groupBy('barang_id')
                                    ->orderByDesc('total')
                                    ->with('barang')
                                    ->limit(5)
                                    ->get(),
                'lowStocks'    => Barang::whereColumn('qty', '<=', 'min')->orderBy('qty')->limit(5)->get(),
            ];
        });

        $permintaanTerbaru = Cache::remember('dashboard_permintaan_admin', now()->addMinutes(2), function () {
            return Permintaan::latest()->limit(5)->get();
        });

        return view('admin.dashboard', array_merge(
            $stats,
            compact('permintaanTerbaru')
        ));
    }
}