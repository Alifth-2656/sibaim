@extends('layouts.admin')

@section('title', 'Kelola Barang')
@section('subtitle', 'Manajemen operasional barang & gudang')

@section('content')
<div class="space-y-10">

    {{-- MENU CARDS — lg:grid-cols-5 agar STO masuk grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">

        <!-- TAMBAH BARANG -->
        <a href="{{ route('admin.kelola_barang.create') }}"
            class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-blue-100/50 hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex flex-col gap-4">
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:bg-[#1E4D9C] group-hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-black text-gray-800 uppercase tracking-tight text-sm">Tambah Barang</h3>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-widest">Registrasi Item Baru</p>
                </div>
            </div>
        </a>

        <!-- TAMBAH STOCK -->
        <a href="{{ route('admin.kelola_barang.stok') }}"
            class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-teal-100/50 hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex flex-col gap-4">
                <div class="w-12 h-12 bg-teal-50 rounded-2xl flex items-center justify-center text-teal-600 group-hover:bg-[#5EEAD4] group-hover:text-[#1E4D9C] transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-black text-gray-800 uppercase tracking-tight text-sm">Update Stock</h3>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-widest">Multi Input & Scan</p>
                </div>
            </div>
        </a>

        <!-- PINDAH RAK -->
        <a href="{{ route('admin.kelola_barang.pindah') }}"
            class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-yellow-100/50 hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex flex-col gap-4">
                <div class="w-12 h-12 bg-yellow-50 rounded-2xl flex items-center justify-center text-yellow-600 group-hover:bg-yellow-400 group-hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-black text-gray-800 uppercase tracking-tight text-sm">Pindah Rak</h3>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-widest">Relokasi Inventory</p>
                </div>
            </div>
        </a>

        <!-- BARANG KELUAR -->
        <a href="{{ route('admin.kelola_barang.keluar') }}"
            class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-red-100/50 hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex flex-col gap-4">
                <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-600 group-hover:bg-red-500 group-hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H3m12 0l-4-4m4 4l-4 4m5-10a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-black text-gray-800 uppercase tracking-tight text-sm">Barang Keluar</h3>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-widest">Checkout Barang</p>
                </div>
            </div>
        </a>

        <!-- STO — sekarang masuk grid -->
        <a href="{{ route('admin.kelola_barang.sto') }}"
            class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-purple-100/50 hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex flex-col gap-4">
                <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 group-hover:bg-purple-500 group-hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-black text-gray-800 uppercase tracking-tight text-sm">Stock Take Over</h3>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-widest">Cek Fisik vs Sistem</p>
                </div>
            </div>
        </a>
    </div>

    {{-- REKAP --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Aktivitas Terbaru dari DB --}}
        <div class="lg:col-span-2 bg-white rounded-[2rem] border border-gray-100 p-8">
            <div class="flex items-center justify-between mb-6">
                <h4 class="font-black text-gray-800 uppercase tracking-widest text-xs">Aktivitas Terakhir</h4>
                <a href="{{ route('admin.history.index') }}" class="text-[10px] font-bold text-blue-500 uppercase tracking-widest hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-3">
                @forelse($aktivitas as $item)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                    <div class="flex items-center gap-4">
                        <div class="w-2 h-2 rounded-full {{ $item['tipe'] === 'in' ? 'bg-green-500' : 'bg-red-500' }}"></div>
                        <div>
                            <p class="text-xs font-bold text-gray-700">
                                {{ $item['tipe'] === 'in' ? 'Stock In' : 'Stock Out' }}: {{ $item['kode'] }} ({{ $item['nama'] }})
                            </p>
                            <p class="text-[10px] text-gray-400 font-medium">
                                Oleh: {{ $item['pic'] }} — {{ \Carbon\Carbon::parse($item['created_at'])->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <span class="text-xs font-black {{ $item['tipe'] === 'in' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $item['tipe'] === 'in' ? '+' : '-' }}{{ $item['qty'] }}
                    </span>
                </div>
                @empty
                <div class="py-10 text-center text-[10px] font-black text-gray-200 uppercase tracking-widest">
                    Belum ada aktivitas
                </div>
                @endforelse
            </div>
            {{-- Pagination Aktivitas --}}
            @if($aktivitas->hasPages())
            <div class="mt-4 flex items-center justify-between">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    {{ $aktivitas->firstItem() }}–{{ $aktivitas->lastItem() }} dari {{ $aktivitas->total() }}
                </p>
                <div class="flex items-center gap-2">
                    @if($aktivitas->onFirstPage())
                    <span class="px-3 py-1.5 text-[10px] font-black text-gray-300 uppercase tracking-widest cursor-not-allowed">← Prev</span>
                    @else
                    <a href="{{ $aktivitas->previousPageUrl() }}"
                        class="px-3 py-1.5 text-[10px] font-black text-[#1E4D9C] uppercase tracking-widest hover:text-[#5EEAD4] transition-all">← Prev</a>
                    @endif

                    @foreach($aktivitas->getUrlRange(1, $aktivitas->lastPage()) as $page => $url)
                    <a href="{{ $url }}"
                        class="w-7 h-7 flex items-center justify-center rounded-lg text-[10px] font-black transition-all
                {{ $page == $aktivitas->currentPage() ? 'bg-[#1E4D9C] text-white' : 'text-gray-400 hover:bg-gray-100' }}">
                        {{ $page }}
                    </a>
                    @endforeach

                    @if($aktivitas->hasMorePages())
                    <a href="{{ $aktivitas->nextPageUrl() }}"
                        class="px-3 py-1.5 text-[10px] font-black text-[#1E4D9C] uppercase tracking-widest hover:text-[#5EEAD4] transition-all">Next →</a>
                    @else
                    <span class="px-3 py-1.5 text-[10px] font-black text-gray-300 uppercase tracking-widest cursor-not-allowed">Next →</span>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Statistik --}}
        <div class="bg-[#1E4D9C] rounded-[2rem] p-8 text-white">
            <h4 class="font-black text-blue-200 uppercase tracking-widest text-[10px] mb-6">Status Gudang</h4>
            <div class="space-y-6">
                <div>
                    <p class="text-2xl font-black">{{ \App\Models\Barang::sum('qty') }}</p>
                    <p class="text-[10px] text-blue-300 uppercase tracking-widest font-bold">Total Item Tersedia</p>
                </div>
                <div class="pt-6 border-t border-white/10">
                    <p class="text-2xl font-black text-[#5EEAD4]">{{ $lowStocks->count() }}</p>
                    <p class="text-[10px] text-blue-300 uppercase tracking-widest font-bold">Item Low Stock</p>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL LOW STOCK --}}
    <div class="bg-white rounded-[2rem] border border-gray-100 p-8 shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h4 class="font-black text-gray-800 uppercase tracking-widest text-sm">Barang Dibawah Minimum</h4>
                <p class="text-[10px] text-red-400 font-black uppercase tracking-widest mt-1">⚠ Perlu restock segera</p>
            </div>
            <a href="{{ route('admin.inventory.index') }}" class="text-xs font-bold text-blue-600 uppercase tracking-widest hover:underline">Lihat Semua</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-[10px] text-gray-400 uppercase tracking-widest font-bold border-b border-gray-100">
                        <th class="pb-4">Kode Barang</th>
                        <th class="pb-4">Nama Barang</th>
                        <th class="pb-4">Lokasi</th>
                        <th class="pb-4 text-center">Stok</th>
                        <th class="pb-4 text-center">Min</th>
                        <th class="pb-4 text-center">Selisih</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($lowStocks as $barang)
                    <tr class="hover:bg-red-50/30 transition-all">
                        <td class="py-4 font-black text-gray-700">{{ $barang->kode_barang }}</td>
                        <td class="py-4 text-gray-600 font-bold">{{ $barang->nama_barang }}</td>
                        <td class="py-4 text-gray-400 text-xs font-bold">{{ $barang->alamat ?? '-' }}</td>
                        <td class="py-4 text-center font-black text-red-500">{{ $barang->qty }}</td>
                        <td class="py-4 text-center font-black text-gray-400">{{ $barang->min }}</td>
                        <td class="py-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-600 text-[10px] font-black rounded-full">
                                -{{ $barang->min - $barang->qty }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center text-[10px] font-black text-gray-200 uppercase tracking-widest">
                            ✓ Semua stok aman
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{-- Pagination Low Stock --}}
            @if($lowStocks->hasPages())
            <div class="mt-6 flex items-center justify-between">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    {{ $lowStocks->firstItem() }}–{{ $lowStocks->lastItem() }} dari {{ $lowStocks->total() }}
                </p>
                <div class="flex items-center gap-2">
                    @if($lowStocks->onFirstPage())
                    <span class="px-3 py-1.5 text-[10px] font-black text-gray-300 uppercase tracking-widest cursor-not-allowed">← Prev</span>
                    @else
                    <a href="{{ $lowStocks->previousPageUrl() }}"
                        class="px-3 py-1.5 text-[10px] font-black text-[#1E4D9C] uppercase tracking-widest hover:text-[#5EEAD4] transition-all">← Prev</a>
                    @endif

                    @foreach($lowStocks->getUrlRange(1, $lowStocks->lastPage()) as $page => $url)
                    <a href="{{ $url }}"
                        class="w-7 h-7 flex items-center justify-center rounded-lg text-[10px] font-black transition-all
                {{ $page == $lowStocks->currentPage() ? 'bg-[#1E4D9C] text-white' : 'text-gray-400 hover:bg-gray-100' }}">
                        {{ $page }}
                    </a>
                    @endforeach

                    @if($lowStocks->hasMorePages())
                    <a href="{{ $lowStocks->nextPageUrl() }}"
                        class="px-3 py-1.5 text-[10px] font-black text-[#1E4D9C] uppercase tracking-widest hover:text-[#5EEAD4] transition-all">Next →</a>
                    @else
                    <span class="px-3 py-1.5 text-[10px] font-black text-gray-300 uppercase tracking-widest cursor-not-allowed">Next →</span>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection