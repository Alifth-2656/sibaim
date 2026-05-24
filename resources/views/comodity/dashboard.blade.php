@extends('layouts.comodity')

@section('title', 'Dashboard Overview')
@section('subtitle', 'Pantau aktivitas gudang secara real-time')

@section('content')

<!-- CARD -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

    <div class="bg-white p-6 rounded-2xl shadow-sm border">
        <p class="text-sm text-gray-500 uppercase">Jenis Barang</p>
        <h3 class="text-3xl font-bold text-[#1E4D9C] mt-1">
            {{ $totalJenis }}
        </h3>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border">
        <p class="text-sm text-gray-500 uppercase">Total Stok</p>
        <h3 class="text-3xl font-bold text-green-600 mt-1">
            {{ $totalStok }}
        </h3>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border">
        <p class="text-sm text-gray-500 uppercase">Stok Menipis</p>
        <h3 class="text-3xl font-bold text-orange-500 mt-1">
            {{ $stokMenipis }}
        </h3>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border">
        <p class="text-sm text-gray-500 uppercase">Permintaan Terakhir</p>
        <h3 class="text-3xl font-bold text-red-500 mt-1">
            {{ $permintaanTerakhir->count() }}
        </h3>
    </div>

</div>

<!-- TABLE -->
<div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

    <div class="px-6 py-4 border-b bg-gray-50">
        <h3 class="font-bold text-gray-800">Riwayat Permintaan Terakhir</h3>
    </div>

    <div class="p-6">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="text-gray-400 border-b">
                    <th class="pb-3">PIC</th>
                    <th class="pb-3">COMODITY</th>
                    <th class="pb-3">Tanggal</th>
                    <th class="pb-3">Status</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($permintaanTerakhir as $item)
                <tr>
                    <td class="py-4 font-semibold text-gray-700">
                        {{ $item->pic }}
                    </td>

                    <td class="py-4 text-gray-600">
                        {{ $item->commodity }}
                    </td>

                    <td class="py-4 text-gray-500 italic">
                        {{ $item->created_at->format('d M Y H:i') }}
                    </td>

                    <td class="py-4">
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                            SUCCESS
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-6 text-gray-400">
                        Belum ada data permintaan
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>

@endsection
