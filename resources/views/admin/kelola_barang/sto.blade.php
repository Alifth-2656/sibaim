@extends('layouts.admin')

@section('title', 'Stock Take Over (STO)')
@section('subtitle', 'Pengecekan stok fisik vs data sistem')

@section('content')
<div class="max-w-5xl mx-auto pb-20">

    {{-- Flash Error --}}
    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl px-6 py-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
        </svg>
        <p class="text-sm font-bold text-red-700">{{ session('error') }}</p>
    </div>
    @endif

    {{-- RESUME DRAFT --}}
    @if(isset($stoDraft) && $stoDraft)
    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-2xl px-6 py-5 flex flex-col md:flex-row md:items-center gap-4">
        <div class="flex items-start gap-3 flex-1">
            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p class="text-sm font-black text-blue-800">Ada Draft STO yang Belum Dikonfirmasi</p>
                <p class="text-xs text-blue-600 mt-0.5">
                    PIC: <strong>{{ $stoDraft->pic }}</strong> —
                    {{ count($stoDraft->results) }} item —
                    disimpan {{ $stoDraft->updated_at->diffForHumans() }}
                </p>
            </div>
        </div>
        <div class="flex gap-3 flex-shrink-0">
            <form method="POST" action="{{ route('admin.kelola_barang.sto.check') }}" id="resumeForm">
                @csrf
                <input type="hidden" name="pic" value="{{ $stoDraft->pic }}">
                @foreach($stoDraft->results as $item)
                <input type="hidden" name="items[{{ $item['barang_id'] }}]" value="{{ $item['qty_fisik'] }}">
                @endforeach
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-blue-600 transition-all">
                    Lanjutkan →
                </button>
            </form>
            <form method="POST" action="{{ route('admin.kelola_barang.sto.discard_draft') }}">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-white border border-blue-200 text-blue-500 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-blue-50 transition-all"
                    onclick="return confirm('Yakin hapus draft STO ini?')">
                    Hapus Draft
                </button>
            </form>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.kelola_barang.sto.check') }}" id="stoForm">
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
                            <select id="itemSelector">
                                <option value="">-- Cari Barang --</option>
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
                            <button type="button" onclick="addItem()"
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
                        <div class="flex flex-col gap-6">

                            {{-- STATUS SCANNER --}}
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Status Scanner</p>
                                <div id="scannerStatusBadge" class="flex items-center gap-2 px-4 py-2 rounded-full bg-red-50 border border-red-100 transition-all duration-500">
                                    <span id="scannerStatusDot" class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                                    <span id="scannerStatusText" class="text-[10px] font-black uppercase tracking-widest text-red-500">Disconnected</span>
                                </div>
                            </div>

                            {{-- SCAN INPUT AREA --}}
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <svg id="scannerIcon" class="w-6 h-6 text-gray-300 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8H3m2 8H3m18-8h-1M4 4l16 16" />
                                    </svg>
                                </div>
                                <input type="text" id="scannerInput" autocomplete="off" spellcheck="false"
                                    class="w-full pl-14 pr-5 py-5 bg-white border-2 border-dashed border-gray-200 rounded-2xl font-mono font-black text-gray-700 text-sm focus:outline-none focus:border-[#5EEAD4] transition-all duration-300 placeholder:font-normal placeholder:text-gray-300 placeholder:font-sans"
                                    placeholder="Fokuskan kursor di sini, lalu scan barcode...">
                            </div>

                            {{-- SCAN RESULT / CONFIRM --}}
                            <div id="scanResult" class="hidden bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                                <div class="flex flex-col md:flex-row md:items-center gap-4">
                                    <div class="flex-1 space-y-1">
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Barang Terdeteksi</p>
                                        <p id="scanNama" class="text-sm font-black text-gray-800">-</p>
                                        <p id="scanKode" class="text-[10px] text-gray-400 font-bold">-</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="space-y-1">
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Qty Fisik</p>
                                            <input type="number" id="scanQtyFisik" min="0" value="0"
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

                            {{-- HINT --}}
                            <p id="scanHint" class="text-[9px] text-gray-300 font-bold uppercase tracking-widest text-center">
                                Arahkan scanner ke barcode / QR code barang · Hasil otomatis muncul
                            </p>

                        </div>
                    </div>
                </div>

                {{-- TABEL REVIEW --}}
                <div class="space-y-4">
                    <div class="flex items-center justify-between px-2">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Daftar Pengecekan</h4>
                        <span id="itemCount" class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">0 Items</span>
                    </div>

                    {{-- PROGRESS TRACKER --}}
                    <div class="bg-white rounded-2xl border border-gray-100 px-6 py-4 shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Progress Scan</span>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span id="progressScanned" class="text-xl font-black text-[#1E4D9C]">0</span>
                                <span class="text-[10px] font-black text-gray-300">/</span>
                                <span id="progressTotal" class="text-xl font-black text-gray-300">{{ $barangs->count() }}</span>
                                <span class="text-[10px] font-bold text-gray-400 ml-1 uppercase tracking-widest">barang</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div id="progressBar" class="h-2 rounded-full bg-[#5EEAD4] transition-all duration-500" style="width: 0%"></div>
                        </div>
                        <div class="flex justify-between mt-2">
                            <span id="progressPct" class="text-[9px] font-black text-gray-300 uppercase tracking-widest">0%</span>
                            <span id="progressRemaining" class="text-[9px] font-black text-gray-300 uppercase tracking-widest">{{ $barangs->count() }} belum dicek</span>
                        </div>
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

                    {{-- PAGINATION --}}
                    <div id="paginationWrapper" class="px-4 py-3 border-t border-gray-100 items-center justify-between hidden">
                        <p id="paginationInfo" class="text-[10px] font-black text-gray-400 uppercase tracking-widest"></p>
                        <div id="paginationControls" class="flex items-center gap-1"></div>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="flex flex-col md:flex-row items-center justify-between pt-10 border-t border-gray-100 gap-6">
                    <a href="{{ route('admin.kelola_barang.index') }}"
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
    const TOTAL_BARANG = {{ $barangs->count() }};

    // ─── PAGINATION STATE ─────────────────────────────────────
    let allRowIds = []; // menyimpan urutan insert id
    const PER_PAGE = 10;
    let currentPage = 1;

    // ─── INIT TOM SELECT ─────────────────────────────────────
    const tsSto = new TomSelect('#itemSelector', {
        placeholder: 'Cari kode / nama barang...',
        searchField: ['text'],
        maxOptions: 50,
    });

    // ─── PREFILL DARI ULANGI STO ─────────────────────────────
    const prefillData = @json($prefill ?? []);

    if (Object.keys(prefillData).length > 0) {
        const selector = document.getElementById('itemSelector');

        for (const [barangId, qtyFisik] of Object.entries(prefillData)) {
            let found = null;
            for (let i = 0; i < selector.options.length; i++) {
                if (selector.options[i].value == barangId) {
                    found = selector.options[i];
                    break;
                }
            }
            if (!found) continue;

            addToTable(
                found.value,
                found.getAttribute('data-nama'),
                found.getAttribute('data-kode'),
                found.getAttribute('data-satuan'),
                parseInt(found.getAttribute('data-qty-sistem')),
                parseInt(qtyFisik)
            );
        }

        tsSto.clear();
    }

    // ─── MODE TOGGLE ─────────────────────────────────────────
    function setMode(mode) {
        const manual = document.getElementById('manualSection');
        const scan   = document.getElementById('scanSection');
        const btnM   = document.getElementById('btnManual');
        const btnS   = document.getElementById('btnScan');

        if (mode === 'manual') {
            manual.classList.remove('hidden');
            scan.classList.add('hidden');
            btnM.classList.add('bg-white', 'text-[#1E4D9C]', 'shadow-sm');
            btnM.classList.remove('text-gray-400');
            btnS.classList.remove('bg-white', 'text-[#1E4D9C]', 'shadow-sm');
            btnS.classList.add('text-gray-400');
        } else {
            manual.classList.add('hidden');
            scan.classList.remove('hidden');
            btnS.classList.add('bg-white', 'text-[#1E4D9C]', 'shadow-sm');
            btnS.classList.remove('text-gray-400');
            btnM.classList.remove('bg-white', 'text-[#1E4D9C]', 'shadow-sm');
            btnM.classList.add('text-gray-400');
            setTimeout(() => document.getElementById('scannerInput').focus(), 100);
        }
    }

    // ─── SCANNER PHYSICAL ────────────────────────────────────
    const scannerInput = document.getElementById('scannerInput');
    let lastKeyTime    = 0;
    let isConnected    = false;
    let disconnectTimer = null;

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
                this.value = '';
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
        isConnected = status;
        const badge = document.getElementById('scannerStatusBadge');
        const dot   = document.getElementById('scannerStatusDot');
        const text  = document.getElementById('scannerStatusText');
        const icon  = document.getElementById('scannerIcon');

        if (status) {
            badge.className = 'flex items-center gap-2 px-4 py-2 rounded-full bg-green-50 border border-green-200 transition-all duration-500';
            dot.className   = 'w-2.5 h-2.5 rounded-full bg-green-400 animate-ping';
            text.className  = 'text-[10px] font-black uppercase tracking-widest text-green-600';
            text.innerText  = 'Connected';
            icon.className  = 'w-6 h-6 text-[#5EEAD4] transition-all duration-300';
            clearTimeout(disconnectTimer);
            disconnectTimer = setTimeout(() => setConnected(false), 3000);
        } else {
            badge.className = 'flex items-center gap-2 px-4 py-2 rounded-full bg-red-50 border border-red-100 transition-all duration-500';
            dot.className   = 'w-2.5 h-2.5 rounded-full bg-red-400';
            text.className  = 'text-[10px] font-black uppercase tracking-widest text-red-500';
            text.innerText  = 'Disconnected';
            icon.className  = 'w-6 h-6 text-gray-300 transition-all duration-300';
        }
    }

    function speak(text) {
        const utter = new SpeechSynthesisUtterance(text);
        utter.lang   = 'id-ID';
        utter.rate   = 1.1;
        utter.volume = 1;
        window.speechSynthesis.speak(utter);
    }

    function processScanCode(raw) {
        const parts = raw.split('|');
        const kode  = parts[0].trim();
        const qty   = parts[1] ? parseInt(parts[1].trim()) : 1;

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
                hint.innerText = 'Arahkan scanner ke barcode / QR code barang · Hasil otomatis muncul';
                hint.classList.remove('text-red-400');
            }, 2500);
            speak('Barang tidak ditemukan');
            return;
        }

        addToTable(
            found.value,
            found.getAttribute('data-nama'),
            found.getAttribute('data-kode'),
            found.getAttribute('data-satuan'),
            parseInt(found.getAttribute('data-qty-sistem')),
            qty
        );
        speak(found.getAttribute('data-nama') + ', ' + qty + ' unit, ditambahkan');
        scannerInput.value = '';
        scannerInput.focus();
    }

    function confirmScan() {
        const qtyInput = document.getElementById('scanQtyFisik');
        const qtyFisik = parseInt(qtyInput.value);

        if (isNaN(qtyFisik) || qtyFisik < 0) {
            alert('Masukkan qty fisik yang valid!');
            return;
        }

        addToTable(
            qtyInput.dataset.barangId,
            qtyInput.dataset.nama,
            qtyInput.dataset.kode,
            qtyInput.dataset.satuan,
            parseInt(qtyInput.dataset.qtySistem),
            qtyFisik
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

    document.getElementById('scanQtyFisik').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            confirmScan();
        }
    });

    // ─── MANUAL ADD ──────────────────────────────────────────
    function addItem() {
        const qtyInput  = document.getElementById('inputQtyFisik');
        const selectedId = tsSto.getValue();

        if (!selectedId) {
            alert('Pilih barang terlebih dahulu!');
            return;
        }

        const qtyFisik = parseInt(qtyInput.value);
        if (isNaN(qtyFisik) || qtyFisik < 0) {
            alert('Masukkan qty fisik yang valid!');
            return;
        }

        const selector = document.getElementById('itemSelector');
        const opt      = selector.querySelector(`option[value="${selectedId}"]`);

        addToTable(
            String(opt.value),
            opt.getAttribute('data-nama'),
            opt.getAttribute('data-kode'),
            opt.getAttribute('data-satuan'),
            parseInt(opt.getAttribute('data-qty-sistem')),
            qtyFisik
        );

        tsSto.clear();
        qtyInput.value = 0;
    }

    // ─── CORE TABLE ──────────────────────────────────────────
    function addToTable(id, nama, kode, satuan, qtySistem, qtyFisik) {
        if (stoItems[id]) {
            // Update qty fisik lalu re-render halaman aktif
            stoItems[id].qtyFisik = qtyFisik;
            renderPage();
        } else {
            stoItems[id] = { nama, kode, satuan, qtySistem, qtyFisik };
            allRowIds.push(id);
            updateCounter();
            // Loncat ke halaman terakhir agar row baru keliatan
            currentPage = Math.ceil(allRowIds.length / PER_PAGE);
            renderPage();
        }
    }

    // ─── PAGINATION RENDER ───────────────────────────────────
    function renderPage() {
        const tbody = document.getElementById('stoTableBody');
        tbody.innerHTML = '';

        if (allRowIds.length === 0) {
            tbody.innerHTML = `
                <tr id="emptyRow">
                    <td colspan="5" class="px-8 py-20 text-center opacity-20 text-[10px] font-black uppercase tracking-[0.2em]">
                        Belum ada barang — tambah manual atau scan QR
                    </td>
                </tr>`;
            document.getElementById('paginationWrapper').classList.add('hidden');
            return;
        }

        const start = (currentPage - 1) * PER_PAGE;
        const end   = Math.min(start + PER_PAGE, allRowIds.length);

        for (let i = start; i < end; i++) {
            renderRow(allRowIds[i]);
        }

        renderPagination(start + 1, end);
    }

    function renderRow(id) {
        const item       = stoItems[id];
        const selisih    = item.qtyFisik - item.qtySistem;
        const colorClass = selisih === 0 ? 'text-green-500' : (selisih > 0 ? 'text-blue-500' : 'text-red-500');
        const selisihStr = selisih > 0 ? '+' + selisih : String(selisih);

        const tbody = document.getElementById('stoTableBody');
        const tr    = document.createElement('tr');
        tr.id        = 'row-' + id;
        tr.className = 'border-b border-gray-50 hover:bg-purple-50/20 transition-all';

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
            </td>`;

        tbody.appendChild(tr);

        // Input qty fisik (editable)
        const inputFisik     = document.createElement('input');
        inputFisik.type      = 'number';
        inputFisik.min       = '0';
        inputFisik.value     = item.qtyFisik;
        inputFisik.className = 'w-20 text-center px-3 py-2 bg-gray-50 rounded-xl font-black text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-[#5EEAD4] border border-gray-100';
        inputFisik.addEventListener('change', function() {
            updateQtyFisik(id, this.value);
        });

        // Hidden input untuk submit form
        const hiddenInput  = document.createElement('input');
        hiddenInput.type   = 'hidden';
        hiddenInput.name   = 'items[' + id + ']';
        hiddenInput.value  = item.qtyFisik;
        hiddenInput.id     = 'hidden-' + id;

        document.getElementById('tdFisik-' + id).append(inputFisik, hiddenInput);

        tr.querySelector('[data-remove]').addEventListener('click', function() {
            removeRow(this.getAttribute('data-remove'));
        });
    }

    function renderPagination(from, to) {
        const wrapper  = document.getElementById('paginationWrapper');
        const info     = document.getElementById('paginationInfo');
        const controls = document.getElementById('paginationControls');
        const total    = allRowIds.length;
        const lastPage = Math.ceil(total / PER_PAGE);

        wrapper.classList.remove('hidden');
        wrapper.classList.add('flex');
        info.textContent = `${from}–${to} dari ${total}`;
        controls.innerHTML = '';

        // Prev
        const prev     = document.createElement('button');
        prev.type      = 'button';
        prev.textContent = '← Prev';
        prev.className = `px-3 py-1 text-[10px] font-black uppercase transition-all ${currentPage === 1 ? 'text-gray-300 cursor-not-allowed' : 'text-[#1E4D9C] hover:text-blue-400'}`;
        prev.disabled  = currentPage === 1;
        prev.onclick   = () => { currentPage--; renderPage(); };
        controls.appendChild(prev);

        // Page numbers
        buildPageList(currentPage, lastPage).forEach((page, idx, arr) => {
            if (idx > 0 && page - arr[idx - 1] > 1) {
                const dots = document.createElement('span');
                dots.textContent = '…';
                dots.className   = 'text-gray-300 font-black text-xs';
                controls.appendChild(dots);
            }
            const btn     = document.createElement('button');
            btn.type      = 'button';
            btn.textContent = page;
            btn.className = `w-7 h-7 flex items-center justify-center rounded-lg text-[10px] font-black transition-all ${page === currentPage ? 'bg-[#1E4D9C] text-white shadow' : 'text-gray-400 hover:bg-gray-100'}`;
            btn.onclick   = () => { currentPage = page; renderPage(); };
            controls.appendChild(btn);
        });

        // Next
        const next     = document.createElement('button');
        next.type      = 'button';
        next.textContent = 'Next →';
        next.className = `px-3 py-1 text-[10px] font-black uppercase transition-all ${currentPage === lastPage ? 'text-gray-300 cursor-not-allowed' : 'text-[#1E4D9C] hover:text-blue-400'}`;
        next.disabled  = currentPage === lastPage;
        next.onclick   = () => { currentPage++; renderPage(); };
        controls.appendChild(next);
    }

    function buildPageList(current, last) {
        const pages = new Set([1]);
        for (let i = Math.max(2, current - 1); i <= Math.min(last - 1, current + 1); i++) pages.add(i);
        if (last > 1) pages.add(last);
        return [...pages].sort((a, b) => a - b);
    }

    // ─── UPDATE ROW (qty fisik berubah) ──────────────────────
    function updateRow(id) {
        const item       = stoItems[id];
        const selisih    = item.qtyFisik - item.qtySistem;
        const colorClass = selisih === 0 ? 'text-green-500' : (selisih > 0 ? 'text-blue-500' : 'text-red-500');

        const selisihEl = document.getElementById('selisih-' + id);
        const hiddenEl  = document.getElementById('hidden-' + id);
        const tdFisik   = document.getElementById('tdFisik-' + id);
        const inputEl   = tdFisik ? tdFisik.querySelector('input[type="number"]') : null;

        if (selisihEl) {
            selisihEl.innerText = selisih > 0 ? '+' + selisih : String(selisih);
            selisihEl.className = 'text-sm font-black ' + colorClass;
        }
        if (hiddenEl) hiddenEl.value = item.qtyFisik;
        if (inputEl)  inputEl.value  = item.qtyFisik;
    }

    function updateQtyFisik(id, val) {
        const qty = parseInt(val);
        if (isNaN(qty) || qty < 0) return;
        stoItems[id].qtyFisik = qty;
        updateRow(id);
    }

    // ─── REMOVE ROW ──────────────────────────────────────────
    function removeRow(id) {
        delete stoItems[id];
        allRowIds = allRowIds.filter(r => r !== id);
        updateCounter();

        const lastPage = Math.ceil(allRowIds.length / PER_PAGE) || 1;
        if (currentPage > lastPage) currentPage = lastPage;
        renderPage();
    }

    // ─── COUNTER + PROGRESS ──────────────────────────────────
    function updateCounter() {
        const scanned   = allRowIds.length;
        const remaining = TOTAL_BARANG - scanned;
        const pct       = TOTAL_BARANG > 0 ? Math.round((scanned / TOTAL_BARANG) * 100) : 0;

        document.getElementById('itemCount').innerText      = scanned + ' Items';
        document.getElementById('progressScanned').innerText = scanned;
        document.getElementById('progressBar').style.width  = pct + '%';
        document.getElementById('progressPct').innerText    = pct + '%';

        const remainingEl = document.getElementById('progressRemaining');
        const barEl       = document.getElementById('progressBar');

        if (pct === 100) {
            barEl.className      = 'h-2 rounded-full bg-green-400 transition-all duration-500';
            remainingEl.innerText = '✓ Semua tercek';
            remainingEl.className = 'text-[9px] font-black text-green-500 uppercase tracking-widest';
        } else {
            barEl.className      = 'h-2 rounded-full bg-[#5EEAD4] transition-all duration-500';
            remainingEl.innerText = remaining + ' belum dicek';
            remainingEl.className = 'text-[9px] font-black text-gray-300 uppercase tracking-widest';
        }
    }

    // ─── HELPER ──────────────────────────────────────────────
    function escHtml(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str));
        return d.innerHTML;
    }

    // ─── GUARD SUBMIT ────────────────────────────────────────
    document.getElementById('stoForm').addEventListener('submit', function(e) {
        if (allRowIds.length === 0) {
            e.preventDefault();
            alert('Tambahkan minimal 1 barang sebelum cek selisih.');
        }
    });
</script>
@endsection