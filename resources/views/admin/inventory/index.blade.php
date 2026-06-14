@extends('layouts.admin')

@section('title', 'Data Inventory')

@section('content')


<div class="space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm">
        <div>
            <h3 class="font-black text-gray-800 text-xl uppercase tracking-tight">Inventory Warehouse</h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Sistem Laporan & Monitoring</p>
        </div>

        <div class="flex bg-gray-50 p-1 rounded-full border border-gray-100">
            <a href="{{ request()->fullUrlWithQuery(['view' => 'table']) }}"
                class="px-8 py-2 text-[10px] font-black uppercase tracking-widest rounded-full transition {{ request('view', 'table') == 'table' ? 'bg-[#1E4D9C] text-white shadow-lg' : 'text-gray-400 hover:text-gray-600' }}">
                Tabel
            </a>
            <a href="{{ request()->fullUrlWithQuery(['view' => 'card']) }}"
                class="px-8 py-2 text-[10px] font-black uppercase tracking-widest rounded-full transition {{ request('view') == 'card' ? 'bg-[#1E4D9C] text-white shadow-lg' : 'text-gray-400 hover:text-gray-600' }}">
                Card
            </a>
        </div>
    </div>



    <form action="{{ route('admin.inventory.index') }}" method="GET" class="relative">
        <input type="hidden" name="view" value="{{ request('view', 'table') }}">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari kode atau nama barang..."
            class="w-full px-6 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-semibold">
        <button type="submit" class="absolute right-4 top-4 text-gray-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
    </form>



    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Aman</span>
            <span class="text-xl font-black text-emerald-600 mt-1">{{ $stats['aman'] }}</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Shortage</span>
            <span class="text-xl font-black text-amber-600 mt-1">{{ $stats['shortage'] }}</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Kosong</span>
            <span class="text-xl font-black text-red-600 mt-1">{{ $stats['kosong'] }}</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Over</span>
            <span class="text-xl font-black text-indigo-600 mt-1">{{ $stats['over'] }}</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Not Used</span>
            <span class="text-xl font-black text-gray-600 mt-1">{{ $stats['not_used'] }}</span>
        </div>
    </div>

    <form action="{{ route('admin.inventory.index') }}" method="GET" class="flex flex-wrap gap-2">
        <input type="hidden" name="view" value="{{ request('view', 'table') }}">
        <input type="hidden" name="search" value="{{ request('search') }}">

        @foreach([
        '' => 'Semua',
        'aman' => 'Aman',
        'shortage' => 'Shortage',
        'kosong' => 'Kosong',
        'over' => 'Over',
        'not_used' => 'Not Used',
        ] as $val => $label)
        <button type="submit" name="status" value="{{ $val }}"
            class="px-5 py-2 text-[10px] font-black uppercase tracking-widest rounded-full transition-all
        {{ request('status', '') === $val
            ? 'bg-[#1E4D9C] text-white shadow-lg'
            : 'bg-white text-gray-400 border border-gray-200 hover:border-[#1E4D9C] hover:text-[#1E4D9C]' }}">
            {{ $label }}
        </button>
        @endforeach
    </form>

    @if(request('view', 'table') == 'table')
    <div class="bg-white rounded-[2rem] border border-gray-100 p-8 shadow-sm overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-[10px] text-gray-400 uppercase tracking-widest font-black border-b border-gray-50">
                    <th class="p-4">No</th>
                    <th class="p-4">Kode</th>
                    <th class="p-4">Nama Barang</th>
                    <th class="p-4">alamat</th>
                    <th class="p-4">Satuan</th>
                    <th class="p-4 text-right">Min</th>
                    <th class="p-4 text-right">Max</th>
                    <th class="p-4 text-right">Stok</th>
                    <th class="p-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="text-xs font-semibold text-gray-600">
                @foreach($barangs as $index => $item)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                    <td class="p-4">{{ $barangs->firstItem() + $loop->index }}</td>
                    <td class="p-4 font-black text-gray-800">{{ $item->kode_barang }}</td>
                    <td class="p-4">{{ $item->nama_barang }}</td>
                    <td class="p-4">{{ $item->alamat }}</td>
                    <td class="p-4">{{ $item->satuan }}</td>
                    <td class="p-4 text-right">{{ $item->min }}</td>
                    <td class="p-4 text-right">{{ $item->max }}</td>
                    <td class="p-4 text-right font-black text-blue-600">{{ $item->qty }}</td>
                    <td class="p-4 text-center">
                        @include('layouts.partials.status_badge')
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination — di luar </table> --}}
        <div class="mt-6 flex items-center justify-between px-2">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                Menampilkan {{ $barangs->firstItem() }}–{{ $barangs->lastItem() }}
                dari {{ $barangs->total() }} barang
            </p>

            <div class="flex items-center gap-1">
                {{-- Prev --}}
                @if($barangs->onFirstPage())
                <span class="px-4 py-2 text-[10px] font-black text-gray-300 uppercase tracking-widest cursor-not-allowed">← Prev</span>
                @else
                <a href="{{ $barangs->previousPageUrl() }}"
                    class="px-4 py-2 text-[10px] font-black text-[#1E4D9C] uppercase tracking-widest hover:text-[#5EEAD4] transition-all">← Prev</a>
                @endif

                {{-- Nomor halaman dengan ellipsis --}}
                @php
                $current = $barangs->currentPage();
                $last = $barangs->lastPage();
                $pages = [];

                // Selalu tampilkan halaman 1
                $pages[] = 1;

                // Window 2 kiri & kanan dari current
                for ($i = max(2, $current - 2); $i <= min($last - 1, $current + 2); $i++) {
                    $pages[]=$i;
                    }

                    // Selalu tampilkan halaman terakhir
                    if ($last> 1) $pages[] = $last;

                    $pages = array_unique($pages);
                    sort($pages);
                    @endphp

                    @php $prev = null; @endphp
                    @foreach($pages as $page)
                    @if($prev !== null && $page - $prev > 1)
                    <span class="px-1 text-gray-300 font-black text-sm">…</span>
                    @endif

                    <a href="{{ $barangs->url($page) }}"
                        class="w-8 h-8 flex items-center justify-center rounded-xl text-[10px] font-black transition-all
                                   {{ $page == $current
                                       ? 'bg-[#1E4D9C] text-white shadow-lg'
                                       : 'text-gray-400 hover:bg-gray-100' }}">
                        {{ $page }}
                    </a>

                    @php $prev = $page; @endphp
                    @endforeach

                    {{-- Next --}}
                    @if($barangs->hasMorePages())
                    <a href="{{ $barangs->nextPageUrl() }}"
                        class="px-4 py-2 text-[10px] font-black text-[#1E4D9C] uppercase tracking-widest hover:text-[#5EEAD4] transition-all">Next →</a>
                    @else
                    <span class="px-4 py-2 text-[10px] font-black text-gray-300 uppercase tracking-widest cursor-not-allowed">Next →</span>
                    @endif
            </div>
        </div>

    </div> {{-- tutup div.bg-white --}}
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($barangs as $item)
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-lg transition flex flex-col overflow-hidden">

        <div class="h-40 bg-gray-50 flex items-center justify-center border-b border-gray-100">
            @if($item->image)
            <img src="{{ asset('storage/' . $item->image) }}"
                alt="{{ $item->nama_barang }}"
                class="h-full w-full object-cover">
            @else
            <div class="flex flex-col items-center justify-center gap-2">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 002-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">No Image</span>
            </div>
            @endif
        </div>

        <div class="p-6 flex flex-col justify-between flex-grow">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <span class="text-[10px] font-black text-blue-600 bg-blue-50 px-3 py-1 rounded-full">{{ $item->kode_barang }}</span>
                    @include('layouts.partials.status_badge')
                </div>
                <h4 class="font-black text-gray-800 text-sm mb-1">{{ $item->nama_barang }}</h4>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $item->satuan }}</p>
            </div>


        </div>
    </div>
    @endforeach
</div>
@endif
</div>
@endsection