@extends('layouts.improvement')

@section('title', 'Barang Keluar')

@section('content')
<div class="max-w-5xl mx-auto pb-20">
    <script src="https://unpkg.com/html5-qrcode"></script>

    <form method="POST" action="{{ route('improvement.kelola_barang.out.store') }}" id="outForm">
        @csrf

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <!-- HEADER -->
            <div class="bg-[#1E4D9C] px-10 py-8 text-white relative overflow-hidden">
                <div class="flex justify-between items-center relative z-10">
                    <div class="flex items-center gap-6">
                        <div class="p-4 bg-[#5EEAD4] rounded-2xl shadow-lg shadow-teal-400/20">
                            <svg class="w-8 h-8 text-[#1E4D9C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black uppercase tracking-tight">Barang Keluar</h3>
                            <p class="text-teal-200 text-[10px] mt-1 uppercase tracking-[0.2em] font-bold opacity-80">Pengurangan Stok & Inventaris</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-10 space-y-10">
                <!-- PIC & KETERANGAN -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">PIC Pengambil</label>
                        <input type="text" name="pic" required class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#5EEAD4] outline-none transition-all font-bold text-gray-700 shadow-inner" placeholder="Nama pengambil...">
                    </div>
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Keterangan</label>
                        <input type="text" name="keterangan" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-[#5EEAD4] outline-none transition-all font-bold text-gray-700 shadow-inner" placeholder="Keperluan...">
                    </div>
                </div>

                <!-- MODE SELECTOR (TAB STYLE) -->
                <div class="flex justify-center">
                    <div class="bg-gray-100 p-1.5 rounded-2xl flex items-center gap-1">
                        <button type="button" onclick="switchMode('manual')" id="btn-manual" class="mode-btn px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all bg-white text-[#1E4D9C] shadow-sm">
                            Manual Input
                        </button>
                        <button type="button" onclick="switchMode('scan')" id="btn-scan" class="mode-btn px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all text-gray-400">
                            Scan Barcode
                        </button>
                    </div>
                </div>

                <!-- INPUT AREA -->
                <div class="bg-gray-50/50 p-8 rounded-[2rem] border border-gray-100 shadow-inner">
                    <!-- MANUAL MODE -->
                    <div id="manual-area" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="md:col-span-6">
                            <select id="itemSelector" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#5EEAD4] outline-none transition-all font-bold text-sm shadow-sm cursor-pointer appearance-none">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangs as $item)
                                <option value="{{ $item->id }}" data-nama="{{ $item->nama_barang }}" data-kode="{{ $item->kode_barang }}" data-stok="{{ $item->qty }}">
                                    {{ $item->kode_barang }} | {{ $item->nama_barang }} ({{ $item->qty }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <input type="number" id="qtyInput" class="w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#5EEAD4] outline-none transition-all font-bold text-sm shadow-sm" placeholder="Qty">
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

                    <!-- SCAN MODE -->
                    <div id="scan-area" class="hidden flex-col items-center gap-6">
                        <div id="reader" class="w-full max-w-sm rounded-3xl overflow-hidden border-4 border-white shadow-xl bg-black"></div>
                        <div class="text-center">
                            <p class="text-[10px] font-black text-[#1E4D9C] uppercase tracking-widest">Scanning Active...</p>
                            <p class="text-[9px] text-gray-400 mt-1 font-bold">Arahkan kamera ke Barcode untuk input otomatis qty 1</p>
                        </div>
                    </div>
                </div>

                <!-- TABLE REVIEW -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between px-2">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Daftar Pengambilan</h4>
                        <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest" id="itemCounter">0 Items</span>
                    </div>
                    <div class="overflow-x-auto rounded-[2rem] border border-gray-100">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100">
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Informasi Barang</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Qty Keluar</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="outTableBody">
                                <tr id="emptyRow">
                                    <td colspan="3" class="px-8 py-20 text-center opacity-20 text-[10px] font-black uppercase tracking-[0.2em]">Belum ada data</td>
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
                    <button type="submit" class="w-full md:w-auto bg-[#5EEAD4] text-[#1E4D9C] px-12 py-5 rounded-2xl font-black text-sm shadow-xl shadow-teal-100 hover:scale-105 transition-all group">
                        PROSES BARANG KELUAR
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    let itemCount = 0;
    let html5QrCode;
    const itemsInTable = {}; // Untuk mencegah double entry/update qty yang sudah ada

    function switchMode(mode) {
        const manualArea = document.getElementById('manual-area');
        const scanArea = document.getElementById('scan-area');
        const btnManual = document.getElementById('btn-manual');
        const btnScan = document.getElementById('btn-scan');

        if (mode === 'manual') {
            manualArea.classList.remove('hidden');
            scanArea.classList.add('hidden');
            btnManual.classList.add('bg-white', 'text-[#1E4D9C]', 'shadow-sm');
            btnScan.classList.remove('bg-white', 'text-[#1E4D9C]', 'shadow-sm');
            btnScan.classList.add('text-gray-400');
            stopScanner();
        } else {
            manualArea.classList.add('hidden');
            scanArea.classList.remove('hidden');
            btnScan.classList.add('bg-white', 'text-[#1E4D9C]', 'shadow-sm');
            btnScan.classList.remove('text-gray-400');
            btnManual.classList.remove('bg-white', 'text-[#1E4D9C]', 'shadow-sm');
            btnManual.classList.add('text-gray-400');
            startScanner();
        }
    }

    function startScanner() {
        html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start({
            facingMode: "environment"
        }, {
            fps: 10,
            qrbox: 250
        }, (decodedText) => {
            const selector = document.getElementById('itemSelector');
            for (let i = 0; i < selector.options.length; i++) {
                if (selector.options[i].getAttribute('data-kode') === decodedText) {
                    processSelection(selector.options[i].value, 1, selector.options[i].getAttribute('data-nama'), decodedText, selector.options[i].getAttribute('data-stok'));
                    // Play beep sound if you want
                    break;
                }
            }
        });
    }

    function stopScanner() {
        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop();
        }
    }

    function addToTable(mode) {
        const selector = document.getElementById('itemSelector');
        const qtyInput = document.getElementById('qtyInput');
        if (!selector.value || !qtyInput.value) return alert('Lengkapi data!');

        const opt = selector.options[selector.selectedIndex];
        processSelection(selector.value, parseInt(qtyInput.value), opt.getAttribute('data-nama'), opt.getAttribute('data-kode'), opt.getAttribute('data-stok'));

        selector.value = "";
        qtyInput.value = "";
    }

    function processSelection(id, qty, nama, kode, stok) {
        if (qty > stok) return alert('Stok tidak mencukupi!');

        const tableBody = document.getElementById('outTableBody');
        const emptyRow = document.getElementById('emptyRow');
        if (emptyRow) emptyRow.remove();

        // Cek jika barang sudah ada di tabel, update qty saja
        const existingRow = document.querySelector(`tr[data-id="${id}"]`);
        if (existingRow) {
            const qtyField = existingRow.querySelector('.qty-text');
            const hiddenQty = existingRow.querySelector('.qty-input');
            let newQty = parseInt(hiddenQty.value) + qty;
            if (newQty > stok) return alert('Total qty melebihi stok!');
            qtyField.innerText = `-${newQty}`;
            hiddenQty.value = newQty;
            return;
        }

        const row = document.createElement('tr');
        row.setAttribute('data-id', id);
        row.className = "border-b border-gray-50 hover:bg-red-50/20 transition-all";
        row.innerHTML = `
            <td class="px-8 py-5">
                <p class="text-[11px] font-black text-gray-800 uppercase">${nama}</p>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">${kode}</p>
                <input type="hidden" name="items[${itemCount}][barang_id]" value="${id}">
            </td>
            <td class="px-8 py-5 text-center">
                <span class="qty-text text-xs font-black text-red-600">-${qty}</span>
                <input type="hidden" name="items[${itemCount}][qty]" value="${qty}" class="qty-input">
            </td>
            <td class="px-8 py-5 text-center">
                <button type="button" onclick="this.closest('tr').remove(); itemCount--; document.getElementById('itemCounter').innerText = itemCount + ' Items';" class="text-red-200 hover:text-red-600 transition-colors">
                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </td>
        `;
        tableBody.appendChild(row);
        itemCount++;
        document.getElementById('itemCounter').innerText = itemCount + ' Items';
    }
</script>
@endsection