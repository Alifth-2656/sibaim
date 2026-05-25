@extends('layouts.admin')

@section('title', 'Laporan Stok')
@section('subtitle', 'Rekap stok semua barang di gudang')

@section('content')
<div class="space-y-6">

    {{-- FILTER --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <form action="{{ route('admin.laporan.stok') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Cari Kode / Nama Barang</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Ketik kode atau nama barang..."
                        class="w-full px-4 py-3 bg-gray-50 rounded-xl border-none outline-none focus:ring-2 focus:ring-[#5EEAD4] font-bold text-sm text-gray-700">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Status Stok</label>
                    <select name="status" class="w-full px-4 py-3 bg-gray-50 rounded-xl border-none outline-none focus:ring-2 focus:ring-[#5EEAD4] font-bold text-sm text-gray-700">
                        <option value="">Semua Status</option>
                        <option value="aman"   {{ request('status') === 'aman'   ? 'selected' : '' }}>Aman</option>
                        <option value="kurang" {{ request('status') === 'kurang' ? 'selected' : '' }}>Kurang</option>
                        <option value="over"   {{ request('status') === 'over'   ? 'selected' : '' }}>Over</option>
                        <option value="habis"  {{ request('status') === 'habis'  ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 py-3 bg-[#1E4D9C] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-[#5EEAD4] hover:text-[#1E4D9C] transition-all">
                        Filter
                    </button>
                    <a href="{{ route('admin.laporan.stok') }}"
                        class="flex-1 py-3 text-center text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-all">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- TABEL --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                Total {{ $barangs->total() }} barang
            </p>
            <a href="{{ route('admin.laporan.stok.export', request()->query()) }}"
                class="flex items-center gap-2 px-5 py-2.5 bg-green-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-green-600 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export Excel
            </a>
        </div>

        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">No</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Kode</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Barang</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Satuan</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Min</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Max</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Stok</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $barang)
                @php
                    if ($barang->qty == 0) {
                        $status = 'Habis'; $color = 'bg-red-100 text-red-600';
                    } elseif ($barang->qty < $barang->min) {
                        $status = 'Kurang'; $color = 'bg-yellow-100 text-yellow-600';
                    } elseif ($barang->qty > $barang->max) {
                        $status = 'Over'; $color = 'bg-purple-100 text-purple-600';
                    } else {
                        $status = 'Aman'; $color = 'bg-green-100 text-green-600';
                    }
                @endphp
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition-all">
                    <td class="px-6 py-4 text-sm text-gray-400 font-bold">{{ $barangs->firstItem() + $loop->index }}</td>
                    <td class="px-6 py-4 text-sm font-black text-gray-800">{{ $barang->kode_barang }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-gray-700">{{ $barang->nama_barang }}</td>
                    <td class="px-6 py-4 text-sm text-gray-400">{{ $barang->satuan }}</td>
                    <td class="px-6 py-4 text-center text-sm font-bold text-gray-500">{{ $barang->min }}</td>
                    <td class="px-6 py-4 text-center text-sm font-bold text-gray-500">{{ $barang->max }}</td>
                    <td class="px-6 py-4 text-center text-sm font-black text-[#1E4D9C]">{{ $barang->qty }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 text-[10px] font-black rounded-full uppercase tracking-widest {{ $color }}">
                            {{ $status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center text-[10px] font-black text-gray-300 uppercase tracking-widest">
                        Tidak ada data barang
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($barangs->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                {{ $barangs->firstItem() }}–{{ $barangs->lastItem() }} dari {{ $barangs->total() }} barang
            </p>
            <div class="flex items-center gap-2">
                @if($barangs->onFirstPage())
                    <span class="px-3 py-1.5 text-[10px] font-black text-gray-300 cursor-not-allowed">← Prev</span>
                @else
                    <a href="{{ $barangs->previousPageUrl() }}" class="px-3 py-1.5 text-[10px] font-black text-[#1E4D9C] hover:text-[#5EEAD4] transition-all">← Prev</a>
                @endif
                @foreach($barangs->getUrlRange(1, $barangs->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="w-7 h-7 flex items-center justify-center rounded-lg text-[10px] font-black transition-all {{ $page == $barangs->currentPage() ? 'bg-[#1E4D9C] text-white' : 'text-gray-400 hover:bg-gray-100' }}">{{ $page }}</a>
                @endforeach
                @if($barangs->hasMorePages())
                    <a href="{{ $barangs->nextPageUrl() }}" class="px-3 py-1.5 text-[10px] font-black text-[#1E4D9C] hover:text-[#5EEAD4] transition-all">Next →</a>
                @else
                    <span class="px-3 py-1.5 text-[10px] font-black text-gray-300 cursor-not-allowed">Next →</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection