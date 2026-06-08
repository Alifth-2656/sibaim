@extends('layouts.admin')

@section('title', 'Barang Tidak Ditemukan')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">

        <div class="bg-[#1E4D9C] px-10 py-8 text-white flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-2xl font-black uppercase tracking-tight">Barang Tidak Ditemukan</h3>
                <p class="text-teal-200 text-[10px] mt-1 uppercase tracking-[0.2em] font-bold">Barang yang tidak ditemukan di gudang saat pengambilan</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 bg-white/10 rounded-xl text-xs font-black text-white uppercase tracking-widest">
                    Pending: {{ $laporan->where('status', 'pending')->count() }}
                </span>
            </div>
        </div>

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
                    @forelse($laporan as $item)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-all">
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
                            Tidak ada laporan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4">
            {{ $laporan->links() }}
        </div>

    </div>
</div>
@endsection