@extends('layouts.improvement')

@section('title', 'Tambah Stock')
@section('subtitle', 'Input penambahan stok barang ke sistem')

@section('content')
<div class="max-w-5xl mx-auto pb-20">

    <form method="POST" action="{{ route('improvement.kelola_barang.stok.store') }}" id="mainForm">
        @csrf

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <!-- HEADER -->
            <div class="bg-[#1E4D9C] px-10 py-8 text-white relative overflow-hidden">
                <div class="flex justify-between items-center relative z-10">
                    <div class="flex items-center gap-6">
                        <div class="p-4 bg-[#5EEAD4] rounded-2xl shadow-lg shadow-teal-400/20">
                            <svg class="w-8 h-8 text-[#1E4D9C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black uppercase tracking-tight text-white">Barang Masuk</h3>
                            <p class="text-teal-200 text-[10px] mt-1 uppercase tracking-[0.2em] font-bold opacity-80">Penambahan Stok & Inventaris</p>
                        </div>
                    </div>
                    <div class="text-right hidden md:block">
                        <p class="text-[10px] text-teal-200 font-bold uppercase tracking-widest">Tanggal Input</p>
                        <p class="text-lg font-black text-white">{{ now()->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-10 space-y-10">
                <!-- PIC SECTION -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">PIC (Penanggung Jawab)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <input type="text" name="pic" required class="w-full pl-12 pr-4 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#5EEAD4] outline-none transition-all font-bold text-gray-700 shadow-inner" placeholder="Nama petugas...">
                        </div>
                    </div>

                    <!-- MODE SELECTOR -->
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Metode Input</label>
                        <div class="flex bg-gray-100 p-1.5 rounded-2xl gap-1">
                            <button type="button" onclick="setMode('manual')" id="btnManual" class="flex-1 py-3 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all duration-300 bg-white text-[#1E4D9C] shadow-sm">
                                Manual Input
                            </button>
                            <button type="button" onclick="setMode('scan')" id="btnScan" class="flex-1 py-3 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all duration-300 text-gray-400">
                                Scan Barcode
                            </button>
                        </div>
                    </div>
                </div>

                <!-- INPUT AREA -->
                <div class="bg-gray-50/50 p-8 rounded-[2rem] border border-gray-100 shadow-inner">
                    <!-- MANUAL MODE -->
                    <div id="manualSection" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="md:col-span-6">
                            <select id="itemSelector" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#5EEAD4] outline-none transition-all font-bold text-sm shadow-sm cursor-pointer appearance-none">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangs as $item)
                                <option value="{{ $item->id }}" data-nama="{{ $item->nama_barang }}" data-kode="{{ $item->kode_barang }}" data-stok="{{ $item->qty }}">
                                    {{ $item->kode_barang }} | {{ $item->nama_barang }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <input type="number" id="inputQty" min="1" value="1" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#5EEAD4] outline-none transition-all font-bold text-sm shadow-sm" placeholder="Qty">
                        </div>
                        <div class="md:col-span-3">
                            <button type="button" onclick="addToTable('manual')" class="w-full h-full bg-[#1E4D9C] text-white rounded-2xl font-black text-[11px] uppercase tracking-widest hover:bg-[#5EEAD4] hover:text-[#1E4D9C] transition-all shadow-lg flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah
                            </button>
                        </div>
                    </div>

                    {{-- SCAN MODE --}}
                    <div id="scanSection" class="hidden">
                        <div class="flex flex-col gap-6">

                            {{-- Status Scanner --}}
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Status Scanner</p>
                                <div id="scannerStatusBadge" class="flex items-center gap-2 px-4 py-2 rounded-full bg-red-50 border border-red-100 transition-all duration-500">
                                    <span id="scannerStatusDot" class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                                    <span id="scannerStatusText" class="text-[10px] font-black uppercase tracking-widest text-red-500">Disconnected</span>
                                </div>
                            </div>

                            {{-- Scan Input --}}
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <svg id="scannerIcon" class="w-6 h-6 text-gray-300 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8H3m2 8H3m18-8h-1M4 4l16 16" />
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    id="scannerInput"
                                    autocomplete="off"
                                    spellcheck="false"
                                    class="w-full pl-14 pr-5 py-5 bg-white border-2 border-dashed border-gray-200 rounded-2xl font-mono font-black text-gray-700 text-sm focus:outline-none focus:border-[#5EEAD4] transition-all duration-300 placeholder:font-normal placeholder:text-gray-300 placeholder:font-sans"
                                    placeholder="Fokuskan kursor di sini, lalu scan barcode...">
                            </div>

                            {{-- Scan Result Card --}}
                            <div id="scanResult" class="hidden bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                                <div class="flex flex-col md:flex-row md:items-center gap-4">
                                    <div class="flex-1 space-y-1">
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Barang Terdeteksi</p>
                                        <p id="scanNama" class="text-sm font-black text-gray-800">-</p>
                                        <p id="scanKode" class="text-[10px] text-gray-400 font-bold">-</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="space-y-1">
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Qty Tambah</p>
                                            <input type="number" id="scanQty" min="1" value="1"
                                                class="w-28 px-4 py-3 bg-gray-50 rounded-xl font-black text-gray-800 text-center focus:outline-none focus:ring-2 focus:ring-[#5EEAD4] border border-gray-100">
                                        </div>
                                        <button type="button" onclick="confirmScan()"
                                            class="mt-5 px-6 py-3 bg-[#5EEAD4] text-[#1E4D9C] rounded-xl font-black text-[11px] uppercase tracking-widest hover:opacity-80 transition-all whitespace-nowrap">
                                            + Tambah
                                        </button>
                                        <button type="button" onclick="cancelScan()"
                                            class="mt-5 px-4 py-3 bg-gray-100 text-gray-400 rounded-xl font-black text-[11px] uppercase tracking-widest hover:bg-gray-200 transition-all">
                                            Batal
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <p id="scanHint" class="text-[9px] text-gray-300 font-bold uppercase tracking-widest text-center">
                                Arahkan scanner ke barcode · Hasil otomatis muncul
                            </p>
                        </div>
                    </div>
                </div>

                <!-- TABLE REVIEW -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between px-2">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Daftar Penambahan</h4>
                        <span id="itemCount" class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">0 Items</span>
                    </div>
                    <div class="overflow-x-auto rounded-[2rem] border border-gray-100">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100">
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Informasi Barang</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Stok Awal</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Tambah Qty</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="stokTableBody">
                                <tr id="emptyRow">
                                    <td colspan="4" class="px-8 py-20 text-center opacity-20 text-[10px] font-black uppercase tracking-[0.2em]">Belum ada barang di list</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="flex flex-col md:flex-row items-center justify-between pt-10 border-t border-gray-100 gap-6">
                    <a href="{{ route('improvement.kelola_barang.index') }}" class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 hover:text-gray-600 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Batalkan
                    </a>
                    <button type="submit" class="w-full md:w-auto bg-[#5EEAD4] text-[#1E4D9C] px-12 py-5 rounded-2xl font-black text-sm shadow-xl shadow-teal-100 hover:scale-105 active:scale-95 transition-all group flex items-center justify-center gap-3">
                        SIMPAN PENAMBAHAN STOK
                        <svg class="w-5 h-5 group-hover:translate-y-[-2px] transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    let itemCount = 0;
    let lastKeyTime = 0;
    let disconnectTimer = null;

    // ─── MODE TOGGLE ─────────────────────────────────────
    function setMode(mode) {
        const manual = document.getElementById('manualSection');
        const scan = document.getElementById('scanSection');
        const btnManual = document.getElementById('btnManual');
        const btnScan = document.getElementById('btnScan');

        if (mode === 'manual') {
            manual.classList.remove('hidden');
            scan.classList.add('hidden');
            btnManual.classList.add('bg-white', 'text-[#1E4D9C]', 'shadow-sm');
            btnManual.classList.remove('text-gray-400');
            btnScan.classList.remove('bg-white', 'text-[#1E4D9C]', 'shadow-sm');
            btnScan.classList.add('text-gray-400');
        } else {
            manual.classList.add('hidden');
            scan.classList.remove('hidden');
            btnScan.classList.add('bg-white', 'text-[#1E4D9C]', 'shadow-sm');
            btnScan.classList.remove('text-gray-400');
            btnManual.classList.remove('bg-white', 'text-[#1E4D9C]', 'shadow-sm');
            btnManual.classList.add('text-gray-400');
            setTimeout(() => document.getElementById('scannerInput').focus(), 100);
        }
    }

    // ─── PHYSICAL SCANNER ────────────────────────────────
    const scannerInput = document.getElementById('scannerInput');

    scannerInput.addEventListener('keydown', function(e) {
        const now = Date.now();
        const gap = now - lastKeyTime;
        lastKeyTime = now;

        if (e.key !== 'Enter' && e.key.length === 1 && gap < 50) {
            setConnected(true);
        }

        if (e.key === 'Enter') {
            e.preventDefault();
            const kode = this.value.trim();
            if (kode) {
                processScanCode(kode);
    
            }
        }
    });

    scannerInput.addEventListener('blur', function() {
        disconnectTimer = setTimeout(() => setConnected(false), 300);
    });

    scannerInput.addEventListener('focus', function() {
        clearTimeout(disconnectTimer);
    });

    function setConnected(status) {
        const badge = document.getElementById('scannerStatusBadge');
        const dot = document.getElementById('scannerStatusDot');
        const text = document.getElementById('scannerStatusText');
        const icon = document.getElementById('scannerIcon');

        if (status) {
            badge.className = 'flex items-center gap-2 px-4 py-2 rounded-full bg-green-50 border border-green-200 transition-all duration-500';
            dot.className = 'w-2.5 h-2.5 rounded-full bg-green-400 animate-ping';
            text.className = 'text-[10px] font-black uppercase tracking-widest text-green-600';
            text.innerText = 'Connected';
            icon.className = 'w-6 h-6 text-[#5EEAD4] transition-all duration-300';
            clearTimeout(disconnectTimer);
            disconnectTimer = setTimeout(() => setConnected(false), 3000);
        } else {
            badge.className = 'flex items-center gap-2 px-4 py-2 rounded-full bg-red-50 border border-red-100 transition-all duration-500';
            dot.className = 'w-2.5 h-2.5 rounded-full bg-red-400';
            text.className = 'text-[10px] font-black uppercase tracking-widest text-red-500';
            text.innerText = 'Disconnected';
            icon.className = 'w-6 h-6 text-gray-300 transition-all duration-300';
        }
    }

    function speak(text) {
        const utter = new SpeechSynthesisUtterance(text);
        utter.lang = 'id-ID';
        utter.rate = 1.1;
        utter.volume = 1;
        window.speechSynthesis.speak(utter);
    }

    function processScanCode(raw) {
        const parts = raw.split('|');
        const kode = parts[0].trim();
        const qty = parts[1] ? parseInt(parts[1].trim()) : 1;
        const selector = document.getElementById('itemSelector');
        let found = null;
        for (let i = 0; i < selector.options.length; i++) {
            if (selector.options[i].getAttribute('data-kode') === kode) {
                found = selector.options[i];
                break;
            }
        }
        if (!found) {
            scannerInput.classList.add('border-red-300', 'bg-red-50');
            setTimeout(() => scannerInput.classList.remove('border-red-300', 'bg-red-50'), 1000);
            const hint = document.getElementById('scanHint');
            hint.innerText = '⚠ Kode "' + kode + '" tidak ditemukan!';
            hint.classList.add('text-red-400');
            setTimeout(() => {
                hint.innerText = 'Arahkan scanner ke barcode · Hasil otomatis muncul';
                hint.classList.remove('text-red-400');
            }, 2500);
            speak('Barang tidak ditemukan');
            return;
        }
        processEntry(found.value, qty, found.getAttribute('data-nama'), found.getAttribute('data-kode'), found.getAttribute('data-stok'));
        speak(found.getAttribute('data-nama') + ', ' + qty + ' unit, ditambahkan');
        scannerInput.value = '';
        scannerInput.focus();
    }


    function confirmScan() {
        const qtyInput = document.getElementById('scanQty');
        const qty = parseInt(qtyInput.value);
        if (isNaN(qty) || qty < 1) return alert('Masukkan qty yang valid!');

        processEntry(
            qtyInput.dataset.barangId,
            qty,
            qtyInput.dataset.nama,
            qtyInput.dataset.kode,
            qtyInput.dataset.stok
        );

        document.getElementById('scanResult').classList.add('hidden');
        setTimeout(() => {
            scannerInput.value = '';
            scannerInput.focus();
        }, 100);
    }

    function cancelScan() {
        document.getElementById('scanResult').classList.add('hidden');
        scannerInput.value = '';
        setTimeout(() => scannerInput.focus(), 100);
    }

    document.getElementById('scanQty').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            confirmScan();
        }
    });

    // ─── MANUAL ADD ──────────────────────────────────────
    function addToTable() {
        const selector = document.getElementById('itemSelector');
        const qtyInput = document.getElementById('inputQty');

        if (!selector.value || !qtyInput.value || qtyInput.value < 1)
            return alert('Pilih barang & masukkan Qty!');

        const opt = selector.options[selector.selectedIndex];
        processEntry(
            selector.value,
            parseInt(qtyInput.value),
            opt.getAttribute('data-nama'),
            opt.getAttribute('data-kode'),
            opt.getAttribute('data-stok')
        );

        selector.value = '';
        qtyInput.value = 1;
    }

    // ─── CORE TABLE ──────────────────────────────────────
    function processEntry(id, qty, nama, kode, stokAwal) {
        const tableBody = document.getElementById('stokTableBody');
        const emptyRow = document.getElementById('emptyRow');
        if (emptyRow) emptyRow.remove();

        // BAGIAN 1 — update row yang sudah ada
        const existingRow = document.querySelector(`tr[data-id="${id}"]`);
        if (existingRow) {
            const input = existingRow.querySelector('.qty-input'); // ← ubah ini
            input.value = parseInt(input.value) + qty; // ← ubah ini
            return;
        }

        const row = document.createElement('tr');
        row.setAttribute('data-id', id);
        row.className = 'border-b border-gray-50 hover:bg-teal-50/20 transition-all';

        // BAGIAN 2 — row baru, ganti innerHTML-nya
        row.innerHTML = `
        <td class="px-8 py-5">
            <p class="text-[11px] font-black text-gray-800 uppercase tracking-tight">${nama}</p>
            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-[0.1em] mt-0.5">${kode}</p>
        </td>
        <td class="px-8 py-5 text-center text-[11px] font-bold text-gray-400">${stokAwal}</td>
        <td class="px-8 py-5 text-center">
            <input 
                type="number" 
                min="1" 
                value="${qty}"
                name="items[${id}]"
                class="qty-input w-20 text-center px-3 py-1.5 bg-teal-50 border border-teal-100 rounded-lg text-xs font-black text-[#1E4D9C] focus:outline-none focus:ring-2 focus:ring-[#5EEAD4]">
        </td>
        <td class="px-8 py-5 text-center">
            <button type="button" onclick="removeRow(this)" class="p-2 text-red-200 hover:text-red-500 transition-all">
                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </td>`;

        tableBody.appendChild(row);
        itemCount++;
        updateCounter();
    }

    function removeRow(btn) {
        btn.closest('tr').remove();
        itemCount--;
        updateCounter();
        const tableBody = document.getElementById('stokTableBody');
        if (tableBody.children.length === 0) {
            tableBody.innerHTML = '<tr id="emptyRow"><td colspan="4" class="px-8 py-20 text-center opacity-20 text-[10px] font-black uppercase tracking-[0.2em]">Belum ada barang di list</td></tr>';
        }
    }

    function updateCounter() {
        document.getElementById('itemCount').innerText = itemCount + ' Items';
    }
</script>
@endsection