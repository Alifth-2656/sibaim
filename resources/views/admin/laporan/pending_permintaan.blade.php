@extends('layouts.admin')

@section('title', 'Pending')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">

        {{-- HEADER --}}
        <div class="bg-[#1E4D9C] px-10 py-8 text-white flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-2xl font-black uppercase tracking-tight">Pending</h3>
                <p class="text-teal-200 text-[10px] mt-1 uppercase tracking-[0.2em] font-bold">
                    {{ $showAll ? 'Semua data' : 'Data tanggal ' . \Carbon\Carbon::parse($tanggal)->translatedFormat('d M Y') }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 bg-white/10 rounded-xl text-xs font-black text-white uppercase tracking-widest">
                    Permintaan Open: {{ $permintaans->total() }}
                </span>
                <span class="px-4 py-2 bg-white/10 rounded-xl text-xs font-black text-white uppercase tracking-widest">
                    Barang Pending: {{ $laporans->total() }}
                </span>
            </div>
        </div>

        {{-- TAB + FILTER --}}
        <div class="px-8 pt-5 pb-0 border-b border-gray-100 bg-gray-50/50">

            {{-- TABS --}}
            <div class="flex gap-2 mb-4">
                <a href="{{ route('admin.laporan.pending_permintaan', array_merge(request()->query(), ['tab' => 'permintaan'])) }}"
                    class="px-6 py-2 text-[10px] font-black uppercase tracking-widest rounded-t-xl transition-all
                    {{ $tab === 'permintaan' ? 'bg-[#1E4D9C] text-white' : 'bg-gray-200 text-gray-500 hover:bg-gray-300' }}">
                    Permintaan Barang
                </a>
                <a href="{{ route('admin.laporan.pending_permintaan', array_merge(request()->query(), ['tab' => 'stok_habis'])) }}"
                    class="px-6 py-2 text-[10px] font-black uppercase tracking-widest rounded-t-xl transition-all
                    {{ $tab === 'stok_habis' ? 'bg-[#1E4D9C] text-white' : 'bg-gray-200 text-gray-500 hover:bg-gray-300' }}">
                    Barang Tidak Ditemukan
                </a>
            </div>

            {{-- FILTER --}}
            <form method="GET" action="{{ route('admin.laporan.pending_permintaan') }}" class="flex flex-wrap items-end gap-3 pb-4">
                <input type="hidden" name="tab" value="{{ $tab }}">

                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tanggal</label>
                    <input
                        type="date"
                        name="tanggal"
                        value="{{ $tanggal }}"
                        {{ $showAll ? 'disabled' : '' }}
                        class="px-4 py-2 rounded-xl border border-gray-200 text-sm text-gray-700 font-semibold focus:outline-none focus:ring-2 focus:ring-[#1E4D9C]/30 disabled:opacity-40 disabled:bg-gray-100"
                    />
                </div>

                <button type="submit"
                    class="px-5 py-2 bg-[#1E4D9C] text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:opacity-80 transition-all">
                    Filter
                </button>

                @if($showAll)
                    <a href="{{ route('admin.laporan.pending_permintaan', ['tab' => $tab]) }}"
                        class="px-5 py-2 bg-amber-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:opacity-80 transition-all">
                        Hari Ini Saja
                    </a>
                @else
                    <a href="{{ route('admin.laporan.pending_permintaan', ['tab' => $tab, 'show_all' => 1]) }}"
                        class="px-5 py-2 bg-gray-200 text-gray-600 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-gray-300 transition-all">
                        Tampilkan Semua
                    </a>
                @endif
            </form>
        </div>

        {{-- ALERT --}}
        @if(session('success'))
            <div class="mx-8 mt-4 px-5 py-3 bg-green-50 border border-green-200 text-green-700 text-xs font-bold rounded-xl">
                ✓ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-8 mt-4 px-5 py-3 bg-red-50 border border-red-200 text-red-600 text-xs font-bold rounded-xl">
                ✗ {{ session('error') }}
            </div>
        @endif

        {{-- =============================== --}}
        {{-- TAB: PERMINTAAN BARANG          --}}
        {{-- =============================== --}}
        @if($tab === 'permintaan')
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">PIC</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Commodity</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Barang Diminta</th>
                        <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permintaans as $item)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-all {{ $item->status === 'close' ? 'opacity-70' : '' }}">
                        <td class="px-6 py-4 font-bold text-gray-800">{{ $item->pic }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $item->commodity }}</td>
                        <td class="px-6 py-4 text-gray-600">
                            @foreach($item->details as $detail)
                                <div class="text-xs">
                                    <span class="font-bold text-gray-700">{{ $detail->barang->nama_barang ?? '-' }}</span>
                                    <span class="text-gray-400">({{ $detail->qty }} {{ $detail->barang->satuan ?? '' }})</span>
                                </div>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 text-center text-gray-400 text-xs">{{ $item->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($item->status === 'open')
                                <span class="px-3 py-1 bg-amber-100 text-amber-600 text-[10px] font-black uppercase tracking-widest rounded-full">Open</span>
                            @else
                                <span class="px-3 py-1 bg-green-100 text-green-600 text-[10px] font-black uppercase tracking-widest rounded-full">Close</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->status === 'open')
                                <form method="POST" action="{{ route('admin.laporan.permintaan.konfirmasi', $item->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="px-4 py-2 bg-[#1E4D9C] text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:opacity-80 transition-all">
                                        Konfirmasi
                                    </button>
                                </form>
                            @else
                                <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-[10px] font-black text-gray-300 uppercase tracking-widest">
                            Tidak ada permintaan{{ $showAll ? '' : ' pada tanggal ' . \Carbon\Carbon::parse($tanggal)->translatedFormat('d M Y') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">
            {{ $permintaans->links() }}
        </div>

        {{-- =============================== --}}
        {{-- TAB: BARANG TIDAK DITEMUKAN     --}}
        {{-- =============================== --}}
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Barang</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">PIC</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Commodity</th>
                        <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Qty Diminta</th>
                        <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporans as $item)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-all {{ $item->status === 'ditangani' ? 'opacity-70' : '' }}">
                        <td class="px-6 py-4 font-bold text-gray-800">
                            {{ $item->barang->nama_barang ?? '-' }}
                            <span class="block text-[10px] text-gray-400 font-bold">{{ $item->barang->kode_barang ?? '' }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $item->pic }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $item->commodity }}</td>
                        <td class="px-6 py-4 text-center font-black text-red-500">{{ $item->qty_diminta }}</td>
                        <td class="px-6 py-4 text-center text-gray-400 text-xs">{{ $item->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($item->status === 'pending')
                                <span class="px-3 py-1 bg-amber-100 text-amber-600 text-[10px] font-black uppercase tracking-widest rounded-full">Pending</span>
                            @else
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-full">Ditangani</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->status === 'pending')
                                <form method="POST" action="{{ route('admin.laporan.stok_habis.tangani', $item->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="px-4 py-2 bg-[#1E4D9C] text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:opacity-80 transition-all">
                                        Tandai Ditangani
                                    </button>
                                </form>
                            @else
                                <span class="text-[10px] text-gray-300 font-bold uppercase">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-[10px] font-black text-gray-300 uppercase tracking-widest">
                            Tidak ada laporan{{ $showAll ? '' : ' pada tanggal ' . \Carbon\Carbon::parse($tanggal)->translatedFormat('d M Y') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">
            {{ $laporans->links() }}
        </div>
        @endif

    </div>
</div>
@endsection