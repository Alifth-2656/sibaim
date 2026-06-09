@extends('layouts.comodity')

@section('title', 'Form Permintaan Barang')
@section('subtitle', 'Langkah 2: Pilih Barang')

@section('content')

<form id="permintaanForm" action="{{ route('comodity.permintaan.cek_stok') }}" method="POST">
    @csrf

    <input type="hidden" name="pic" value="{{ $pic }}">
    <input type="hidden" name="commodity" value="{{ $commodity }}">

    {{-- Search + Pilih Barang --}}
    <div class="bg-white rounded-2xl shadow-sm border p-5 mb-6">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Barang</label>

        <div class="relative" id="search-wrapper">
            <input type="text" id="barang-search" autocomplete="off"
                placeholder="🔍 Ketik nama atau kode barang..."
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white">

            {{-- Dropdown hasil search --}}
            <div id="search-dropdown"
                class="absolute z-40 w-full bg-white border border-gray-200 rounded-xl shadow-lg mt-1 max-h-60 overflow-y-auto hidden">
            </div>
        </div>

        {{-- Info Barang yang dipilih --}}
        <div id="barang-info" class="hidden mt-4 bg-gray-50 rounded-xl border px-4 py-3 text-sm space-y-1">
            <div class="flex justify-between">
                <span class="text-gray-500">Barang dipilih</span>
                <span id="info-nama" class="font-semibold text-gray-800 text-right max-w-[60%]">-</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Stok tersedia</span>
                <span id="info-stok" class="font-semibold text-gray-800">-</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Alamat / Lokasi</span>
                <span id="info-alamat" class="font-semibold text-gray-800 text-right max-w-[60%]">-</span>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <label class="text-gray-500">Jumlah</label>
                <select id="qty-select"
                    class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white">
                    <option value="0">0</option>
                </select>
                <button type="button" onclick="addToCart()"
                    class="bg-[#1E4D9C] text-white px-4 py-1.5 rounded-lg text-sm hover:bg-blue-900 transition">
                    + Tambah
                </button>
            </div>
        </div>
    </div>

    {{-- Hidden inputs for cart --}}
    <div id="hidden-inputs"></div>

    {{-- Cart List --}}
    <div class="bg-white rounded-2xl shadow-sm border p-5">
        <h3 class="font-bold text-gray-800 mb-3">🛒 Barang Dipilih</h3>
        <div id="cart-empty" class="text-sm text-gray-400 italic">Belum ada barang dipilih.</div>
        <table id="cart-table" class="w-full text-sm hidden">
            <thead>
                <tr class="border-b text-gray-500">
                    <th class="text-left py-2">Barang</th>
                    <th class="text-left py-2">Alamat</th>
                    <th class="text-center py-2">Jumlah</th>
                    <th class="py-2"></th>
                </tr>
            </thead>
            <tbody id="cart-body"></tbody>
        </table>
    </div>

    <button type="button" onclick="submitForm()"
        class="mt-6 bg-[#5EEAD4] text-[#1E4D9C] px-8 py-3 rounded-2xl font-bold shadow hover:scale-105 transition">
        Submit Permintaan
    </button>
</form>

{{-- Confirm Modal --}}
<div id="confirmModal"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6">
        <h2 class="text-xl font-bold text-[#1E4D9C] mb-1">Konfirmasi Permintaan</h2>
        <p class="text-xs text-gray-500 mb-4">Pastikan data sudah benar</p>

        <div class="grid grid-cols-[100px_10px_1fr] gap-x-2 text-sm mb-4">
            <span class="font-semibold">PIC</span> <span>:</span> <span>{{ $pic }}</span>
            <span class="font-semibold">COMODITY</span> <span>:</span> <span>{{ $commodity }}</span>
        </div>

        <ul id="confirm-list" class="space-y-2 max-h-64 overflow-y-auto"></ul>

        <div class="flex justify-end gap-3 mt-6">
            <button type="button" onclick="closeModal()"
                class="px-4 py-2 bg-gray-200 rounded-xl hover:bg-gray-300">Batal</button>
            <button type="button" onclick="submitConfirmed()"
                class="px-5 py-2 bg-[#1E4D9C] text-white rounded-xl hover:bg-blue-900">Confirm</button>
        </div>
    </div>
</div>

