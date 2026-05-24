@extends('layouts.improvement')

@section('title', 'Pindah Rak')
@section('subtitle', 'Relokasi posisi barang antar rak gudang')

@section('content')
<div class="max-w-5xl mx-auto pb-20">
    <form method="POST" action="{{ route('improvement.kelola_barang.pindah.update') }}" id="moveForm">
        @csrf

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <!-- HEADER (Tetap Biru Navy untuk Branding, tapi Accent Teal) -->
            <div class="bg-[#1E4D9C] px-10 py-8 text-white relative overflow-hidden">
                <div class="flex justify-between items-center relative z-10">
                    <div class="flex items-center gap-6">
                        <!-- Icon Box Teal (Konsisten) -->
                        <div class="p-4 bg-[#5EEAD4] rounded-2xl shadow-lg shadow-teal-400/20">
                            <svg class="w-8 h-8 text-[#1E4D9C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black uppercase tracking-tight">Relokasi Rak</h3>
                            <p class="text-teal-200 text-[10px] mt-1 uppercase tracking-[0.2em] font-bold opacity-80">Update Lokasi & Alamat Inventory</p>
                        </div>
                    </div>
                    <div class="hidden md:block text-right">
                        <p class="text-[10px] text-blue-300 font-black uppercase tracking-widest">Periode Transaksi</p>
                        <p class="text-lg font-black text-[#5EEAD4]">{{ now()->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-10 space-y-10">
                <!-- PIC SECTION -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-end">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">PIC Relokasi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <input type="text" name="pic" required class="w-full pl-12 pr-4 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#5EEAD4] outline-none transition-all font-bold text-gray-700 placeholder:text-gray-300 shadow-inner" placeholder="Nama Petugas...">
                        </div>
                    </div>
                </div>

                <!-- SELECTOR SECTION (Warna Teal) -->
                <div class="bg-gray-50/50 p-8 rounded-[2rem] border border-gray-100 shadow-inner space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="md:col-span-6">
                            <select id="itemSelector" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#5EEAD4] outline-none transition-all font-bold text-sm shadow-sm appearance-none cursor-pointer">
                                <option value="">-- Cari Barang --</option>
                                @foreach($barangs as $item)
                                <option value="{{ $item->id }}" data-nama="{{ $item->nama_barang }}" data-kode="{{ $item->kode_barang }}" data-alamat="{{ $item->alamat }}">
                                    {{ $item->kode_barang }} | {{ $item->nama_barang }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <input type="text" id="newAlamat" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#1E4D9C] outline-none transition-all font-bold text-sm shadow-sm uppercase" placeholder="Rak Baru">
                        </div>
                        <div class="md:col-span-3">
                            <button type="button" onclick="addToTable()" class="w-full h-full bg-[#1E4D9C] text-[#ffff] rounded-2xl font-black text-[11px] uppercase tracking-widest hover:brightness-105 transition-all duration-300 shadow-lg shadow-teal-100 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah
                            </button>
                        </div>
                    </div>
                </div>

                <!-- REVIEW TABLE -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between px-2">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-[#5EEAD4] rounded-full animate-pulse"></span>
                            Review Perpindahan
                        </h4>
                        <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest" id="itemCounter">0 Items</span>
                    </div>

                    <div class="overflow-x-auto rounded-[2rem] border border-gray-100">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100">
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Action</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Informasi Barang</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Hapus</th>
                                </tr>
                            </thead>
                            <tbody id="moveTableBody">
                                <tr id="emptyRow">
                                    <td colspan="3" class="px-8 py-20 text-center opacity-20">
                                        <p class="text-xs font-bold uppercase tracking-widest italic text-gray-500">Daftar masih kosong</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- FOOTER ACTION (KONSISTEN TAMBAH STOK) -->
                <div class="flex flex-col md:flex-row items-center justify-between pt-10 border-t border-gray-100 gap-6">
                    <a href="{{ route('improvement.kelola_barang.index') }}" class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 hover:text-gray-600 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Batalkan
                    </a>


                    <button type="submit" class="w-full md:w-auto bg-[#5EEAD4] text-[#1E4D9C] px-12 py-5 rounded-2xl font-black text-sm shadow-xl shadow-teal-100 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-4 order-1 md:order-2 group">
                        KONFIRMASI PINDAH RAK
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    let itemCount = 0;

    function addToTable() {
        const selector = document.getElementById('itemSelector');
        const newAlamatInput = document.getElementById('newAlamat');
        const tableBody = document.getElementById('moveTableBody');
        const emptyRow = document.getElementById('emptyRow');
        const counterDisplay = document.getElementById('itemCounter');

        const itemId = selector.value;
        const newAlamat = newAlamatInput.value.trim();

        if (!itemId || !newAlamat) return alert('Pilih barang & isi rak baru!');

        const selectedOption = selector.options[selector.selectedIndex];
        const nama = selectedOption.getAttribute('data-nama');
        const kode = selectedOption.getAttribute('data-kode');
        const alamatLama = selectedOption.getAttribute('data-alamat') || 'N/A';

        if (emptyRow) emptyRow.remove();

        const row = document.createElement('tr');
        row.className = "border-b border-gray-50 hover:bg-teal-50/20 transition-all";
        row.innerHTML = `
            <td class="px-8 py-5">
                <div class="flex items-center justify-center gap-4">
                    <span class="text-[10px] font-black text-gray-400 bg-gray-50 px-3 py-1 rounded-md border border-gray-100 uppercase tracking-tighter">${alamatLama}</span>
                    <div class="w-8 h-8 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </div>
                    <span class="text-[10px] font-black text-teal-600 bg-teal-50 px-3 py-1 rounded-md border border-teal-100 uppercase tracking-tighter">${newAlamat}</span>
                </div>
                <input type="hidden" name="items[${itemId}][from]" value="${alamatLama}">
                <input type="hidden" name="items[${itemId}][to]" value="${newAlamat}">
            </td>
            <td class="px-8 py-5 text-left">
                <p class="text-xs font-black text-gray-800 uppercase tracking-tight">${nama}</p>
                <p class="text-[10px] text-gray-400 font-bold tracking-widest mt-0.5">${kode}</p>
            </td>
            <td class="px-8 py-5 text-center">
                <button type="button" onclick="this.closest('tr').remove(); itemCount--; document.getElementById('itemCounter').innerText = itemCount + ' Items';" class="p-2 text-red-200 hover:text-red-500 transition-all">
                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </td>
        `;

        tableBody.appendChild(row);
        itemCount++;
        counterDisplay.innerText = `${itemCount} Items`;
        selector.value = "";
        newAlamatInput.value = "";
    }
</script>
@endsection