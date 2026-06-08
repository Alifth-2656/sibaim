@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('subtitle', 'Overview semua aktivitas sistem')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=DM+Sans:wght@400;500;700;900&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .dashboard-root {
        font-family: 'DM Sans', sans-serif;
    }

    .mono {
        font-family: 'DM Mono', monospace;
    }
</style>
@endpush

@section('content')
<div class="dashboard-root space-y-5">

    {{-- ═══════════════════════════════════════ --}}
    {{-- FILTER TANGGAL                         --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-4">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Dari</label>
                <input type="date"
                    name="dari"
                    value="{{ $dari }}"
                    max="{{ $sampai }}"
                    class="mono px-4 py-2 rounded-xl border border-gray-200">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Sampai</label>
                <input type="date"
                    name="sampai"
                    value="{{ $sampai }}"
                    min="{{ $dari }}"
                    class="mono px-4 py-2 rounded-xl border border-gray-200">
            </div>
            <button type="submit"
                class="px-6 py-2 bg-[#1E4D9C] text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-blue-800 active:scale-95 transition-all">
                Terapkan
            </button>
            <a href="{{ route('admin.dashboard') }}"
                class="px-6 py-2 bg-gray-100 text-gray-500 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-gray-200 transition-all">
                Reset
            </a>
            <div class="ml-auto">
                <div class="bg-blue-50 border border-blue-200 rounded-xl px-4 py-2 text-right">

                    <p class="text-[11px] font-bold text-blue-700 uppercase tracking-wide">
                        Periode Data
                    </p>

                    <p class="mono text-sm font-extrabold text-blue-900">
                        @if($dari === $sampai)
                        {{ \Carbon\Carbon::parse($dari)->translatedFormat('d M Y') }}
                        @else
                        {{ \Carbon\Carbon::parse($dari)->translatedFormat('d M Y') }}
                        —
                        {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d M Y') }}
                        @endif
                    </p>

                </div>
            </div>
        </form>
    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- STAT CARDS                             --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">

        {{-- TOTAL BARANG --}}
        <div class="bg-[#1E4D9C] rounded-2xl p-6 shadow-md text-white">
            <p class="text-sm font-bold text-blue-100 mb-3">
                Total Jenis Barang
            </p>

            <p class="mono text-5xl font-extrabold leading-none text-white">
                {{ $totalBarang }}
            </p>

            <p class="text-xs text-blue-200 mt-3 font-medium">
                Item terdaftar
            </p>
        </div>

        {{-- TOTAL STOK --}}
        <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-200">
            <p class="text-sm font-bold text-gray-700 mb-3">
                Total Stok
            </p>

            <p class="mono text-5xl font-extrabold text-gray-900 leading-none">
                {{ number_format($totalStok) }}
            </p>

            <p class="text-xs text-gray-500 mt-3 font-medium">
                Unit keseluruhan
            </p>
        </div>

        {{-- BARANG MASUK --}}
        <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-200">
            <p class="text-sm font-bold text-gray-700 mb-3">
                Barang Masuk
            </p>

            <p class="mono text-5xl font-extrabold text-emerald-600 leading-none">
                +{{ number_format($barangMasuk) }}
            </p>

            <p class="text-xs text-gray-500 mt-3 font-medium">
                Bulan ini
            </p>
        </div>

        {{-- BARANG KELUAR --}}
        <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-200">
            <p class="text-sm font-bold text-gray-700 mb-3">
                Barang Keluar
            </p>

            <p class="mono text-5xl font-extrabold text-orange-500 leading-none">
                -{{ number_format($barangKeluar) }}
            </p>

            <p class="text-xs text-gray-500 mt-3 font-medium">
                Bulan ini
            </p>
        </div>

    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- INVENTORY STATUS                       --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="grid grid-cols-3 gap-5">

        <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-200 flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-emerald-100 flex items-center justify-center text-xl">
                ✓
            </div>

            <div>
                <p class="text-sm font-bold text-gray-700">
                    Stock Aman
                </p>

                <p class="mono text-3xl font-extrabold text-emerald-600">
                    {{ $stockAman }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-200 flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-amber-100 flex items-center justify-center text-xl">
                ⚠
            </div>

            <div>
                <p class="text-sm font-bold text-gray-700">
                    Stok Kurang
                </p>

                <p class="mono text-3xl font-extrabold text-amber-500">
                    {{ $stockKurang }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-200 flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-red-100 flex items-center justify-center text-xl">
                ✕
            </div>

            <div>
                <p class="text-sm font-bold text-gray-700">
                    Stok Habis
                </p>

                <p class="mono text-3xl font-extrabold text-red-500">
                    {{ $stockHabis }}
                </p>
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- BARANG MASUK VS KELUAR + STOK KOSONG   --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

        {{-- CHART --}}
        <div class="xl:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">

            <div class="flex items-center justify-between mb-5">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        Barang Masuk vs Barang Keluar
                    </p>

                    <p class="text-[10px] text-gray-300 font-medium mt-0.5">
                        Perbandingan quantity per barang
                    </p>
                </div>
            </div>

            <canvas id="commodityChart" height="120"></canvas>

        </div>

        {{-- STOK KOSONG --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-100">
                <p class="text-[10px] font-black text-red-500 uppercase tracking-widest">
                    Barang Stok Kosong
                </p>
            </div>

            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-[10px] font-black uppercase tracking-widest text-gray-400">
                            Barang
                        </th>

                        <th class="px-4 py-3 text-center text-[10px] font-black uppercase tracking-widest text-gray-400">
                            Qty
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($stokKosong as $barang)

                    <tr class="hover:bg-red-50 transition">

                        <td class="px-4 py-3">
                            <div class="font-semibold text-gray-700">
                                {{ $barang->nama_barang }}
                            </div>

                            <div class="text-xs text-gray-400 mono">
                                {{ $barang->kode_barang }}
                            </div>
                        </td>

                        <td class="px-4 py-3 text-center">
                            <span class="mono font-bold text-red-500">
                                {{ $barang->qty }}
                            </span>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="2"
                            class="py-10 text-center text-green-500 font-semibold">
                            ✓ Tidak ada stok kosong
                        </td>
                    </tr>

                    @endforelse

                </tbody>
            </table>

        </div>

    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- TABEL BAWAH                            --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Permintaan Terbaru --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Permintaan Terbaru</p>
                <a href="{{ route('admin.laporan.permintaan') }}"
                    class="text-[10px] font-black text-[#1E4D9C] uppercase tracking-widest hover:text-blue-400 transition-all">
                    Lihat Semua →
                </a>
            </div>
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">PIC</th>
                        <th class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Commodity</th>
                        <th class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($permintaanTerbaru as $p)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-[#1E4D9C]/10 text-[#1E4D9C] flex items-center justify-center text-[9px] font-black uppercase shrink-0">
                                    {{ strtoupper(substr($p->pic, 0, 2)) }}
                                </div>
                                <span class="text-sm font-bold text-gray-700">{{ $p->pic }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3.5 text-sm text-gray-500">{{ $p->commodity }}</td>
                        <td class="px-6 py-3.5 mono text-xs text-gray-400">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-10 text-center text-[10px] font-black text-gray-300 uppercase tracking-widest">
                            Belum ada permintaan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Low Stock --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">⚠ Barang Low Stock</p>
                <a href="{{ route('admin.inventory.index') }}"
                    class="text-[10px] font-black text-[#1E4D9C] uppercase tracking-widest hover:text-blue-400 transition-all">
                    Lihat Semua →
                </a>
            </div>
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Barang</th>
                        <th class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Stok</th>
                        <th class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Min</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($lowStocks as $barang)
                    <tr class="hover:bg-red-50/20 transition-colors">
                        <td class="px-6 py-3.5">
                            <p class="text-sm font-bold text-gray-800">{{ $barang->nama_barang }}</p>
                            <p class="mono text-[10px] text-gray-400">{{ $barang->kode_barang }}</p>
                        </td>
                        <td class="px-6 py-3.5 text-center mono font-bold text-red-500">{{ $barang->qty }}</td>
                        <td class="px-6 py-3.5 text-center mono font-bold text-gray-400">{{ $barang->min }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-10 text-center text-[10px] font-black text-gray-300 uppercase tracking-widest">
                            ✓ Semua stok aman
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script>
    // ── Data dari controller ───────────────────────────
    const labels = @json($labels);
    const dataMasuk = @json($dataMasuk);
    const dataKeluar = @json($dataKeluar);



    // ── Font default Chart.js ──────────────────────────
    Chart.defaults.font.family = "'DM Sans', sans-serif";
    Chart.defaults.font.size = 11;
    Chart.defaults.color = '#374151';

    // ── LINE CHART — Masuk vs Keluar ───────────────────
    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                    label: 'Masuk',
                    data: dataMasuk,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,0.08)',
                    borderWidth: 2.5,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
                {
                    label: 'Keluar',
                    data: dataKeluar,
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249,115,22,0.08)',
                    borderWidth: 2.5,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#f97316',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: {
                        family: "'DM Sans', sans-serif",
                        weight: '700',
                        size: 11
                    },
                    bodyFont: {
                        family: "'DM Mono', monospace",
                        size: 12
                    },
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y} unit`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9',
                        lineWidth: 1
                    },
                    ticks: {
                        font: {
                            family: "'DM Mono', monospace",
                            size: 10
                        },
                        stepSize: 1,
                    },
                    border: {
                        display: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            family: "'DM Sans', sans-serif",
                            size: 11,
                            weight: '600'
                        }
                    },
                    border: {
                        display: false
                    }
                }
            }
        }
    });

    const commodityLabels = @json($commodityLabels);
    const commodityMasuk = @json($commodityMasuk);
    const commodityKeluar = @json($commodityKeluar);

    new Chart(document.getElementById('commodityChart'), {

        type: 'bar',

        data: {
            labels: commodityLabels,

            datasets: [{
                    label: 'Barang Masuk',
                    data: commodityMasuk,
                    backgroundColor: '#10b981',
                    borderRadius: 8,
                    borderSkipped: false,
                },
                {
                    label: 'Barang Keluar',
                    data: commodityKeluar,
                    backgroundColor: '#1E4D9C',
                    borderRadius: 8,
                    borderSkipped: false,
                }
            ]
        },

        options: {
            responsive: true,

            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true
                    }
                }
            },

            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9'
                    }
                },

                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    
</script>
@endpush

@endsection