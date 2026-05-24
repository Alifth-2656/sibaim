@extends('layouts.improvement')

@section('title', 'Stock Take Over (STO)')
@section('subtitle', 'Pengecekan stok fisik vs data sistem')

@section('content')
<div class="max-w-5xl mx-auto pb-20">
    <script src="https://unpkg.com/html5-qrcode"></script>

    <form method="POST" action="{{ route('improvement.kelola_barang.sto.check') }}" id="stoForm">
        @csrf

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">

            {{-- HEADER --}}
            <div class="bg-[#1E4D9C] px-10 py-8 text-white">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-6">
                        <div class="p-4 bg-[#5EEAD4] rounded-2xl shadow-lg shadow-teal-400/20">
                            <svg class="w-8 h-8 text-[#1E4D9C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black uppercase tracking-tight text-white">Stock Take Over</h3>
                            <p class="text-teal-200 text-[10px] mt-1 uppercase tracking-[0.2em] font-bold opacity-80">Cek Fisik vs Sistem</p>
                        </div>
                    </div>
                    <div class="text-right hidden md:block">
                        <p class="text-[10px] text-teal-200 font-bold uppercase tracking-widest">Tanggal STO</p>
                        <p class="text-lg font-black text-white">{{ now()->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-10 space-y-10">

                {{-- PIC + MODE --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">PIC (Penanggung Jawab)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <input type="text" name="pic" required
                                class="w-full pl-12 pr-4 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#5EEAD4] outline-none transition-all font-bold text-gray-700 shadow-inner"
                                placeholder="Nama petugas STO...">
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Metode Input</label>
                        <div class="flex bg-gray-100 p-1.5 rounded-2xl gap-1">
                            <button type="button" onclick="setMode('manual')" id="btnManual"
                                class="flex-1 py-3 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all duration-300 bg-white text-[#1E4D9C] shadow-sm">
                                Manual
                            </button>
                            <button type="button" onclick="setMode('scan')" id="btnScan"
                                class="flex-1 py-3 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all duration-300 text-gray-400">
                                Scan QR
                            </button>
                        </div>
                    </div>
                </div>

                {{-- INPUT AREA --}}
                <div class="bg-gray-50/50 p-8 rounded-[2rem] border border-gray-100 shadow-inner">

                    {{-- MANUAL MODE --}}
                    <div id="manualSection" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="md:col-span-6">
                            <select id="itemSelector"
                                class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#5EEAD4] outline-none transition-all font-bold text-sm shadow-sm cursor-pointer">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangs as $item)
                                <option value="{{ $item->id }}"
                                    data-nama="{{ $item->nama_barang }}"
                                    data-kode="{{ $item->kode_barang }}"
                                    data-satuan="{{ $item->satuan }}"
                                    data-qty-sistem="{{ $item->qty }}">
                                    {{ $item->kode_barang }} | {{ $item->nama_barang }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <input type="number" id="inputQtyFisik" min="0" value="0"
                                class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#5EEAD4] outline-none transition-all font-bold text-sm shadow-sm"
                                placeholder="Qty Fisik">
                        </div>
                        <div class="md:col-span-3">
                            <button type="button" onclick="addItem('manual')"
                                class="w-full h-full bg-[#1E4D9C] text-white rounded-2xl font-black text-[11px] uppercase tracking-widest hover:bg-[#5EEAD4] hover:text-[#1E4D9C] transition-all shadow-lg flex items-center justify-center gap-2 py-4">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah
                            </button>
                        </div>
                    </div>

                    {{-- SCAN MODE --}}
                    <div id="scanSection" class="hidden">
                        <div class="flex flex-col items-center gap-6">
                            <div id="reader" class="w-full max-w-sm rounded-3xl overflow-hidden border-4 border-white shadow-xl bg-black"></div>

                            {{-- Input qty setelah scan --}}
                            <div id="scanResult" class="hidden w-full max-w-sm bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Barang Terdeteksi</p>
                                    <p id="scanNama" class="text-sm font-black text-gray-800 mt-1">-</p>
                                    <p id="scanKode" class="text-[10px] text-gray-400 font-bold">-</p>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Qty Fisik</label>
                                    <input type="number" id="scanQtyFisik" min="0" value="0"
                                        class="w-full px-4 py-3 bg-gray-50 rounded-xl font-black text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#5EEAD4]">
                                </div>
                                <button type="button" onclick="confirmScan()"
                                    class="w-full py-3 bg-[#5EEAD4] text-[#1E4D9C] rounded-xl font-black text-[11px] uppercase tracking-widest hover:opacity-80 transition-all">
                                    Tambah ke List
                                </button>
                            </div>

                            <div id="scanIdle" class="text-center">
                                <div class="inline-flex items-center gap-2 px-4 py-2 bg-teal-50 rounded-full mb-2">
                                    <span class="w-2 h-2 bg-[#5EEAD4] rounded-full animate-ping"></span>
                                    <span class="text-[10px] font-black text-[#1E4D9C] uppercase tracking-widest">Scanner Active</span>
                                </div>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Arahkan kamera ke QR code barang</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TABEL REVIEW --}}
                <div class="space-y-4">
                    <div class="flex items-center justify-between px-2">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Daftar Pengecekan</h4>
                        <span id="itemCount" class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">0 Items</span>
                    </div>

                    <div class="overflow-x-auto rounded-[2rem] border border-gray-100">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100">
                                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Barang</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Qty Sistem</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Qty Fisik</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Selisih</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="stoTableBody">
                                <tr id="emptyRow">
                                    <td colspan="5" class="px-8 py-20 text-center opacity-20 text-[10px] font-black uppercase tracking-[0.2em]">
                                        Belum ada barang — tambah manual atau scan QR
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="flex flex-col md:flex-row items-center justify-between pt-10 border-t border-gray-100 gap-6">
                    <a href="{{ route('improvement.kelola_barang.index') }}"
                        class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 hover:text-gray-600 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Batalkan
                    </a>
                    <button type="submit"
                        class="w-full md:w-auto bg-[#5EEAD4] text-[#1E4D9C] px-12 py-5 rounded-2xl font-black text-sm shadow-xl shadow-teal-100 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-3">
                        CEK SELISIH
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
    let stoItems = {};
    let scanPending = null;
    let html5QrCode = null;
    let isScanning = false;

    // ─── MODE TOGGLE ─────────────────────────────────────────
    function setMode(mode) {
        const manual = document.getElementById('manualSection');
        const scan = document.getElementById('scanSection');
        const btnM = document.getElementById('btnManual');
        const btnS = document.getElementById('btnScan');

        if (mode === 'manual') {
            manual.classList.remove('hidden');
            scan.classList.add('hidden');
            btnM.classList.add('bg-white', 'text-[#1E4D9C]', 'shadow-sm');
            btnM.classList.remove('text-gray-400');
            btnS.classList.remove('bg-white', 'text-[#1E4D9C]', 'shadow-sm');
            btnS.classList.add('text-gray-400');
            stopScanner();
        } else {
            manual.classList.add('hidden');
            scan.classList.remove('hidden');
            btnS.classList.add('bg-white', 'text-[#1E4D9C]', 'shadow-sm');
            btnS.classList.remove('text-gray-400');
            btnM.classList.remove('bg-white', 'text-[#1E4D9C]', 'shadow-sm');
            btnM.classList.add('text-gray-400');
            startScanner();
        }
    }

    // ─── SCANNER ─────────────────────────────────────────────
    function startScanner() {
        if (isScanning) return;
        html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start({
                    facingMode: "environment"
                }, {
                    fps: 15,
                    qrbox: 250
                },
                (decodedText) => onScanSuccess(decodedText)
            )
            .then(() => {
                isScanning = true;
            })
            .catch(err => console.error("Scanner error:", err));
    }

    function stopScanner() {
        if (html5QrCode && isScanning) {
            html5QrCode.stop()
                .then(() => {
                    isScanning = false;
                    html5QrCode = null;
                })
                .catch(err => console.error("Stop error:", err));
        }
    }

    window.addEventListener('beforeunload', () => stopScanner());

    function onScanSuccess(kodeBarang) {
        const selector = document.getElementById('itemSelector');
        let found = null;
        for (let i = 0; i < selector.options.length; i++) {
            const opt = selector.options[i];
            if (opt.getAttribute('data-kode') === kodeBarang) {
                found = opt;
                break;
            }
        }

        if (!found) {
            alert('Kode barang "' + kodeBarang + '" tidak ditemukan di sistem.');
            return;
        }

        scanPending = {
            id: found.value,
            nama: found.getAttribute('data-nama'),
            kode: found.getAttribute('data-kode'),
            satuan: found.getAttribute('data-satuan'),
            qtySistem: parseInt(found.getAttribute('data-qty-sistem')),
        };

        document.getElementById('scanNama').innerText = scanPending.nama;
        document.getElementById('scanKode').innerText = scanPending.kode;
        document.getElementById('scanQtyFisik').value = 0;
        document.getElementById('scanResult').classList.remove('hidden');
        document.getElementById('scanIdle').classList.add('hidden');
    }

    function confirmScan() {
        if (!scanPending) return;
        const qtyFisik = parseInt(document.getElementById('scanQtyFisik').value);
        if (isNaN(qtyFisik) || qtyFisik < 0) {
            alert('Masukkan qty fisik yang valid!');
            return;
        }
        addToTable(scanPending.id, scanPending.nama, scanPending.kode, scanPending.satuan, scanPending.qtySistem, qtyFisik);
        scanPending = null;
        document.getElementById('scanResult').classList.add('hidden');
        document.getElementById('scanIdle').classList.remove('hidden');
    }

    // ─── MANUAL ADD ──────────────────────────────────────────
    function addItem() {
        const selector = document.getElementById('itemSelector');
        const qtyInput = document.getElementById('inputQtyFisik');

        if (!selector.value) {
            alert('Pilih barang terlebih dahulu!');
            return;
        }

        const qtyFisik = parseInt(qtyInput.value);
        if (isNaN(qtyFisik) || qtyFisik < 0) {
            alert('Masukkan qty fisik yang valid!');
            return;
        }

        const opt = selector.options[selector.selectedIndex];
        addToTable(
            String(opt.value), // ✅ pastikan string
            opt.getAttribute('data-nama'),
            opt.getAttribute('data-kode'),
            opt.getAttribute('data-satuan'),
            parseInt(opt.getAttribute('data-qty-sistem')),
            qtyFisik
        );

        selector.value = '';
        qtyInput.value = 0;
    }

    // ─── CORE ────────────────────────────────────────────────
    function addToTable(id, nama, kode, satuan, qtySistem, qtyFisik) {
        // Hapus empty row kalau ada
        const emptyRow = document.getElementById('emptyRow');
        if (emptyRow) emptyRow.remove();

        if (stoItems[id]) {
            // Update existing
            stoItems[id].qtyFisik = qtyFisik;
            updateRow(id);
        } else {
            // Tambah baru
            stoItems[id] = {
                nama,
                kode,
                satuan,
                qtySistem,
                qtyFisik
            };
            renderRow(id);
            updateCounter(); // ✅ hanya dipanggil saat item baru
        }
    }

    function renderRow(id) {
        const item = stoItems[id];
        const selisih = item.qtyFisik - item.qtySistem;
        const colorClass = selisih === 0 ? 'text-green-500' : (selisih > 0 ? 'text-blue-500' : 'text-red-500');
        const selisihStr = selisih > 0 ? '+' + selisih : String(selisih);

        const tbody = document.getElementById('stoTableBody');
        const tr = document.createElement('tr');
        tr.id = 'row-' + id;
        tr.className = 'border-b border-gray-50 hover:bg-purple-50/20 transition-all';

        // ✅ Pakai createElement untuk input agar tidak ada masalah HTML encoding
        tr.innerHTML = `
            <td class="px-6 py-5">
                <p class="text-[11px] font-black text-gray-800 uppercase tracking-tight">${escHtml(item.nama)}</p>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-[0.1em] mt-0.5">${escHtml(item.kode)} · ${escHtml(item.satuan)}</p>
            </td>
            <td class="px-6 py-5 text-center text-sm font-black text-[#1E4D9C]">${item.qtySistem}</td>
            <td class="px-6 py-5 text-center" id="tdFisik-${id}"></td>
            <td class="px-6 py-5 text-center">
                <span id="selisih-${id}" class="text-sm font-black ${colorClass}">${selisihStr}</span>
            </td>
            <td class="px-6 py-5 text-center">
                <button type="button" class="p-2 text-red-200 hover:text-red-500 transition-all" data-remove="${id}">
                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </td>
        `;

        tbody.appendChild(tr);

        // ✅ Buat input & hidden via createElement — hindari masalah template literal + event
        const inputFisik = document.createElement('input');
        inputFisik.type = 'number';
        inputFisik.min = '0';
        inputFisik.value = item.qtyFisik;
        inputFisik.className = 'w-20 text-center px-3 py-2 bg-gray-50 rounded-xl font-black text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-[#5EEAD4] border border-gray-100';
        inputFisik.addEventListener('change', function() {
            updateQtyFisik(id, this.value);
        });

        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'items[' + id + ']';
        hiddenInput.value = item.qtyFisik;
        hiddenInput.id = 'hidden-' + id;

        const tdFisik = document.getElementById('tdFisik-' + id);
        tdFisik.appendChild(inputFisik);
        tdFisik.appendChild(hiddenInput);

        // ✅ Event remove via data attribute — bukan inline onclick
        tr.querySelector('[data-remove]').addEventListener('click', function() {
            removeRow(this.getAttribute('data-remove'));
        });
    }

    function updateRow(id) {
        const item = stoItems[id];
        const selisih = item.qtyFisik - item.qtySistem;
        const colorClass = selisih === 0 ? 'text-green-500' : (selisih > 0 ? 'text-blue-500' : 'text-red-500');

        const selisihEl = document.getElementById('selisih-' + id);
        const hiddenEl = document.getElementById('hidden-' + id);
        const tdFisik = document.getElementById('tdFisik-' + id);
        const inputEl = tdFisik ? tdFisik.querySelector('input[type="number"]') : null;

        if (selisihEl) {
            selisihEl.innerText = selisih > 0 ? '+' + selisih : String(selisih);
            selisihEl.className = 'text-sm font-black ' + colorClass;
        }
        if (hiddenEl) hiddenEl.value = item.qtyFisik;
        if (inputEl) inputEl.value = item.qtyFisik;
    }

    function updateQtyFisik(id, val) {
        const qty = parseInt(val);
        if (isNaN(qty) || qty < 0) return;
        stoItems[id].qtyFisik = qty;
        updateRow(id);
    }

    function removeRow(id) {
        delete stoItems[id];
        const row = document.getElementById('row-' + id);
        if (row) row.remove();
        updateCounter();

        const tbody = document.getElementById('stoTableBody');
        if (Object.keys(stoItems).length === 0) {
            tbody.innerHTML = `
                <tr id="emptyRow">
                    <td colspan="5" class="px-8 py-20 text-center opacity-20 text-[10px] font-black uppercase tracking-[0.2em]">
                        Belum ada barang — tambah manual atau scan QR
                    </td>
                </tr>`;
        }
    }

    function updateCounter() {
        document.getElementById('itemCount').innerText = Object.keys(stoItems).length + ' Items';
    }

    // ✅ Escape HTML untuk mencegah XSS dari nama barang
    function escHtml(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str));
        return d.innerHTML;
    }

    // ─── GUARD SUBMIT ────────────────────────────────────────
    document.getElementById('stoForm').addEventListener('submit', function(e) {
        if (Object.keys(stoItems).length === 0) {
            e.preventDefault();
            alert('Tambahkan minimal 1 barang sebelum cek selisih.');
        }
    });
</script>
@endsection