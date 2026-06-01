@extends('layouts.improvement')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="space-y-8">

    <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm">
        <h3 class="font-black text-gray-800 text-xl uppercase tracking-tight">History Center</h3>
        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Sistem Monitoring In, Out & Move Barang</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex flex-col items-center">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Barang</span>
            <span class="text-2xl font-black text-gray-800 mt-2">{{ $totalBarang }}</span>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex flex-col items-center">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Masuk (In)</span>
            <span class="text-2xl font-black text-emerald-600 mt-2">{{ $totalIn }}</span>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex flex-col items-center">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Keluar (Out)</span>
            <span class="text-2xl font-black text-red-600 mt-2">{{ $totalOut }}</span>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex flex-col items-center">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Pindah (Move)</span>
            <span class="text-2xl font-black text-blue-600 mt-2">{{ $totalMove }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <a href="{{ route('improvement.history.in.index') }}" class="group bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-lg transition flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </div>
            <h4 class="font-black text-gray-800 uppercase tracking-widest">Barang Masuk</h4>
            <p class="text-xs text-gray-400 font-bold mt-2">Lihat riwayat barang yang baru masuk ke gudang</p>
        </a>

        <a href="{{ route('improvement.history.out.index') }}" class="group bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-lg transition flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </div>
            <h4 class="font-black text-gray-800 uppercase tracking-widest">Barang Keluar</h4>
            <p class="text-xs text-gray-400 font-bold mt-2">Lihat riwayat barang yang keluar untuk pemakaian</p>
        </a>

        <a href="{{ route('improvement.history.move.index') }}" class="group bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-lg transition flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            </div>
            <h4 class="font-black text-gray-800 uppercase tracking-widest">Barang Pindah</h4>
            <p class="text-xs text-gray-400 font-bold mt-2">Lihat riwayat perpindahan lokasi barang</p>
        </a>

        <a href="{{ route('improvement.history.sto.index') }}"
            class="group bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-lg transition flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-purple-50 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <h4 class="font-black text-gray-800 uppercase tracking-widest">Stock Take Over</h4>
            <p class="text-xs text-gray-400 font-bold mt-2">Lihat riwayat pengecekan stok fisik vs sistem</p>
        </a>

    </div>
</div>
@endsection