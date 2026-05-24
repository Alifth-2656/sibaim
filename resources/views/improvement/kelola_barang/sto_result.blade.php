{{-- resources/views/improvement/kelola_barang/sto_result.blade.php --}}
@extends('layouts.improvement')

@section('title', 'Hasil STO')
@section('subtitle', 'Review selisih sebelum konfirmasi')

@section('content')
@php
    $totalMatch   = collect($results)->where('status', 'match')->count();
    $totalSelisih = collect($results)->where('status', '!=', 'match')->count();
@endphp

<form action="{{ route('improvement.kelola_barang.sto.confirm') }}" method="POST">
    @csrf
    <div class="space-y-6">

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-[2rem] border border-gray-100 p-6 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Item</p>
                <p class="text-3xl font-black text-gray-800 mt-2">{{ count($results) }}</p>
            </div>
            <div class="bg-white rounded-[2rem] border border-green-100 p-6 shadow-sm">
                <p class="text-[10px] font-black text-green-400 uppercase tracking-widest">Match</p>
                <p class="text-3xl font-black text-green-600 mt-2">{{ $totalMatch }}</p>
            </div>
            <div class="bg-white rounded-[2rem] border border-red-100 p-6 shadow-sm">
                <p class="text-[10px] font-black text-red-400 uppercase tracking-widest">Selisih</p>
                <p class="text-3xl font-black text-red-500 mt-2">{{ $totalSelisih }}</p>
            </div>
        </div>

        {{-- TABEL HASIL --}}
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100">
                <h4 class="font-black text-gray-800 uppercase tracking-widest text-sm">Detail Perbandingan</h4>
                @if($totalSelisih > 0)
                <p class="text-[10px] text-red-400 font-black uppercase tracking-widest mt-1">
                    ⚠ Ada {{ $totalSelisih }} item tidak matching — centang untuk adjust data sistem
                </p>
                @else
                <p class="text-[10px] text-green-500 font-black uppercase tracking-widest mt-1">
                    ✓ Semua item matching
                </p>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Barang</th>
                            <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Qty Sistem</th>
                            <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Qty Fisik</th>
                            <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Selisih</th>
                            <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Adjust DB</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($results as $item)
                        <tr class="hover:bg-gray-50/50 transition-all {{ $item['status'] !== 'match' ? 'bg-red-50/30' : '' }}">
                            <td class="px-6 py-4">
                                <p class="text-sm font-black text-gray-800">{{ $item['nama_barang'] }}</p>
                                <p class="text-[10px] text-gray-400 font-bold">{{ $item['kode_barang'] }}</p>
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-black text-[#1E4D9C]">{{ $item['qty_sistem'] }}</td>
                            <td class="px-6 py-4 text-center text-sm font-black text-gray-800">{{ $item['qty_fisik'] }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-black {{ $item['selisih'] > 0 ? 'text-green-500' : ($item['selisih'] < 0 ? 'text-red-500' : 'text-gray-400') }}">
                                    {{ $item['selisih'] > 0 ? '+' : '' }}{{ $item['selisih'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item['status'] === 'match')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 text-[9px] font-black uppercase tracking-widest rounded-full">
                                        ✓ Match
                                    </span>
                                @elseif($item['status'] === 'surplus')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 text-[9px] font-black uppercase tracking-widest rounded-full">
                                        ↑ Surplus
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 text-[9px] font-black uppercase tracking-widest rounded-full">
                                        ↓ Deficit
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item['status'] !== 'match')
                                <input type="checkbox"
                                    name="adjust[]"
                                    value="{{ $item['barang_id'] }}"
                                    class="w-4 h-4 accent-[#1E4D9C] cursor-pointer">
                                @else
                                <span class="text-gray-200 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-8 py-6 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('improvement.kelola_barang.sto') }}"
                    class="text-[10px] font-black text-gray-400 hover:text-gray-600 uppercase tracking-widest transition-all">
                    ← Ulangi STO
                </a>
                <button type="submit"
                    class="px-8 py-3 bg-[#1E4D9C] text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-[#163d7d] transition-all">
                    Simpan & Konfirmasi →
                </button>
            </div>
        </div>
    </div>
</form>
@endsection