<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\RiwayatIn;
use App\Models\RiwayatOut;
use App\Models\Permintaan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // =====================
    // COMODITY
    // =====================
    public function comodity()
    {
        $totalJenis  = Barang::count();
        $totalStok   = Barang::sum('qty');
        $stokMenipis = Barang::whereColumn('qty', '<=', 'min')->count();

        $permintaanTerakhir = Permintaan::latest()->take(5)->get();

        return view('comodity.dashboard', compact(
            'totalJenis',
            'totalStok',
            'stokMenipis',
            'permintaanTerakhir'
        ));
    }

    // =====================
    // IMPROVEMENT
    // =====================
    public function improvement()
    {
        $totalBarang  = Barang::count();
        $totalStok    = Barang::sum('qty');
        $barangMasuk  = RiwayatIn::whereMonth('created_at', now()->month)->sum('qty');
        $barangKeluar = RiwayatOut::whereMonth('created_at', now()->month)->sum('qty');

        $stockAman   = Barang::whereColumn('qty', '>=', 'min')->count();
        $stockKurang = Barang::whereColumn('qty', '<', 'min')->where('qty', '>', 0)->count();
        $stockHabis  = Barang::where('qty', 0)->count();

        $stokBarang = Barang::select('nama_barang', 'qty', 'min')
            ->orderByDesc('qty')
            ->limit(8)
            ->get();

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

        $labels     = collect();
        $dataMasuk  = collect();
        $dataKeluar = collect();

        for ($i = 6; $i >= 0; $i--) {
            $tgl = now()->subDays($i)->format('Y-m-d');
            $labels->push(now()->subDays($i)->format('d M'));
            $dataMasuk->push($riwayatMasuk[$tgl] ?? 0);
            $dataKeluar->push($riwayatKeluar[$tgl] ?? 0);
        }

        $permintaanTerbaru = Permintaan::latest()->limit(5)->get();

        return view('improvement.dashboard', compact(
            'totalBarang', 'totalStok', 'barangMasuk', 'barangKeluar',
            'stockAman', 'stockKurang', 'stockHabis',
            'stokBarang', 'permintaanTerbaru',
            'labels', 'dataMasuk', 'dataKeluar'
        ));
    }

    // =====================
    // ADMIN
    // =====================
    public function admin()
    {
        // Stat Cards
        $totalBarang  = Barang::count();
        $totalStok    = Barang::sum('qty');
        $totalUser    = User::count();
        $barangMasuk  = RiwayatIn::whereMonth('created_at', now()->month)->sum('qty');
        $barangKeluar = RiwayatOut::whereMonth('created_at', now()->month)->sum('qty');

        // Inventory Status
        $stockAman   = Barang::whereColumn('qty', '>=', 'min')->count();
        $stockKurang = Barang::whereColumn('qty', '<', 'min')->where('qty', '>', 0)->count();
        $stockHabis  = Barang::where('qty', 0)->count();

        // Line Chart — 7 hari terakhir
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

        $labels     = collect();
        $dataMasuk  = collect();
        $dataKeluar = collect();

        for ($i = 6; $i >= 0; $i--) {
            $tgl = now()->subDays($i)->format('Y-m-d');
            $labels->push(now()->subDays($i)->format('d M'));
            $dataMasuk->push($riwayatMasuk[$tgl] ?? 0);
            $dataKeluar->push($riwayatKeluar[$tgl] ?? 0);
        }

        // Top 5 barang paling sering keluar bulan ini
        $topKeluar = RiwayatOut::select('barang_id', DB::raw('SUM(qty) as total'))
            ->whereMonth('created_at', now()->month)
            ->groupBy('barang_id')
            ->orderByDesc('total')
            ->with('barang')
            ->limit(5)
            ->get();

        // Permintaan terbaru dari comodity
        $permintaanTerbaru = Permintaan::latest()->limit(5)->get();

        // Low stock
        $lowStocks = Barang::whereColumn('qty', '<=', 'min')->orderBy('qty')->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalBarang', 'totalStok', 'totalUser',
            'barangMasuk', 'barangKeluar',
            'stockAman', 'stockKurang', 'stockHabis',
            'labels', 'dataMasuk', 'dataKeluar',
            'topKeluar', 'permintaanTerbaru', 'lowStocks'
        ));
    }
}