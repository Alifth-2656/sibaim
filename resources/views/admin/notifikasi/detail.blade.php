@extends('layouts.admin')

@section('title', 'Detail Permintaan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-[#1E4D9C] px-10 py-8 text-white">
            <h3 class="text-2xl font-black uppercase tracking-tight">Detail Permintaan</h3>
            <p class="text-teal-200 text-[10px] mt-1 uppercase tracking-[0.2em] font-bold">
                {{ \Carbon\Carbon::parse($permintaan->tanggal)->format('d M Y') }}
            </p>
        </div>
        <div class="p-10 space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">PIC</p>
                    <p class="text-sm font-black text-gray-800 mt-1">{{ $permintaan->pic }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Commodity</p>
                    <p class="text-sm font-black text-gray-800 mt-1">{{ $permintaan->commodity }}</p>
                </div>
                @if($permintaan->remark)
                <div class="col-span-2">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Remark</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $permintaan->remark }}</p>
                </div>
                @endif
            </div>

            <div class="overflow-x-auto rounded-2xl border border-gray-100">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Barang</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permintaan->details as $detail)
                        <tr class="border-b border-gray-50">
                            <td class="px-6 py-4">
                                <p class="text-sm font-black text-gray-800">{{ $detail->barang->nama_barang }}</p>
                                <p class="text-[10px] text-gray-400 font-bold">{{ $detail->barang->kode_barang }}</p>
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-black text-red-500">-{{ $detail->qty }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-[10px] font-black text-gray-400 hover:text-gray-600 uppercase tracking-widest transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection