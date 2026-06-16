@extends('layouts.comodity')

@section('title', 'Comodity Inventory')
@section('subtitle', 'Daftar komoditas dan stok material gudang')

@section('content')

<form method="GET" action="{{ route('comodity.data_barang.index') }}" class="mb-6">
    <div class="flex gap-3">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari nama / kode barang..."
            class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1E4D9C]">
        <button
            type="submit"
            class="bg-[#1E4D9C] text-white px-5 py-2 rounded-xl hover:bg-[#163a75]">
            Search
        </button>
    </div>
</form>

{{-- Grid Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($barangs as $item)

    @php
        if ($item->qty == 0 && $item->min == 0 && $item->max == 0) {
            $color  = 'bg-gray-100 text-gray-500';
            $status = 'Tidak Dipakai';
        } elseif ($item->qty == 0) {
            $color  = 'bg-red-100 text-red-600';
            $status = 'Kosong';
        } elseif ($item->qty > $item->max) {
            $color  = 'bg-purple-100 text-purple-600';
            $status = 'Over';
        } elseif ($item->qty < $item->min) {
            $color  = 'bg-yellow-100 text-yellow-600';
            $status = 'Menipis';
        } else {
            $color  = 'bg-green-100 text-green-600';
            $status = 'Aman';
        }
    @endphp

    <div class="bg-white rounded-2xl shadow-sm border hover:shadow-md transition overflow-hidden">

        <div class="h-40 bg-gray-100 flex items-center justify-center">
            @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" class="h-full w-full object-cover">
            @else
                <span class="text-gray-400 text-sm">No Image</span>
            @endif
        </div>

        <div class="p-4 space-y-2">
            <div>
                <p class="text-xs text-[#1E4D9C] font-mono">{{ $item->kode_barang }}</p>
                <h3 class="font-bold text-gray-800">{{ $item->nama_barang }}</h3>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-sm font-bold">
                    {{ $item->qty }} {{ $item->satuan }}
                </span>
                <span class="px-2 py-1 text-xs rounded-lg font-semibold {{ $color }}">
                    {{ $status }}
                </span>
            </div>

            <p class="text-xs text-gray-500">📍 {{ $item->alamat }}</p>

            <a href="{{ route('comodity.permintaan.index') }}"
                class="block text-center bg-[#1E4D9C] hover:bg-[#163a75] text-white py-2 rounded-lg text-sm font-semibold transition">
                Minta Barang
            </a>
        </div>
    </div>

    @empty
    <div class="col-span-3 text-center py-16 text-gray-400">
        <p class="text-4xl mb-2">📦</p>
        <p class="text-xs font-bold uppercase tracking-widest">Tidak ada barang ditemukan.</p>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($barangs->hasPages())
<div class="mt-6 flex items-center justify-between">
    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
        Menampilkan {{ $barangs->firstItem() }}–{{ $barangs->lastItem() }}
        dari {{ $barangs->total() }} barang
    </p>

    <div class="flex items-center gap-1">
        {{-- Prev --}}
        @if($barangs->onFirstPage())
            <span class="px-4 py-2 text-[10px] font-black text-gray-300 uppercase tracking-widest cursor-not-allowed">← Prev</span>
        @else
            <a href="{{ $barangs->previousPageUrl() }}"
                class="px-4 py-2 text-[10px] font-black text-[#1E4D9C] uppercase tracking-widest hover:text-[#163a75] transition-all">← Prev</a>
        @endif

        {{-- Page numbers with ellipsis --}}
        @php
            $current = $barangs->currentPage();
            $last    = $barangs->lastPage();
            $pages   = [1];
            for ($i = max(2, $current - 2); $i <= min($last - 1, $current + 2); $i++) { $pages[] = $i; }
            if ($last > 1) $pages[] = $last;
            $pages = array_unique($pages); sort($pages);
            $prev = null;
        @endphp

        @foreach($pages as $page)
            @if($prev !== null && $page - $prev > 1)
                <span class="px-1 text-gray-300 font-black text-sm">…</span>
            @endif
            <a href="{{ $barangs->url($page) }}"
                class="w-8 h-8 flex items-center justify-center rounded-xl text-[10px] font-black transition-all
                    {{ $page == $current ? 'bg-[#1E4D9C] text-white shadow-lg' : 'text-gray-400 hover:bg-gray-100' }}">
                {{ $page }}
            </a>
            @php $prev = $page; @endphp
        @endforeach

        {{-- Next --}}
        @if($barangs->hasMorePages())
            <a href="{{ $barangs->nextPageUrl() }}"
                class="px-4 py-2 text-[10px] font-black text-[#1E4D9C] uppercase tracking-widest hover:text-[#163a75] transition-all">Next →</a>
        @else
            <span class="px-4 py-2 text-[10px] font-black text-gray-300 uppercase tracking-widest cursor-not-allowed">Next →</span>
        @endif
    </div>
</div>
@endif

@endsection