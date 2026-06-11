@extends('layouts.admin')

@section('title', 'Detail STO')
@section('subtitle', 'Detail Stock Take Over')

@section('content')
<div class="space-y-6">

    <a href="{{ route('admin.history.sto.index') }}"
        class="inline-flex items-center gap-2 text-[10px] font-black text-gray-400 hover:text-gray-600 uppercase tracking-widest transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>

    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="bg-[#1E4D9C] px-10 py-8 text-white">
            <h3 class="text-2xl font-black uppercase tracking-tight">Detail STO</h3>
            <p class="text-teal-200 text-[10px] mt-1 uppercase tracking-[0.2em] font-bold">
                {{ \Carbon\Carbon::parse($sto->tanggal)->translatedFormat('d F Y') }}
            </p>
        </div>

        <div class="p-8 border-b border-gray-100">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">PIC</p>
                    <p class="text-sm font-black text-gray-800 mt-1">{{ $sto->pic }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Item</p>
                    <p class="text-sm font-black text-gray-800 mt-1">{{ $sto->total_item }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-green-400 uppercase tracking-widest">Match</p>
                    <p class="text-sm font-black text-green-600 mt-1">{{ $sto->total_match }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-red-400 uppercase tracking-widest">Selisih</p>
                    <p class="text-sm font-black text-red-500 mt-1">{{ $sto->total_selisih }}</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Barang</th>
                        <th class="px-8 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Qty Sistem</th>
                        <th class="px-8 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Qty Fisik</th>
                        <th class="px-8 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Selisih</th>
                        <th class="px-8 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Adjusted</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($sto->details as $detail)
                    <tr class="hover:bg-gray-50/50 transition-all {{ $detail->selisih !== 0 ? 'bg-red-50/20' : '' }}">
                        <td class="px-8 py-4">
                            <p class="text-sm font-black text-gray-800">{{ $detail->barang->nama_barang ?? '-' }}</p>
                            <p class="text-[10px] text-gray-400 font-bold">{{ $detail->barang->kode_barang ?? '-' }}</p>
                        </td>
                        <td class="px-8 py-4 text-center text-sm font-black text-[#1E4D9C]">{{ $detail->qty_sistem }}</td>
                        <td class="px-8 py-4 text-center text-sm font-black text-gray-800">{{ $detail->qty_fisik }}</td>
                        <td class="px-8 py-4 text-center">
                            <span class="text-sm font-black {{ $detail->selisih === 0 ? 'text-green-500' : ($detail->selisih > 0 ? 'text-blue-500' : 'text-red-500') }}">
                                {{ $detail->selisih > 0 ? '+' : '' }}{{ $detail->selisih }}
                            </span>
                        </td>
                        <td class="px-8 py-4 text-center">
                            @if($detail->selisih === 0)
                                <span class="inline-flex px-3 py-1 bg-green-100 text-green-700 text-[9px] font-black uppercase tracking-widest rounded-full">Match</span>
                            @elseif($detail->selisih > 0)
                                <span class="inline-flex px-3 py-1 bg-blue-100 text-blue-700 text-[9px] font-black uppercase tracking-widest rounded-full">Surplus</span>
                            @else
                                <span class="inline-flex px-3 py-1 bg-red-100 text-red-700 text-[9px] font-black uppercase tracking-widest rounded-full">Deficit</span>
                            @endif
                        </td>
                        <td class="px-8 py-4 text-center">
                            @if($detail->is_adjusted)
                                <span class="inline-flex px-3 py-1 bg-purple-100 text-purple-700 text-[9px] font-black uppercase tracking-widest rounded-full">✓ Adjusted</span>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection