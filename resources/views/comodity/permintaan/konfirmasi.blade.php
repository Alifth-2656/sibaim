@extends('layouts.comodity')

@section('title', 'Konfirmasi Permintaan')
@section('subtitle', 'Langkah 3: Konfirmasi Pengambilan Barang')

@section('content')
<div class="max-w-2xl mx-auto pb-20">

    {{-- Warning barang stok habis dari step sebelumnya --}}
    @if(session('warning_habis'))
    <div class="mb-6 bg-amber-50 border border-amber-200 rounded-2xl px-6 py-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <p class="text-sm font-bold text-amber-700">{{ session('warning_habis') }}</p>
    </div>
    @endif

    <form method="POST" action="{{ route('comodity.permintaan.store') }}" id="konfirmasiForm">
        @csrf

        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">

            <div class="bg-[#1E4D9C] px-8 py-7 text-white">
                <h3 class="text-xl font-black uppercase tracking-tight">Konfirmasi Permintaan</h3>
                <p class="text-teal-200 text-[10px] mt-1 uppercase tracking-[0.2em] font-bold">Tandai barang yang tidak ditemukan di gudang</p>
            </div>

            <div class="px-8 py-6 space-y-5">

                {{-- Info PIC & Commodity --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-2xl px-5 py-4">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">PIC</p>
                        <p class="text-sm font-black text-gray-800 mt-1">{{ $pic }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-2xl px-5 py-4">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Commodity</p>
                        <p class="text-sm font-black text-gray-800 mt-1">{{ $commodity }}</p>
                    </div>
                </div>

                {{-- Legend --}}
                <div class="flex items-center gap-6 text-xs text-gray-500 font-semibold px-1">
                    <span class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-400 inline-block"></span> Diambil (default)
                    </span>
                    <span class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-400 inline-block"></span> Tidak ditemukan di gudang
                    </span>
                </div>

                {{-- Daftar item dengan toggle --}}
                <div class="space-y-3">
                    @foreach($items as $item)
                    <div class="item-row rounded-2xl border border-gray-100 px-5 py-4 transition-all duration-200"
                         id="row-{{ $item['barang_id'] }}" data-id="{{ $item['barang_id'] }}">

                        {{-- Hidden input status, default diambil --}}
                        <input type="hidden"
                               name="item_status[{{ $item['barang_id'] }}]"
                               id="status-{{ $item['barang_id'] }}"
                               value="diambil">

                        <div class="flex items-center justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <p class="font-black text-gray-800 text-sm truncate">{{ $item['nama_barang'] }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Kode: {{ $item['kode_barang'] }}
                                    &nbsp;·&nbsp;
                                    Qty diminta: <span class="font-black text-[#1E4D9C]">{{ $item['qty_diminta'] }}</span>
                                    &nbsp;·&nbsp;
                                    Stok sistem: {{ $item['qty_sistem'] }} {{ $item['satuan'] }}
                                </p>
                            </div>

                            {{-- Toggle button --}}
                            <button type="button"
                                    onclick="toggleStatus({{ $item['barang_id'] }})"
                                    id="btn-{{ $item['barang_id'] }}"
                                    class="flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all duration-200 status-btn-diambil">
                                <svg id="icon-check-{{ $item['barang_id'] }}" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <svg id="icon-x-{{ $item['barang_id'] }}" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span id="label-{{ $item['barang_id'] }}">Diambil</span>
                            </button>
                        </div>

                    </div>
                    @endforeach
                </div>

                {{-- Info penting --}}
                <div class="bg-blue-50 border border-blue-100 rounded-2xl px-5 py-4 flex items-start gap-3">
                    <svg class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xs text-blue-600 font-bold">
                        Stok akan berkurang untuk barang yang <span class="text-blue-800">Diambil</span>.
                        Barang yang <span class="text-red-600">Tidak Ditemukan</span> akan dilaporkan ke admin tanpa mengurangi stok.
                    </p>
                </div>

                {{-- Summary counter --}}
                <div class="flex gap-4">
                    <div class="flex-1 bg-emerald-50 border border-emerald-100 rounded-2xl px-4 py-3 text-center">
                        <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Diambil</p>
                        <p class="text-2xl font-black text-emerald-600 mt-0.5" id="counter-diambil">{{ count($items) }}</p>
                    </div>
                    <div class="flex-1 bg-red-50 border border-red-100 rounded-2xl px-4 py-3 text-center">
                        <p class="text-[10px] font-black text-red-400 uppercase tracking-widest">Tidak Ditemukan</p>
                        <p class="text-2xl font-black text-red-500 mt-0.5" id="counter-tidak">0</p>
                    </div>
                </div>

                {{-- Tombol aksi --}}
                <div class="flex gap-4 pt-2">
                    <button type="submit"
                        class="flex-1 py-4 bg-[#5EEAD4] text-[#1E4D9C] font-black text-sm uppercase tracking-widest rounded-2xl hover:opacity-80 transition-all">
                        ✓ Konfirmasi Pengambilan
                    </button>
                    <a href="{{ route('comodity.permintaan.pilih', ['pic' => $pic, 'commodity' => $commodity]) }}"
                        class="px-6 py-4 bg-gray-100 text-gray-500 font-black text-sm uppercase tracking-widest rounded-2xl hover:bg-gray-200 transition-all flex items-center">
                        Kembali
                    </a>
                </div>

            </div>
        </div>
    </form>
</div>

<style>
.status-btn-diambil {
    background-color: #d1fae5;
    color: #065f46;
}
.status-btn-tidak {
    background-color: #fee2e2;
    color: #991b1b;
}
.row-tidak {
    background-color: #fff5f5;
    border-color: #fecaca !important;
    opacity: 0.75;
}
</style>

<script>
    const totalItems = {{ count($items) }};
    let tidakCount = 0;

    function toggleStatus(id) {
        const input  = document.getElementById('status-' + id);
        const btn    = document.getElementById('btn-' + id);
        const row    = document.getElementById('row-' + id);
        const label  = document.getElementById('label-' + id);
        const iconOk = document.getElementById('icon-check-' + id);
        const iconX  = document.getElementById('icon-x-' + id);

        if (input.value === 'diambil') {
            // Toggle ke tidak_ditemukan
            input.value = 'tidak_ditemukan';
            btn.className = btn.className.replace('status-btn-diambil', 'status-btn-tidak');
            row.classList.add('row-tidak');
            label.textContent = 'Tidak Ditemukan';
            iconOk.classList.add('hidden');
            iconX.classList.remove('hidden');
            tidakCount++;
        } else {
            // Toggle balik ke diambil
            input.value = 'diambil';
            btn.className = btn.className.replace('status-btn-tidak', 'status-btn-diambil');
            row.classList.remove('row-tidak');
            label.textContent = 'Diambil';
            iconOk.classList.remove('hidden');
            iconX.classList.add('hidden');
            tidakCount--;
        }

        document.getElementById('counter-diambil').textContent = totalItems - tidakCount;
        document.getElementById('counter-tidak').textContent   = tidakCount;
    }
</script>
@endsection
