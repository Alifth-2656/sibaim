@extends('layouts.admin')

@section('title', 'Laporan Permintaan')
@section('subtitle', 'Riwayat semua permintaan barang dari commodity')

@section('content')
<div class="space-y-6">

    {{-- FILTER --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <form action="{{ route('admin.laporan.permintaan') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Cari PIC / Commodity</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Ketik nama PIC atau commodity..."
                        class="w-full px-4 py-3 bg-gray-50 rounded-xl border-none outline-none focus:ring-2 focus:ring-[#5EEAD4] font-bold text-sm text-gray-700">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Dari Tanggal</label>
                    <input type="date" name="dari" value="{{ request('dari') }}"
                        class="w-full px-4 py-3 bg-gray-50 rounded-xl border-none outline-none focus:ring-2 focus:ring-[#5EEAD4] font-bold text-sm text-gray-700">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Sampai Tanggal</label>
                    <input type="date" name="sampai" value="{{ request('sampai') }}"
                        class="w-full px-4 py-3 bg-gray-50 rounded-xl border-none outline-none focus:ring-2 focus:ring-[#5EEAD4] font-bold text-sm text-gray-700">
                </div>
            </div>
            <div class="flex items-center gap-3 mt-4">
                <button type="submit"
                    class="px-6 py-2.5 bg-[#1E4D9C] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-[#5EEAD4] hover:text-[#1E4D9C] transition-all">
                    Filter
                </button>
                <a href="{{ route('admin.laporan.permintaan') }}"
                    class="px-6 py-2.5 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-all">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- TABEL --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                Total {{ $permintaans->total() }} permintaan
            </p>
        </div>

        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">No</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">PIC</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Commodity</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Tanggal</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Barang Diambil</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Remark</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permintaans as $p)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition-all">
                    <td class="px-6 py-4 text-sm text-gray-400 font-bold">{{ $permintaans->firstItem() + $loop->index }}</td>
                    <td class="px-6 py-4 text-sm font-black text-gray-800">{{ $p->pic }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 font-bold">{{ $p->commodity }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="space-y-1">
                            @foreach($p->details as $detail)
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-black text-gray-500">{{ $detail->barang->nama_barang ?? '-' }}</span>
                                <span class="px-2 py-0.5 bg-orange-50 text-orange-600 text-[9px] font-black rounded-full">x{{ $detail->qty }}</span>
                            </div>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-400">{{ $p->remark ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="openDetail({{ $p->id }})"
                            class="p-2 text-[#1E4D9C] hover:text-[#5EEAD4] transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </td>
                </tr>

                {{-- Detail Row (hidden by default) --}}
                <tr id="detail-{{ $p->id }}" class="hidden">
                    <td colspan="7" class="px-6 pb-4">
                        <div class="bg-gray-50 rounded-2xl overflow-hidden border border-gray-100">
                            {{-- Header Detail --}}
                            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 bg-[#1E4D9C] rounded-lg flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 text-[#5EEAD4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                            {{-- List Barang --}}
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

                            {{-- Remark --}}
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
                    <td colspan="7" class="px-6 py-16 text-center text-[10px] font-black text-gray-300 uppercase tracking-widest">
                        Belum ada data permintaan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($permintaans->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                {{ $permintaans->firstItem() }}–{{ $permintaans->lastItem() }} dari {{ $permintaans->total() }}
            </p>
            <div class="flex items-center gap-2">
                @if($permintaans->onFirstPage())
                <span class="px-3 py-1.5 text-[10px] font-black text-gray-300 cursor-not-allowed">← Prev</span>
                @else
                <a href="{{ $permintaans->previousPageUrl() }}" class="px-3 py-1.5 text-[10px] font-black text-[#1E4D9C] hover:text-[#5EEAD4] transition-all">← Prev</a>
                @endif
                @foreach($permintaans->getUrlRange(1, $permintaans->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="w-7 h-7 flex items-center justify-center rounded-lg text-[10px] font-black transition-all {{ $page == $permintaans->currentPage() ? 'bg-[#1E4D9C] text-white' : 'text-gray-400 hover:bg-gray-100' }}">{{ $page }}</a>
                @endforeach
                @if($permintaans->hasMorePages())
                <a href="{{ $permintaans->nextPageUrl() }}" class="px-3 py-1.5 text-[10px] font-black text-[#1E4D9C] hover:text-[#5EEAD4] transition-all">Next →</a>
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