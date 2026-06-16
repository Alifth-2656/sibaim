@extends('layouts.admin')

@section('title', 'Scan Pindah Rak')
@section('subtitle', 'Relokasi posisi barang antar rak gudang via Barcode Scanner')

@section('content')
<div class="max-w-5xl mx-auto pb-20">
    <form method="POST" action="{{ route('admin.kelola_barang.pindah.update') }}" id="moveForm">
        @csrf

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">

            <!-- HEADER -->
            <div class="bg-[#1E4D9C] px-10 py-8 text-white relative overflow-hidden">
                <div class="flex justify-between items-center relative z-10">
                    <div class="flex items-center gap-6">
                        <div class="p-4 bg-[#5EEAD4] rounded-2xl shadow-lg shadow-teal-400/20">
                            <svg class="w-8 h-8 text-[#1E4D9C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black uppercase tracking-tight">Pindah Rak</h3>
                            <p class="text-teal-200 text-[10px] mt-1 uppercase tracking-[0.2em] font-bold opacity-80">Relokasi Posisi Barang via Scanner</p>
                        </div>
                    </div>
                    <div class="hidden md:block text-right">
                        <p class="text-[10px] text-blue-300 font-black uppercase tracking-widest">Tanggal Input</p>
                        <p class="text-lg font-black text-[#5EEAD4]">{{ now()->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-10 space-y-10">

                <!-- ROW 1: PIC + METODE INPUT -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-end">
                    <!-- PIC -->
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">PIC (Penanggung Jawab)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <input type="text" name="pic" required
                                class="w-full pl-12 pr-4 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#5EEAD4] outline-none transition-all font-bold text-gray-700 placeholder:text-gray-300 shadow-inner"
                                placeholder="Nama petugas...">
                        </div>
                    </div>

                    <!-- METODE INPUT TOGGLE -->
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Metode Input</label>
                        <div class="flex bg-gray-100 rounded-2xl p-1 gap-1">
                            <button type="button" id="tab-manual" onclick="switchTab('manual')"
                                class="flex-1 py-3 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all text-gray-400">
                                Manual Input
                            </button>
                            <button type="button" id="tab-scan" onclick="switchTab('scan')"
                                class="flex-1 py-3 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all bg-white shadow text-[#1E4D9C]">
                                Scan Barcode
                            </button>
                        </div>
                    </div>
                </div>

                <!-- PANEL SCAN -->
                <div id="panel-scan" class="bg-gray-50/50 p-8 rounded-[2rem] border border-gray-100 shadow-inner space-y-4">
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Status Scanner</p>
                        <span id="scannerStatus"
                            class="flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full bg-red-50 text-red-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400 inline-block"></span>
                            Disconnected
                        </span>
                    </div>

                    <!-- Input scan utama -->
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-gray-300">
                            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h2M4 10h2M4 14h2M18 6h2M18 10h2M18 14h2M7 4v16M17 4v16M7 4h10M7 20h10" />
                            </svg>
                        </span>
                        <input type="text" id="scanInput" autocomplete="off"
                            class="w-full pl-14 pr-5 py-5 bg-white border-2 border-dashed border-[#5EEAD4] rounded-2xl focus:ring-0 focus:border-[#1E4D9C] outline-none transition-all font-bold text-gray-700 placeholder:text-gray-300 text-sm"
                            placeholder="Fokuskan kursor di sini, lalu scan barcode...">
                        <p class="text-center text-[10px] text-gray-300 font-black uppercase tracking-[0.2em] mt-3">Arahkan scanner ke barcode · Hasil otomatis muncul</p>
                    </div>

                    <!-- Input rak tujuan (muncul setelah scan) -->
                    <div id="rakTujuanWrapper" class="hidden">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">Rak Tujuan</label>
                        <div class="flex gap-3">
                            <input type="text" id="scanRak" autocomplete="off"
                                class="flex-1 px-5 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#5EEAD4] outline-none transition-all font-bold text-sm shadow-sm uppercase"
                                placeholder="Contoh: B3-05">
                            <button type="button" onclick="addFromScan()"
                                class="bg-[#1E4D9C] text-white px-6 rounded-2xl font-black text-[11px] uppercase tracking-widest hover:brightness-105 transition-all shadow-lg flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah
                            </button>
                        </div>
                        <!-- Info barang hasil scan -->
                        <div id="scannedItemInfo" class="mt-3 px-5 py-3 bg-teal-50 rounded-xl border border-teal-100 hidden">
                            <p id="scannedNama" class="text-xs font-black text-gray-700 uppercase"></p>
                            <p id="scannedKode" class="text-[10px] text-gray-400 font-bold tracking-widest mt-0.5"></p>
                        </div>
                    </div>
                </div>

                <!-- PANEL MANUAL -->
                <div id="panel-manual" class="hidden bg-gray-50/50 p-8 rounded-[2rem] border border-gray-100 shadow-inner">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="md:col-span-6">
                            <select id="itemSelector"
                                class="w-full bg-white border border-gray-100 rounded-2xl font-bold text-sm shadow-sm">
                                <option value="">-- Cari Barang --</option>
                                @foreach($barangs as $item)
                                <option value="{{ $item->id }}"
                                    data-nama="{{ $item->nama_barang }}"
                                    data-kode="{{ $item->kode_barang }}"
                                    data-alamat="{{ $item->alamat ?? 'N/A' }}">
                                    {{ $item->kode_barang }} | {{ $item->nama_barang }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <input type="text" id="manualRak"
                                class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#1E4D9C] outline-none transition-all font-bold text-sm shadow-sm uppercase"
                                placeholder="Rak Baru">
                        </div>
                        <div class="md:col-span-3">
                            <button type="button" onclick="addFromManual()"
                                class="w-full h-full bg-[#1E4D9C] text-white rounded-2xl font-black text-[11px] uppercase tracking-widest hover:brightness-105 transition-all shadow-lg flex items-center justify-center gap-2">
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
                            Daftar Perpindahan
                        </h4>
                        <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest" id="itemCounter">0 Items</span>
                    </div>

                    <div class="overflow-x-auto rounded-[2rem] border border-gray-100">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100">
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Perpindahan</th>
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
                        <!-- PAGINATION -->
                        <div id="paginationWrapper" class="px-4 py-3 border-t border-gray-100 items-center justify-between hidden">
                            <p id="paginationInfo" class="text-[10px] font-black text-gray-400 uppercase tracking-widest"></p>
                            <div id="paginationControls" class="flex items-center gap-1"></div>
                        </div>
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="flex flex-col md:flex-row items-center justify-between pt-10 border-t border-gray-100 gap-6">
                    <a href="{{ route('admin.kelola_barang.index') }}"
                        class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 hover:text-gray-600 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Batalkan
                    </a>
                    <button type="submit"
                        class="w-full md:w-auto bg-[#5EEAD4] text-[#1E4D9C] px-12 py-5 rounded-2xl font-black text-sm shadow-xl shadow-teal-100 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-4 order-1 md:order-2 group">
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
    const barangData = {};
    @foreach($barangs as $item)
    barangData["{{ $item->kode_barang }}"] = {
        id: "{{ $item->id }}",
        nama: "{{ $item->nama_barang }}",
        kode: "{{ $item->kode_barang }}",
        alamat: "{{ $item->alamat ?? 'N/A' }}"
    };
    @endforeach

    let itemCount = 0;
    const addedIds = new Set();
    let currentScannedItem = null;
    let scanTimeout = null;

    let allRows = [];
    const PER_PAGE = 10;
    let currentPage = 1;

    function switchTab(tab) {
        const isScan = tab === 'scan';
        document.getElementById('panel-scan').classList.toggle('hidden', !isScan);
        document.getElementById('panel-manual').classList.toggle('hidden', isScan);
        const tabScan = document.getElementById('tab-scan'),
            tabManual = document.getElementById('tab-manual');
        if (isScan) {
            tabScan.classList.add('bg-white', 'shadow', 'text-[#1E4D9C]');
            tabScan.classList.remove('text-gray-400');
            tabManual.classList.remove('bg-white', 'shadow', 'text-[#1E4D9C]');
            tabManual.classList.add('text-gray-400');
            document.getElementById('scanInput').focus();
        } else {
            tabManual.classList.add('bg-white', 'shadow', 'text-[#1E4D9C]');
            tabManual.classList.remove('text-gray-400');
            tabScan.classList.remove('bg-white', 'shadow', 'text-[#1E4D9C]');
            tabScan.classList.add('text-gray-400');
        }
    }

    const scanInput = document.getElementById('scanInput');
    scanInput.addEventListener('focus', () => setStatus('connected'));
    scanInput.addEventListener('blur', () => setStatus('disconnected'));
    scanInput.addEventListener('input', function() {
        clearTimeout(scanTimeout);
        scanTimeout = setTimeout(() => {
            const val = this.value.trim().toUpperCase();
            if (val.length >= 3) processScan(val);
        }, 300);
    });
    scanInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(scanTimeout);
            const val = this.value.trim().toUpperCase();
            if (val) processScan(val);
        }
    });

    function setStatus(status) {
        const el = document.getElementById('scannerStatus');
        if (status === 'connected') {
            el.className = 'flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full bg-teal-50 text-teal-600';
            el.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-[#5EEAD4] inline-block animate-pulse"></span> Connected';
        } else {
            el.className = 'flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full bg-red-50 text-red-400';
            el.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-red-400 inline-block"></span> Disconnected';
        }
    }

    function processScan(kode) {
        const item = barangData[kode];
        if (!item) {
            showScanError('Kode "' + kode + '" tidak ditemukan!');
            return;
        }
        currentScannedItem = item;
        document.getElementById('scannedNama').textContent = item.nama;
        document.getElementById('scannedKode').textContent = item.kode + ' · Rak saat ini: ' + item.alamat;
        document.getElementById('scannedItemInfo').classList.remove('hidden');
        document.getElementById('rakTujuanWrapper').classList.remove('hidden');
        document.getElementById('scanRak').focus();
    }

    function showScanError(msg) {
        scanInput.classList.add('border-red-300');
        setTimeout(() => scanInput.classList.remove('border-red-300'), 1500);
        alert(msg);
    }

    function addFromScan() {
        if (!currentScannedItem) return alert('Scan barang terlebih dahulu!');
        const rakBaru = document.getElementById('scanRak').value.trim().toUpperCase();
        if (!rakBaru) return alert('Isi rak tujuan!');
        if (addRow(currentScannedItem.id, currentScannedItem.nama, currentScannedItem.kode, currentScannedItem.alamat, rakBaru)) {
            scanInput.value = '';
            document.getElementById('scanRak').value = '';
            document.getElementById('rakTujuanWrapper').classList.add('hidden');
            document.getElementById('scannedItemInfo').classList.add('hidden');
            currentScannedItem = null;
            scanInput.focus();
        }
    }

    document.getElementById('scanRak').addEventListener('keydown', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            addFromScan();
        }
    });

    function addFromManual() {
        const selector = document.getElementById('itemSelector'),
            rakBaru = document.getElementById('manualRak').value.trim().toUpperCase();
        const itemId = selector.value;
        if (!itemId || !rakBaru) return alert('Pilih barang & isi rak tujuan!');
        const opt = selector.options[selector.selectedIndex];
        if (addRow(itemId, opt.getAttribute('data-nama'), opt.getAttribute('data-kode'), opt.getAttribute('data-alamat') || 'N/A', rakBaru)) {
            selector.value = '';
            document.getElementById('manualRak').value = '';
        }
    }

    function addRow(itemId, nama, kode, alamatLama, rakBaru) {
        if (addedIds.has(itemId)) {
            alert('Barang ' + kode + ' sudah ada di daftar!');
            return false;
        }

        const row = document.createElement('tr');
        row.className = 'border-b border-gray-50 hover:bg-teal-50/20 transition-all';
        row.dataset.id = itemId;
        row.innerHTML = `
            <td class="px-8 py-5">
                <div class="flex items-center justify-center gap-4">
                    <span class="text-[10px] font-black text-gray-400 bg-gray-50 px-3 py-1 rounded-md border border-gray-100 uppercase tracking-tighter">${alamatLama}</span>
                    <div class="w-8 h-8 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </div>
                    <span class="text-[10px] font-black text-teal-600 bg-teal-50 px-3 py-1 rounded-md border border-teal-100 uppercase tracking-tighter">${rakBaru}</span>
                </div>
                <input type="hidden" name="items[${itemId}][from]" value="${alamatLama}">
                <input type="hidden" name="items[${itemId}][to]" value="${rakBaru}">
            </td>
            <td class="px-8 py-5 text-left">
                <p class="text-xs font-black text-gray-800 uppercase tracking-tight">${nama}</p>
                <p class="text-[10px] text-gray-400 font-bold tracking-widest mt-0.5">${kode}</p>
            </td>
            <td class="px-8 py-5 text-center">
                <button type="button" onclick="removeRow(this, '${itemId}')" class="p-2 text-red-200 hover:text-red-500 transition-all">
                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </td>`;

        allRows.push(row);
        addedIds.add(itemId);
        itemCount++;
        document.getElementById('itemCounter').innerText = `${itemCount} Items`;
        currentPage = Math.ceil(allRows.length / PER_PAGE);
        renderPage();
        return true;
    }

    function renderPage() {
        const tbody = document.getElementById('moveTableBody');
        tbody.innerHTML = '';
        if (allRows.length === 0) {
            tbody.innerHTML = '<tr id="emptyRow"><td colspan="3" class="px-8 py-20 text-center opacity-20"><p class="text-xs font-bold uppercase tracking-widest italic text-gray-500">Daftar masih kosong</p></td></tr>';
            document.getElementById('paginationWrapper').classList.add('hidden');
            return;
        }
        const start = (currentPage - 1) * PER_PAGE,
            end = Math.min(start + PER_PAGE, allRows.length);
        for (let i = start; i < end; i++) tbody.appendChild(allRows[i]);
        renderPagination(start + 1, end);
    }

    function renderPagination(from, to) {
        const wrapper = document.getElementById('paginationWrapper'),
            info = document.getElementById('paginationInfo'),
            controls = document.getElementById('paginationControls'),
            total = allRows.length,
            lastPage = Math.ceil(total / PER_PAGE);
        wrapper.classList.remove('hidden');
        wrapper.classList.add('flex');
        info.textContent = `${from}–${to} dari ${total}`;
        controls.innerHTML = '';
        const prev = document.createElement('button');
        prev.type = 'button';
        prev.textContent = '← Prev';
        prev.className = `px-3 py-1 text-[10px] font-black uppercase transition-all ${currentPage === 1 ? 'text-gray-300 cursor-not-allowed' : 'text-[#1E4D9C] hover:text-blue-400'}`;
        prev.disabled = currentPage === 1;
        prev.onclick = () => {
            currentPage--;
            renderPage();
        };
        controls.appendChild(prev);
        buildPageList(currentPage, lastPage).forEach((page, idx, arr) => {
            if (idx > 0 && page - arr[idx - 1] > 1) {
                const d = document.createElement('span');
                d.textContent = '…';
                d.className = 'text-gray-300 font-black text-xs';
                controls.appendChild(d);
            }
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = page;
            btn.className = `w-7 h-7 flex items-center justify-center rounded-lg text-[10px] font-black transition-all ${page === currentPage ? 'bg-[#1E4D9C] text-white shadow' : 'text-gray-400 hover:bg-gray-100'}`;
            btn.onclick = () => {
                currentPage = page;
                renderPage();
            };
            controls.appendChild(btn);
        });
        const next = document.createElement('button');
        next.type = 'button';
        next.textContent = 'Next →';
        next.className = `px-3 py-1 text-[10px] font-black uppercase transition-all ${currentPage === lastPage ? 'text-gray-300 cursor-not-allowed' : 'text-[#1E4D9C] hover:text-blue-400'}`;
        next.disabled = currentPage === lastPage;
        next.onclick = () => {
            currentPage++;
            renderPage();
        };
        controls.appendChild(next);
    }

    function buildPageList(current, last) {
        const pages = new Set([1]);
        for (let i = Math.max(2, current - 1); i <= Math.min(last - 1, current + 1); i++) pages.add(i);
        if (last > 1) pages.add(last);
        return [...pages].sort((a, b) => a - b);
    }

    function removeRow(btn, itemId) {
        const rowEl = btn.closest('tr');
        allRows = allRows.filter(r => r !== rowEl);
        addedIds.delete(itemId);
        itemCount--;
        document.getElementById('itemCounter').innerText = `${itemCount} Items`;
        const lastPage = Math.ceil(allRows.length / PER_PAGE) || 1;
        if (currentPage > lastPage) currentPage = lastPage;
        renderPage();
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('scanInput').focus();
    });

    const tsMove = new TomSelect('#itemSelector', {
        placeholder: 'Cari kode / nama barang...',
        searchField: ['text'],
        maxOptions: 50,
    });
</script>
@endsection