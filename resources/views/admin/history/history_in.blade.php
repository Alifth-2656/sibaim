@extends('layouts.admin')

@section('title', 'Riwayat Barang Masuk')

@section('content')
<div class="max-w-6xl mx-auto p-4 md:p-8">

    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6">
            <div>
                <h3 class="text-2xl font-black text-gray-800 tracking-tight">Riwayat Barang Masuk</h3>
                <p class="text-sm text-gray-400 font-medium mt-1">Daftar lengkap transaksi masuk ke gudang</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.history.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold transition flex items-center gap-2 text-sm">
                    &larr; Kembali
                </a>
                <a href="{{ route('admin.history.in.export', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                    class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold transition flex items-center gap-2 text-sm shadow-md shadow-emerald-200">
                    <span>Export CSV</span>
                </a>
            </div>
        </div>

        <form action="{{ route('admin.history.in.index') }}" method="GET" class="bg-gray-50 p-6 rounded-2xl mb-8 flex flex-wrap items-end gap-6 border border-gray-100">
            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
            </div>
            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2.5 bg-gray-800 hover:bg-black text-white rounded-xl font-bold text-sm transition">
                    Filter
                </button>
                <a href="{{ route('admin.history.in.index') }}" class="px-4 py-2.5 text-gray-400 hover:text-gray-600 rounded-xl font-bold text-sm flex items-center">
                    Reset
                </a>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 text-[10px] uppercase tracking-widest">
                        <th class="px-6 py-4 font-black">No</th>
                        <th class="px-6 py-4 font-black">PIC</th>
                        <th class="px-6 py-4 font-black">Nama Barang</th>
                        <th class="px-6 py-4 font-black">Satuan</th>
                        <th class="px-6 py-4 font-black text-right">QTY</th>
                        <th class="px-6 py-4 font-black text-right">Mark</th>
                        <th class="px-6 py-4 font-black">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($data as $item)
                    <tr class="hover:bg-emerald-50/50 transition duration-150">
                        <td class="px-6 py-5 text-sm text-gray-500 font-medium">{{ $loop->iteration }}</td>
                        <td class="px-6 py-5 text-sm text-gray-800 font-bold">{{ $item->pic }}</td>
                        <td class="px-6 py-5 text-sm text-gray-800 font-bold">{{ $item->barang->nama_barang ?? 'N/A' }}</td>
                        <td class="px-6 py-5 text-sm text-gray-600">{{ $item->barang->satuan ?? '-' }}</td>
                        <td class="px-6 py-5 text-sm text-right font-black text-emerald-600">{{ $item->qty }}</td>
                        <td class="px-6 py-5 text-sm text-right font-black text-emerald-600">{{ $item->keterangan }}</td>
                        <td class="px-6 py-5 text-sm text-gray-500 font-medium">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 font-bold">Data tidak ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection