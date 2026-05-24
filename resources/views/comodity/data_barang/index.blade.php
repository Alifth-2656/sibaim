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


<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($barangs as $item)

    @php
    if ($item->qty == 0) {
    $color = 'bg-red-100 text-red-600';
    $status = 'Kosong';
    } elseif ($item->qty <= $item->min) {
        $color = 'bg-yellow-100 text-yellow-600';
        $status = 'Menipis';
        } else {
        $color = 'bg-green-100 text-green-600';
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

        @endforeach
</div>

@endsection