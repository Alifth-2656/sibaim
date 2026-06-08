@extends('layouts.admin')

@section('title', 'Check Daily')
@section('subtitle', 'Rekap aktivitas harian gudang')

@section('content')
<div class="space-y-6">

    {{-- HEADER + FILTER TANGGAL --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="bg-[#1E4D9C] px-8 py-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-xl font-black text-white uppercase tracking-tight">📋 Check Daily</h3>
                <p class="text-blue-200 text-[10px] mt-1 uppercase tracking-[0.2em] font-bold">
                    {{ $tanggal->translatedFormat('l, d F Y') }}
                </p>
            </div>
            <form method="GET" action="{{ route('admin.laporan.check_daily') }}" class="flex items-center gap-3">
                <input
                    type="date"
                    name="tanggal"
                    value="{{ $tanggal->format('Y-m-d') }}"
                    class="px-4 py-2 rounded-xl text-sm font-bold text-gray-700 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#5EEAD4]"
                />
                <button type="submit"
                    class="px-5 py-2 bg-[#5EEAD4] text-[#1E4D9C] text-xs font-black uppercase tracking-widest rounded-xl hover:bg-teal-300 transition-all">
                    Cek
                </button>
                @if($tanggal->toDateString() !== now()->toDateString())
                <a href="{{ route('admin.laporan.check_daily') }}"
                    class="px-4 py-2 bg-white/10 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-white/20 transition-all">
                    Hari Ini
                </a>
                @endif
            </form>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Masuk</p>
            <p class="text-3xl font-black text-teal-500">{{ $summary['total_masuk'] }}</p>
            <p class="text-[9px] text-gray-400 mt-1">{{ $summary['transaksi_masuk'] }} transaksi</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Keluar</p>
            <p class="text-3xl font-black text-orange-500">{{ $summary['total_keluar'] }}</p>
            <p class="text-[9px] text-gray-400 mt-1">{{ $summary['transaksi_keluar'] }} transaksi</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Pindah Barang</p>
            <p class="text-3xl font-black text-blue-500">{{ $summary['total_pindah'] }}</p>
            <p class="text-[9px] text-gray-400 mt-1">aktivitas move</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Net Perubahan</p>
            @php $net = $summary['total_masuk'] - $summary['total_keluar']; @endphp
            <p class="text-3xl font-black {{ $net >= 0 ? 'text-green-500' : 'text-red-500' }}">
                {{ $net >= 0 ? '+' : '' }}{{ $net }}
            </p>
            <p class="text-[9px] text-gray-400 mt-1">unit hari ini</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Stok Kritis</p>
            <p class="text-3xl font-black {{ $summary['stok_kritis'] > 0 ? 'text-red-500' : 'text-green-500' }}">
                {{ $summary['stok_kritis'] }}
            </p>
            <p class="text-[9px] text-gray-400 mt-1">item perlu restock</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Aktivitas Total</p>
            <p class="text-3xl font-black text-[#1E4D9C]">
                {{ $summary['transaksi_masuk'] + $summary['transaksi_keluar'] + $summary['total_pindah'] }}
            </p>
            <p class="text-[9px] text-gray-400 mt-1">semua transaksi</p>
        </div>
    </div>

    {{-- MASUK & KELUAR SIDE BY SIDE --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- BARANG MASUK --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center text-base">⬇️</span>
                    <p class="text-[10px] font-black text-gray-700 uppercase tracking-widest">Barang Masuk</p>
                </div>
                <span class="px-3 py-1 bg-teal-50 text-teal-600 text-[10px] font-black rounded-lg">
                    {{ $detailMasuk->count() }} transaksi
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Barang</th>
                            <th class="px-4 py-3 text-center text-[9px] font-black text-gray-400 uppercase tracking-widest">Qty</th>
                            <th class="px-4 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">PIC</th>
                            <th class="px-4 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Jam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detailMasuk as $item)
                        <tr class="border-b border-gray-50 hover:bg-teal-50/30 transition-all">
                            <td class="px-4 py-3">
                                <p class="text-xs font-black text-gray-800">{{ $item->barang->nama_barang ?? '-' }}</p>
                                <p class="text-[9px] text-gray-400 font-bold">{{ $item->barang->kode_barang ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 bg-teal-100 text-teal-700 text-xs font-black rounded-lg">
                                    +{{ $item->qty }} {{ $item->barang->satuan ?? '' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs font-bold text-gray-600">{{ $item->pic }}</td>
                            <td class="px-4 py-3 text-[10px] text-gray-400">
                                {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-[10px] font-black text-gray-300 uppercase tracking-widest">
                                Tidak ada barang masuk hari ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- BARANG KELUAR --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center text-base">⬆️</span>
                    <p class="text-[10px] font-black text-gray-700 uppercase tracking-widest">Barang Keluar</p>
                </div>
                <span class="px-3 py-1 bg-orange-50 text-orange-600 text-[10px] font-black rounded-lg">
                    {{ $detailKeluar->count() }} transaksi
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Barang</th>
                            <th class="px-4 py-3 text-center text-[9px] font-black text-gray-400 uppercase tracking-widest">Qty</th>
                            <th class="px-4 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">PIC</th>
                            <th class="px-4 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Jam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detailKeluar as $item)
                        <tr class="border-b border-gray-50 hover:bg-orange-50/30 transition-all">
                            <td class="px-4 py-3">
                                <p class="text-xs font-black text-gray-800">{{ $item->barang->nama_barang ?? '-' }}</p>
                                <p class="text-[9px] text-gray-400 font-bold">{{ $item->barang->kode_barang ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs font-black rounded-lg">
                                    -{{ $item->qty }} {{ $item->barang->satuan ?? '' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs font-bold text-gray-600">{{ $item->pic }}</td>
                            <td class="px-4 py-3 text-[10px] text-gray-400">
                                {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-[10px] font-black text-gray-300 uppercase tracking-widest">
                                Tidak ada barang keluar hari ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- PINDAH BARANG --}}
    @if($detailPindah->count() > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-base">🔄</span>
                <p class="text-[10px] font-black text-gray-700 uppercase tracking-widest">Pindah Barang</p>
            </div>
            <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black rounded-lg">
                {{ $detailPindah->count() }} aktivitas
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Barang</th>
                        <th class="px-6 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Dari</th>
                        <th class="px-6 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Ke</th>
                        <th class="px-6 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">PIC</th>
                        <th class="px-6 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Jam</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detailPindah as $item)
                    <tr class="border-b border-gray-50 hover:bg-blue-50/20 transition-all">
                        <td class="px-6 py-3">
                            <p class="text-xs font-black text-gray-800">{{ $item->barang->nama_barang ?? '-' }}</p>
                            <p class="text-[9px] text-gray-400 font-bold">{{ $item->barang->kode_barang ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-3 text-xs font-bold text-gray-600">{{ $item->from }}</td>
                        <td class="px-6 py-3 text-xs font-bold text-gray-600">{{ $item->to }}</td>
                        <td class="px-6 py-3 text-xs font-bold text-gray-600">{{ $item->pic }}</td>
                        <td class="px-6 py-3 text-[10px] text-gray-400">
                            {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- STOK KRITIS --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-base">⚠️</span>
                <p class="text-[10px] font-black text-gray-700 uppercase tracking-widest">Status Stok Kritis (Saat Ini)</p>
            </div>
            @if($stokKritis->count() > 0)
            <span class="px-3 py-1 bg-red-50 text-red-600 text-[10px] font-black rounded-lg">
                {{ $stokKritis->count() }} item perlu restock
            </span>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Barang</th>
                        <th class="px-6 py-3 text-center text-[9px] font-black text-gray-400 uppercase tracking-widest">Stok</th>
                        <th class="px-6 py-3 text-center text-[9px] font-black text-gray-400 uppercase tracking-widest">Min</th>
                        <th class="px-6 py-3 text-center text-[9px] font-black text-gray-400 uppercase tracking-widest">Selisih</th>
                        <th class="px-6 py-3 text-center text-[9px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stokKritis as $barang)
                    <tr class="border-b border-gray-50 hover:bg-red-50/20 transition-all">
                        <td class="px-6 py-3">
                            <p class="text-xs font-black text-gray-800">{{ $barang->nama_barang }}</p>
                            <p class="text-[9px] text-gray-400 font-bold">{{ $barang->kode_barang }}</p>
                        </td>
                        <td class="px-6 py-3 text-center font-black text-red-500">{{ $barang->qty }}</td>
                        <td class="px-6 py-3 text-center font-black text-gray-400">{{ $barang->min }}</td>
                        <td class="px-6 py-3 text-center font-black text-red-400">
                            {{ $barang->qty - $barang->min }}
                        </td>
                        <td class="px-6 py-3 text-center">
                            @if($barang->qty == 0)
                                <span class="px-2 py-1 bg-red-100 text-red-700 text-[9px] font-black rounded-lg uppercase">Habis</span>
                            @else
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-[9px] font-black rounded-lg uppercase">Menipis</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-[10px] font-black text-green-400 uppercase tracking-widest">
                            ✓ Semua stok dalam kondisi aman
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection