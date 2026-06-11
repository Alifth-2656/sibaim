@extends('layouts.admin')

@section('title', 'History Permintaan')
@section('subtitle', 'Riwayat semua permintaan barang dari commodity')

@section('content')
<div class="max-w-6xl mx-auto p-4 md:p-8">
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6">
            <div>
                <h3 class="text-2xl font-black text-gray-800 tracking-tight">History Permintaan</h3>
                <p class="text-sm text-gray-400 font-medium mt-1">Riwayat semua permintaan barang dari commodity</p>
            </div>
            <a href="{{ route('admin.history.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold transition flex items-center gap-2 text-sm">
                &larr; Kembali
            </a>
        </div>

        {{-- FILTER --}}
        <form action="{{ route('admin.history.permintaan.index') }}" method="GET" class="bg-gray-50 p-6 rounded-2xl mb-8 flex flex-wrap items-end gap-6 border border-gray-100">
            <div class="flex flex-col gap-2 flex-1 min-w-[200px]">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Cari PIC / Commodity</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Ketik nama PIC atau commodity..."
                    class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
            </div>
            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
            </div>
            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2.5 bg-gray-800 hover:bg-black text-white rounded-xl font-bold text-sm transition">
                    Filter
                </button>
                <a href="{{ route('admin.history.permintaan.index') }}" class="px-4 py-2.5 text-gray-400 hover:text-gray-600 rounded-xl font-bold text-sm flex items-center">
                    Reset
                </a>
            </div>
        </form>

        {{-- TOTAL --}}
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">
            Total {{ $permintaans->total() }} permintaan
        </p>

        {{-- TABEL --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 text-[10px] uppercase tracking-widest">
                        <th class="px-6 py-4 font-black">No</th>
                        <th class="px-6 py-4 font-black">PIC</th>
                        <th class="px-6 py-4 font-black">Commodity</th>
                        <th class="px-6 py-4 font-black">Tanggal</th>
                        <th class="px-6 py-4 font-black">Barang Diambil</th>
                        <th class="px-6 py-4 font-black text-center">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($permintaans as $p)
                    <tr class="hover:bg-emerald-50/50 transition duration-150">
                        <td class="px-6 py-5 text-sm text-gray-400 font-bold">{{ $permintaans->firstItem() + $loop->index }}</td>
                        <td class="px-6 py-5 text-sm font-black text-gray-800">{{ $p->pic }}</td>
                        <td class="px-6 py-5 text-sm text-gray-600 font-bold">{{ $p->commodity }}</td>
                        <td class="px-6 py-5 text-sm text-gray-500">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                        <td class="px-6 py-5">
                            <div class="space-y-1">
                                @foreach($p->details as $detail)
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black text-gray-500">{{ $detail->barang->nama_barang ?? '-' }}</span>
                                    <span class="px-2 py-0.5 bg-orange-50 text-orange-600 text-[9px] font-black rounded-full">x{{ $detail->qty }}</span>
                                </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <button onclick="openDetail({{ $p->id }})" class="p-2 text-[#1E4D9C] hover:text-emerald-500 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </td>
                    </tr>

                    {{-- Detail Row --}}
                    <tr id="detail-{{ $p->id }}" class="hidden">
                        <td colspan="7" class="px-6 pb-4">
                            <div class="bg-gray-50 rounded-2xl overflow-hidden border border-gray-100">
                                <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-7 h-7 bg-[#1E4D9C] rounded-lg flex items-center justify-center">
                                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V11" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black text-gray-800 uppercase tracking-tight">{{ $p->pic }} — {{ $p->commodity }}</p>
                                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 bg-[#1E4D9C]/10 text-[#1E4D9C] text-[9px] font-black rounded-full uppercase tracking-widest">
                                        {{ $p->details->count() }} item
                                    </span>
                                </div>
                                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @foreach($p->details as $detail)
                                    <div class="flex items-center justify-between bg-white rounded-xl px-4 py-3 border border-gray-100">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <span class="text-[9px] font-black text-gray-400">{{ strtoupper(substr($detail->barang->nama_barang ?? 'N', 0, 2)) }}</span>
                                            </div>
                                            <div>
                                                <p class="text-[11px] font-black text-gray-800">{{ $detail->barang->nama_barang ?? '-' }}</p>
                                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">{{ $detail->barang->kode_barang ?? '-' }} · {{ $detail->barang->satuan ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <span class="px-3 py-1.5 bg-orange-50 text-orange-600 text-[10px] font-black rounded-lg flex-shrink-0">
                                            ×{{ $detail->qty }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                                @if($p->remark)
                                <div class="px-5 pb-4">
                                    <div class="bg-yellow-50 border border-yellow-100 rounded-xl px-4 py-3">
                                        <p class="text-[9px] font-black text-yellow-600 uppercase tracking-widest mb-1">Remark</p>
                                        <p class="text-[11px] text-gray-600 font-bold">{{ $p->remark }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400 font-bold">
                            Belum ada data permintaan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($permintaans->hasPages())
        <div class="mt-6 flex items-center justify-between">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                {{ $permintaans->firstItem() }}–{{ $permintaans->lastItem() }} dari {{ $permintaans->total() }}
            </p>
            <div class="flex items-center gap-2">
                @if($permintaans->onFirstPage())
                <span class="px-3 py-1.5 text-[10px] font-black text-gray-300 cursor-not-allowed">← Prev</span>
                @else
                <a href="{{ $permintaans->previousPageUrl() }}" class="px-3 py-1.5 text-[10px] font-black text-[#1E4D9C] hover:text-emerald-500 transition-all">← Prev</a>
                @endif
                @foreach($permintaans->getUrlRange(1, $permintaans->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="w-7 h-7 flex items-center justify-center rounded-lg text-[10px] font-black transition-all {{ $page == $permintaans->currentPage() ? 'bg-[#1E4D9C] text-white' : 'text-gray-400 hover:bg-gray-100' }}">{{ $page }}</a>
                @endforeach
                @if($permintaans->hasMorePages())
                <a href="{{ $permintaans->nextPageUrl() }}" class="px-3 py-1.5 text-[10px] font-black text-[#1E4D9C] hover:text-emerald-500 transition-all">Next →</a>
                @else
                <span class="px-3 py-1.5 text-[10px] font-black text-gray-300 cursor-not-allowed">Next →</span>
                @endif
            </div>
        </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
    function openDetail(id) {
        const row = document.getElementById('detail-' + id);
        row.classList.toggle('hidden');
    }
</script>
@endpush

@endsection