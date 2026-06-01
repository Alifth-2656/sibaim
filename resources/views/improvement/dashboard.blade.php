@extends('layouts.improvement')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan aktivitas improvement hari ini')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="space-y-6">

    {{-- REMINDER STO --}}
    @if($reminderSto)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl px-6 py-5 flex items-start gap-4">
        <div class="flex-shrink-0 w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-black text-amber-800 uppercase tracking-wide">Reminder — STO Bulan Ini Belum Dilakukan!</p>
            <p class="text-xs text-amber-600 mt-1">Sudah tanggal {{ now()->format('d') }} — Stock Take Over bulan {{ now()->translatedFormat('F Y') }} belum ada. Segera lakukan pengecekan stok fisik.</p>
        </div>
        <a href="{{ route('improvement.kelola_barang.sto') }}"
            class="flex-shrink-0 px-4 py-2 bg-amber-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-amber-600 transition-all">
            Mulai STO →
        </a>
    </div>
    @endif

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Jenis Barang</p>
            <p class="text-3xl font-black text-[#1E4D9C]">{{ $totalBarang }}</p>
            <p class="text-[10px] text-gray-400 mt-1">item terdaftar</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Stok</p>
            <p class="text-3xl font-black text-[#5EEAD4]">{{ $totalStok }}</p>
            <p class="text-[10px] text-gray-400 mt-1">unit keseluruhan</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Barang Masuk</p>
            <p class="text-3xl font-black text-green-500">{{ $barangMasuk }}</p>
            <p class="text-[10px] text-gray-400 mt-1">bulan ini</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Barang Keluar</p>
            <p class="text-3xl font-black text-orange-500">{{ $barangKeluar }}</p>
            <p class="text-[10px] text-gray-400 mt-1">bulan ini</p>
        </div>
    </div>

    {{-- INVENTORY STATUS --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Stock Aman</p>
                <p class="text-2xl font-black text-green-500">{{ $stockAman }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-yellow-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Stok Kurang</p>
                <p class="text-2xl font-black text-yellow-500">{{ $stockKurang }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Stok Habis</p>
                <p class="text-2xl font-black text-red-500">{{ $stockHabis }}</p>
            </div>
        </div>
    </div>

    {{-- CHART + TABLE --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- Line Chart Masuk vs Keluar --}}
        <div class="md:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Barang Masuk vs Keluar — 7 Hari Terakhir</p>
            <canvas id="lineChart" height="100"></canvas>
        </div>

        {{-- Bar Chart Stok --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Stok Barang Tertinggi</p>
            <canvas id="barChart" height="220"></canvas>
        </div>
    </div>

    {{-- TABEL PERMINTAAN TERBARU --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Permintaan Terbaru</p>
            <a href="{{ route('improvement.history.index') }}" class="text-[10px] font-black text-[#1E4D9C] uppercase tracking-widest hover:text-[#5EEAD4] transition-all">Lihat Semua →</a>
        </div>
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">PIC</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Commodity</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Tanggal</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Remark</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permintaanTerbaru as $p)
                <tr class="border-b border-gray-50 hover:bg-teal-50/20 transition-all">
                    <td class="px-6 py-4 text-sm font-bold text-gray-700">{{ $p->pic }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $p->commodity }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-400">{{ $p->remark ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-10 text-center text-[10px] font-black text-gray-300 uppercase tracking-widest">Belum ada permintaan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@push('scripts')
<script>
    const labels = @json($labels);
    const dataMasuk = @json($dataMasuk);
    const dataKeluar = @json($dataKeluar);
    const stokLabels = @json($stokBarang->pluck('nama_barang'));
    const stokQty = @json($stokBarang->pluck('qty'));
    const stokMin = @json($stokBarang->pluck('min'));

    // Line Chart
    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Masuk',
                    data: dataMasuk,
                    borderColor: '#5EEAD4',
                    backgroundColor: 'rgba(94,234,212,0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#5EEAD4',
                    pointRadius: 5,
                },
                {
                    label: 'Keluar',
                    data: dataKeluar,
                    borderColor: '#F97316',
                    backgroundColor: 'rgba(249,115,22,0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#F97316',
                    pointRadius: 5,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { font: { family: 'Plus Jakarta Sans', weight: 'bold', size: 11 } } } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { family: 'Plus Jakarta Sans' } } },
                x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans' } } }
            }
        }
    });

    // Bar Chart
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: stokLabels,
            datasets: [
                {
                    label: 'Stok',
                    data: stokQty,
                    backgroundColor: '#1E4D9C',
                    borderRadius: 8,
                },
                {
                    label: 'Min',
                    data: stokMin,
                    backgroundColor: '#5EEAD4',
                    borderRadius: 8,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { font: { family: 'Plus Jakarta Sans', weight: 'bold', size: 10 } } } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { family: 'Plus Jakarta Sans' } } },
                x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 9 }, maxRotation: 45 } }
            }
        }
    });
</script>
@endpush

@endsection