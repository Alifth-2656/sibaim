@extends('layouts.comodity')

@section('title', 'Form Permintaan Barang')
@section('subtitle', 'Langkah 1: Identitas Pengambil')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('comodity.permintaan.pilih') }}" method="GET">

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-[#1E4D9C] px-8 py-6 text-white">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-white/10 rounded-2xl">
                            <svg class="w-6 h-6 text-[#5EEAD4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold uppercase tracking-tight">Form Permintaan Barang Improvement</h3>
                            <p class="text-blue-200 text-xs mt-1 uppercase tracking-widest font-semibold">Identitas & Kategori</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-blue-300 font-bold uppercase">Tanggal Hari Ini</p>
                        <p class="text-sm font-bold text-[#5EEAD4]">{{ now()->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">PIC (Penanggung Jawab)</label>
                        <input type="text" name="pic" placeholder="PIC" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-semibold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Commodity</label>
                        <input type="text" name="commodity" placeholder="COMODITY" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-semibold">
                    </div>
                </div>



                <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                    <p class="text-xs text-gray-400 font-medium italic">*Klik lanjut untuk memilih item barang yang akan diambil.</p>
                    <button type="submit" class="bg-[#5EEAD4] text-[#1E4D9C] px-10 py-3 rounded-2xl font-black text-sm shadow-lg shadow-teal-100 hover:scale-105 active:scale-95 transition-all flex items-center gap-3">
                        LANJUT PILIH BARANG
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        

        


</div>
@endsection