<script>
    const allBarangs = @json($barangs);
    let cart = {};
    let selectedBarang = null;

    const searchInput = document.getElementById('barang-search');
    const dropdown = document.getElementById('search-dropdown');

    // Saat user ngetik
    function renderDropdown(keyword) {
        dropdown.innerHTML = '';

        const results = keyword ?
            allBarangs.filter(b =>
                b.nama_barang.toLowerCase().includes(keyword) ||
                (b.kode_barang && b.kode_barang.toLowerCase().includes(keyword))
            ) :
            allBarangs;

        if (results.length === 0) {
            dropdown.innerHTML = `<div class="px-4 py-3 text-sm text-gray-400 italic">Barang tidak ditemukan</div>`;
            dropdown.classList.remove('hidden');
            return;
        }

        results.forEach(b => {
            const div = document.createElement('div');
            div.className = 'px-4 py-2.5 text-sm hover:bg-blue-50 cursor-pointer flex justify-between items-center border-b last:border-0';
            const nama = keyword ? highlightText(b.nama_barang, keyword) : b.nama_barang;
            const kode = b.kode_barang ? `<span class="text-xs text-gray-400">${b.kode_barang}</span>` : '';
            div.innerHTML = `
            <span>${nama} ${kode}</span>
            <span class="text-xs text-gray-500 ml-2 shrink-0">Stok: ${b.qty}</span>
        `;
            div.addEventListener('click', () => selectBarang(b));
            dropdown.appendChild(div);
        });

        dropdown.classList.remove('hidden');
    }

    searchInput.addEventListener('focus', function() {
        renderDropdown(this.value.trim().toLowerCase());
    });

    searchInput.addEventListener('input', function() {
        renderDropdown(this.value.trim().toLowerCase());
    });

    // Highlight teks yang cocok
    function highlightText(text, keyword) {
        const regex = new RegExp(`(${keyword})`, 'gi');
        return text.replace(regex, '<mark class="bg-yellow-200 rounded px-0.5">$1</mark>');
    }

    // Pilih barang dari dropdown
    function selectBarang(b) {
        selectedBarang = b;
        searchInput.value = `${b.kode_barang ? b.kode_barang + ' | ' : ''}${b.nama_barang}`;
        dropdown.classList.add('hidden');

        document.getElementById('info-nama').innerText = b.nama_barang;
        document.getElementById('info-stok').innerText = b.qty;
        document.getElementById('info-alamat').innerText = b.alamat ?? '-';

        const qtySelect = document.getElementById('qty-select');
        qtySelect.innerHTML = '';
        for (let i = 0; i <= b.qty; i++) {
            const opt = document.createElement('option');
            opt.value = i;
            opt.innerText = i;
            if (cart[b.id] && cart[b.id].qty == i) opt.selected = true;
            qtySelect.appendChild(opt);
        }

        document.getElementById('barang-info').classList.remove('hidden');
    }

    // Tutup dropdown kalau klik di luar
    document.addEventListener('click', function(e) {
        if (!document.getElementById('search-wrapper').contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    function addToCart() {
        if (!selectedBarang) {
            alert('Pilih barang dulu!');
            return;
        }
        const qty = parseInt(document.getElementById('qty-select').value);
        if (qty === 0) {
            alert('Jumlah harus lebih dari 0!');
            return;
        }

        const b = selectedBarang;
        cart[b.id] = {
            nama: b.nama_barang,
            qty,
            alamat: b.alamat ?? '-'
        };

        renderCart();
        renderHiddenInputs();

        // Reset
        searchInput.value = '';
        selectedBarang = null;
        document.getElementById('barang-info').classList.add('hidden');
    }

    function removeFromCart(id) {
        delete cart[id];
        renderCart();
        renderHiddenInputs();
    }

    function renderCart() {
        const body = document.getElementById('cart-body');
        const table = document.getElementById('cart-table');
        const empty = document.getElementById('cart-empty');
        body.innerHTML = '';
        const keys = Object.keys(cart);

        if (keys.length === 0) {
            table.classList.add('hidden');
            empty.classList.remove('hidden');
            return;
        }

        table.classList.remove('hidden');
        empty.classList.add('hidden');

        keys.forEach(id => {
            const item = cart[id];
            body.innerHTML += `
                <tr class="border-b">
                    <td class="py-2 font-medium text-gray-800">${item.nama}</td>
                    <td class="py-2 text-gray-500 text-xs">${item.alamat}</td>
                    <td class="py-2 text-center font-bold text-[#1E4D9C]">${item.qty}</td>
                    <td class="py-2 text-right">
                        <button type="button" onclick="removeFromCart(${id})"
                            class="text-red-400 hover:text-red-600 text-xs">Hapus</button>
                    </td>
                </tr>`;
        });
    }

    function renderHiddenInputs() {
        const container = document.getElementById('hidden-inputs');
        container.innerHTML = '';
        for (let id in cart) {
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = `items[${id}]`;
            inp.value = cart[id].qty;
            container.appendChild(inp);
        }
    }

    function submitForm() {
        const keys = Object.keys(cart);
        if (keys.length === 0) {
            alert('Pilih barang dulu 😏');
            return;
        }

        const list = document.getElementById('confirm-list');
        list.innerHTML = '';
        keys.forEach(id => {
            const item = cart[id];
            list.innerHTML += `
                <li class="bg-white border rounded-lg px-3 py-2 space-y-1">
                    <div class="flex justify-between">
                        <span class="font-semibold text-sm">${item.nama}</span>
                        <span class="font-bold text-[#1E4D9C] text-sm">${item.qty}</span>
                    </div>
                    <div class="text-xs text-gray-500">📍 ${item.alamat}</div>
                </li>`;
        });

        document.getElementById('confirmModal').classList.remove('hidden');
        document.getElementById('confirmModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('confirmModal').classList.add('hidden');
        document.getElementById('confirmModal').classList.remove('flex');
    }

    function submitConfirmed() {
        document.getElementById('permintaanForm').submit();
    }
</script>

@endsection