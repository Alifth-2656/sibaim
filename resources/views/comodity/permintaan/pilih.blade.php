@extends('layouts.comodity')

@section('title', 'Form Permintaan Barang')
@section('subtitle', 'Langkah 2: Pilih Barang')

@section('content')

<form id="permintaanForm" action="{{ route('comodity.permintaan.cek_stok') }}" method="POST">
    @csrf

    <input type="hidden" name="pic" value="{{ $pic }}">
    <input type="hidden" name="commodity" value="{{ $commodity }}">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($barangs as $item)
        <div class="bg-white rounded-2xl shadow-sm border hover:shadow-md transition overflow-hidden">
            <div class="h-32 bg-gray-100 flex items-center justify-center">
                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" class="h-full w-full object-cover">
                @else
                    <span class="text-gray-400 text-xs">No Image</span>
                @endif
            </div>

            <div class="p-4 space-y-2">
                <h3 class="font-bold text-gray-800 text-sm">{{ $item->nama_barang }}</h3>
                <p class="text-xs text-gray-500">Stock: {{ $item->qty }}</p>

                <div class="flex items-center justify-between mt-3">
                    <button type="button" onclick="kurang({{ $item->id }})"
                        class="bg-gray-200 px-3 py-1 rounded-lg">-</button>

                    <span id="qty-text-{{ $item->id }}" class="font-bold text-lg">0</span>

                    <button type="button" onclick="tambah({{ $item->id }}, {{ $item->qty }})"
                        class="bg-[#1E4D9C] text-white px-3 py-1 rounded-lg">+</button>
                </div>

                <input type="hidden" name="items[{{ $item->id }}]" id="qty-{{ $item->id }}" value="0">
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-10 bg-white p-6 rounded-2xl shadow-sm border">
        <h3 class="font-bold text-gray-800 mb-3">🛒 Barang Dipilih</h3>
        <ul id="cart-list" class="text-sm text-gray-600 space-y-1"></ul>
    </div>

    <button type="button" onclick="submitForm()"
        class="mt-6 bg-[#5EEAD4] text-[#1E4D9C] px-8 py-3 rounded-2xl font-bold shadow hover:scale-105 transition">
        Submit Permintaan
    </button>
</form>

<div id="confirmModal"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl p-6">
        <h2 class="text-xl font-bold text-[#1E4D9C] mb-1">Konfirmasi Permintaan</h2>
        <p class="text-xs text-gray-500 mb-4">Pastikan data sudah benar</p>

        <div class="grid grid-cols-[100px_10px_1fr] gap-x-2 text-sm">
            <span class="font-semibold">PIC</span> <span>:</span> <span>{{ $pic }}</span>
            <span class="font-semibold">COMODITY</span> <span>:</span> <span>{{ $commodity }}</span>
        </div>

        <ul id="confirm-list" class="space-y-2 mt-4 max-h-60 overflow-y-auto"></ul>

        <div class="flex justify-end gap-3 mt-6">
            <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 rounded-xl hover:bg-gray-300">Batal</button>
            <button type="button" onclick="submitConfirmed()" class="px-5 py-2 bg-[#1E4D9C] text-white rounded-xl hover:bg-blue-900">Confirm</button>
        </div>
    </div>
</div>

<script>
    // 🔥 INISIALISASI DATA SEKALI SAJA
    const allBarangs = @json($barangs);
    let cart = {};

    function getBarang(id) {
        return allBarangs.find(b => b.id == id);
    }

    // TAMBAH & KURANG
    function tambah(id, max) {
        let current = cart[id] || 0;
        if (current < max) cart[id] = current + 1;
        updateUI(id);
    }

    function kurang(id) {
        let current = cart[id] || 0;
        if (current > 0) cart[id] = current - 1;
        updateUI(id);
    }

    // UPDATE UI
    function updateUI(id) {
        let qty = cart[id] || 0;
        document.getElementById('qty-' + id).value = qty;
        document.getElementById('qty-text-' + id).innerText = qty;
        renderCart();
    }

    // RENDER CART
    function renderCart() {
        let list = document.getElementById('cart-list');
        list.innerHTML = '';

        for (let id in cart) {
            let qty = cart[id];
            if (qty > 0) {
                let barang = getBarang(id);
                list.innerHTML += `<li>${barang.nama_barang} - ${qty}</li>`;
            }
        }
    }

    // OPEN MODAL
    function submitForm() {
        let list = document.getElementById('confirm-list');
        list.innerHTML = '';
        let hasItem = false;

        for (let id in cart) {
            let qty = cart[id];
            if (qty > 0) {
                let barang = getBarang(id);
                list.innerHTML += `
                    <li class="bg-white border rounded-lg px-3 py-2 space-y-1">
                        <div class="flex justify-between">
                            <span class="font-semibold">${barang.nama_barang}</span>
                            <span class="font-bold text-[#1E4D9C]">${qty}</span>
                        </div>
                        <div class="text-xs text-gray-500">📍 ${barang.alamat ?? 'No Location'}</div>
                    </li>
                `;
                hasItem = true;
            }
        }

        if (!hasItem) {
            alert("Pilih barang dulu 😏");
            return;
        }

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