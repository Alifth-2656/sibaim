{{-- resources/views/improvement/kelola_barang/sto_result.blade.php --}}
@extends('layouts.admin')

@section('title', 'Hasil STO')
@section('subtitle', 'Review selisih sebelum konfirmasi')

@section('content')
@php
    $totalMatch   = collect($results)->where('status', 'match')->count();
    $totalSelisih = collect($results)->where('status', '!=', 'match')->count();
    $totalScanned = count($results);
    $totalBarang  = $totalBarang ?? $totalScanned; // fallback jika tidak dipass dari controller
    $pctCoverage  = $totalBarang > 0 ? round(($totalScanned / $totalBarang) * 100) : 100;
    $notScanned   = $totalBarang - $totalScanned;
@endphp

<form action="{{ route('admin.kelola_barang.sto.confirm') }}" method="POST">
    @csrf
    <div class="space-y-6">

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Coverage --}}
            <div class="col-span-2 md:col-span-1 bg-white rounded-[2rem] border border-[#1E4D9C]/20 p-6 shadow-sm">
                <p class="text-[10px] font-black text-[#1E4D9C]/60 uppercase tracking-widest">Dicek / Total</p>
                <div class="flex items-baseline gap-1 mt-2">
                    <p class="text-3xl font-black text-[#1E4D9C]">{{ $totalScanned }}</p>
                    <p class="text-sm font-black text-gray-300">/ {{ $totalBarang }}</p>
                </div>
                {{-- Mini progress bar --}}
                <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                    <div class="h-1.5 rounded-full {{ $pctCoverage === 100 ? 'bg-green-400' : 'bg-[#5EEAD4]' }} transition-all"
                        style="width: {{ $pctCoverage }}%"></div>
                </div>
                <p class="text-[9px] font-black mt-1 {{ $pctCoverage === 100 ? 'text-green-500' : 'text-gray-300' }} uppercase tracking-widest">
                    {{ $pctCoverage }}% · {{ $notScanned > 0 ? $notScanned . ' belum dicek' : '✓ Semua tercek' }}
                </p>
            </div>

            {{-- Total Item --}}
            <div class="bg-white rounded-[2rem] border border-gray-100 p-6 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Item</p>
                <p class="text-3xl font-black text-gray-800 mt-2">{{ $totalScanned }}</p>
                <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mt-1">item dicek</p>
            </div>

            {{-- Match --}}
            <div class="bg-white rounded-[2rem] border border-green-100 p-6 shadow-sm">
                <p class="text-[10px] font-black text-green-400 uppercase tracking-widest">Match</p>
                <p class="text-3xl font-black text-green-600 mt-2">{{ $totalMatch }}</p>
                <p class="text-[9px] font-black text-green-300 uppercase tracking-widest mt-1">sesuai sistem</p>
            </div>

            {{-- Selisih --}}
            <div class="bg-white rounded-[2rem] border border-red-100 p-6 shadow-sm">
                <p class="text-[10px] font-black text-red-400 uppercase tracking-widest">Selisih</p>
                <p class="text-3xl font-black text-red-500 mt-2">{{ $totalSelisih }}</p>
                <p class="text-[9px] font-black text-red-300 uppercase tracking-widest mt-1">perlu dicek</p>
            </div>
        </div>

        {{-- WARNING: ada barang yang tidak dicek --}}
        @if($notScanned > 0)
        <div class="bg-amber-50 border border-amber-200 rounded-2xl px-6 py-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
            </svg>
            <p class="text-sm font-bold text-amber-700">
                <span class="font-black">{{ $notScanned }} barang</span> tidak dicek dalam STO ini dan tidak akan diupdate datanya.
            </p>
        </div>
        @endif

        {{-- TABEL HASIL --}}
        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
                <div>
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
                {{-- Badge coverage di header tabel --}}
                <div class="flex items-center gap-2 px-4 py-2 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Coverage</span>
                    <span class="text-sm font-black {{ $pctCoverage === 100 ? 'text-green-500' : 'text-[#1E4D9C]' }}">
                        {{ $totalScanned }}/{{ $totalBarang }}
                    </span>
                    <span class="text-[9px] font-black {{ $pctCoverage === 100 ? 'text-green-400' : 'text-gray-300' }} uppercase tracking-widest">
                        ({{ $pctCoverage }}%)
                    </span>
                </div>
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
                <a href="{{ route('admin.kelola_barang.sto') }}"
                    class="text-[10px] font-black text-gray-400 hover:text-gray-600 uppercase tracking-widest transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Ulangi STO
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