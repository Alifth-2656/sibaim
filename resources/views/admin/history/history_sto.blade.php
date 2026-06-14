@extends('layouts.admin')

@section('title', 'Riwayat STO')
@section('subtitle', 'History Stock Take Over')

@section('content')
<div class="space-y-6">

    <a href="{{ route('admin.history.index') }}"
        class="inline-flex items-center gap-2 text-[10px] font-black text-gray-400 hover:text-gray-600 uppercase tracking-widest transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>

    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="bg-[#1E4D9C] px-10 py-8 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="text-2xl font-black uppercase tracking-tight">Riwayat STO</h3>
                    <p class="text-teal-200 text-[10px] mt-1 uppercase tracking-[0.2em] font-bold">Stock Take Over History</p>
                </div>
                {{-- FILTER BULAN --}}
                <form method="GET" action="{{ route('admin.history.sto.index') }}" class="flex items-center gap-3">
                    <label class="text-[10px] font-black text-teal-200 uppercase tracking-widest whitespace-nowrap">Filter Bulan</label>
                    <input type="month" name="bulan" value="{{ $filterBulan ?? '' }}"
                        class="px-4 py-2 bg-white/10 border border-white/20 text-white text-sm font-bold rounded-xl focus:outline-none focus:ring-2 focus:ring-[#5EEAD4] placeholder-white/40 [color-scheme:dark]">
                    <button type="submit"
                        class="px-4 py-2 bg-[#5EEAD4] text-[#1E4D9C] text-[10px] font-black uppercase tracking-widest rounded-xl hover:opacity-80 transition-all">
                        Filter
                    </button>
                    @if($filterBulan)
                    <a href="{{ route('admin.history.sto.index') }}"
                        class="px-4 py-2 bg-white/10 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-white/20 transition-all">
                        Reset
                    </a>
                    @endif
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">PIC</th>
                        <th class="px-8 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Item</th>
                        <th class="px-8 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Match</th>
                        <th class="px-8 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Selisih</th>
                        <th class="px-8 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($riwayat as $sto)
                    <tr class="hover:bg-gray-50/50 transition-all">
                        <td class="px-8 py-4">
                            <p class="text-sm font-black text-gray-800">
                                {{ \Carbon\Carbon::parse($sto->tanggal)->translatedFormat('d F Y') }}
                            </p>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">
                                {{ \Carbon\Carbon::parse($sto->created_at)->diffForHumans() }}
                            </p>
                        </td>
                        <td class="px-8 py-4 text-sm font-bold text-gray-700">{{ $sto->pic }}</td>
                        <td class="px-8 py-4 text-center text-sm font-black text-gray-800">{{ $sto->total_item }}</td>
                        <td class="px-8 py-4 text-center text-sm font-black text-green-500">{{ $sto->total_match }}</td>
                        <td class="px-8 py-4 text-center text-sm font-black text-red-500">{{ $sto->total_selisih }}</td>
                        <td class="px-8 py-4 text-center">
                            @if($sto->total_selisih === 0)
                                <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 text-[9px] font-black uppercase tracking-widest rounded-full">
                                    ✓ All Match
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 text-[9px] font-black uppercase tracking-widest rounded-full">
                                    ⚠ Ada Selisih
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-4 text-center">
                            <a href="{{ route('admin.history.sto.detail', $sto->id) }}"
                                class="text-[10px] font-black text-[#1E4D9C] hover:text-[#5EEAD4] uppercase tracking-widest transition-all">
                                Detail →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-8 py-20 text-center text-[10px] font-black text-gray-200 uppercase tracking-widest">
                            @if($filterBulan)
                                Tidak ada STO pada bulan {{ \Carbon\Carbon::createFromFormat('Y-m', $filterBulan)->translatedFormat('F Y') }}
                            @else
                                Belum ada riwayat STO
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($riwayat->hasPages())
        <div class="px-8 py-6 border-t border-gray-100 flex items-center justify-between">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                Menampilkan {{ $riwayat->firstItem() }}–{{ $riwayat->lastItem() }} dari {{ $riwayat->total() }} STO
            </p>
            <div class="flex items-center gap-1">
                @if($riwayat->onFirstPage())
                    <span class="px-4 py-2 text-[10px] font-black text-gray-300 uppercase tracking-widest cursor-not-allowed">← Prev</span>
                @else
                    <a href="{{ $riwayat->previousPageUrl() }}" class="px-4 py-2 text-[10px] font-black text-[#1E4D9C] uppercase tracking-widest hover:text-[#5EEAD4] transition-all">← Prev</a>
                @endif

                @php
                    $current = $riwayat->currentPage();
                    $last    = $riwayat->lastPage();
                    $pages   = [1];
                    for ($i = max(2, $current - 2); $i <= min($last - 1, $current + 2); $i++) $pages[] = $i;
                    if ($last > 1) $pages[] = $last;
                    $pages = array_unique($pages); sort($pages);
                @endphp

                @php $prev = null; @endphp
                @foreach($pages as $page)
                    @if($prev !== null && $page - $prev > 1)
                        <span class="px-1 text-gray-300 font-black text-sm">…</span>
                    @endif
                    <a href="{{ $riwayat->url($page) }}"
                        class="w-8 h-8 flex items-center justify-center rounded-xl text-[10px] font-black transition-all {{ $page == $current ? 'bg-[#1E4D9C] text-white shadow-lg' : 'text-gray-400 hover:bg-gray-100' }}">
                        {{ $page }}
                    </a>
                    @php $prev = $page; @endphp
                @endforeach

                @if($riwayat->hasMorePages())
                    <a href="{{ $riwayat->nextPageUrl() }}" class="px-4 py-2 text-[10px] font-black text-[#1E4D9C] uppercase tracking-widest hover:text-[#5EEAD4] transition-all">Next →</a>
                @else
                    <span class="px-4 py-2 text-[10px] font-black text-gray-300 uppercase tracking-widest cursor-not-allowed">Next →</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
