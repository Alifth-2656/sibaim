@extends('layouts.improvement')

@section('title', 'Tambah Barang')
@section('subtitle', 'Menambahkan barang baru ke sistem gudang')

@section('content')
<div class="max-w-4xl mx-auto">
    <form method="POST" action="{{ route('improvement.kelola_barang.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- HEADER -->
            <div class="bg-[#1E4D9C] px-8 py-6 text-white">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-white/10 rounded-2xl">
                            <svg class="w-6 h-6 text-[#5EEAD4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold uppercase tracking-tight">Form Tambah Barang</h3>
                            <p class="text-blue-200 text-xs mt-1 uppercase tracking-widest font-semibold">Input Data Barang Gudang</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-blue-300 font-bold uppercase">Tanggal</p>
                        <p class="text-sm font-bold text-[#5EEAD4]">{{ now()->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- BODY -->
            <div class="p-8 space-y-6">

                <!-- PIC ROW -->
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">PIC (Penanggung Jawab)</label>
                    <input type="text" name="pic" placeholder="Nama Petugas" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-semibold">
                </div>

                <!-- GRID INFO BARANG -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Kode Barang</label>
                        <input type="text" name="kode_barang" placeholder="Contoh: BRG-001" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-semibold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Nama Barang</label>
                        <input type="text" name="nama_barang" placeholder="Nama Barang" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-semibold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Satuan</label>
                        <input type="text" name="satuan" placeholder="Pcs / Box / Unit" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-semibold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Alamat Rak</label>
                        <input type="text" name="alamat" placeholder="Contoh: A1-02"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-semibold">
                    </div>
                </div>

                <!-- GRID STOK -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Min Stock</label>
                        <input type="number" name="min" id="min" value="0" min="0"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-semibold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Max Stock</label>
                        <input type="number" name="max" id="max" value="0" min="0"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-semibold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Stock Awal</label>
                        <input type="number" name="qty" id="qty" value="0" min="0"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all font-semibold">
                    </div>
                </div>

                <!-- UPLOAD IMAGE -->
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">
                        Upload Gambar Barang
                    </label>
                    <input type="file" name="image" id="imageInput"
                        accept="image/*"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm bg-white 
        file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 
        file:bg-[#1E4D9C] file:text-white hover:file:bg-blue-800 transition shadow-sm">
                    <p id="error-msg" class="text-[10px] text-red-500 mt-1 font-bold hidden uppercase tracking-wider">
                        Format file harus berupa gambar!
                    </p>
                </div>

                <!-- FOOTER / ACTION -->
                <div class="flex flex-col md:flex-row items-center justify-between pt-8 border-t border-gray-100 gap-6">
                    <!-- Tombol Kembali -->
                    <a href="{{ route('improvement.kelola_barang.index') }}" class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 hover:text-gray-600 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Batalkan
                    </a>

                    <!-- Informasi & Button Simpan -->
                    <div class="flex flex-col md:flex-row items-center gap-6 order-1 md:order-2 w-full md:w-auto">
                        <p class="text-[10px] text-gray-400 font-bold italic uppercase tracking-wider text-center md:text-right leading-relaxed">
                            *Pastikan semua data mandatori<br>sudah terisi dengan benar
                        </p>

                        <button type="submit" class="w-full md:w-auto bg-[#5EEAD4] text-[#1E4D9C] px-10 py-4 rounded-2xl font-black text-sm shadow-lg shadow-teal-100 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-3">
                            SIMPAN BARANG
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const minInput = document.getElementById('min');
        const maxInput = document.getElementById('max');
        const qtyInput = document.getElementById('qty');

        function validate() {
            const minVal = parseInt(minInput.value) || 0;
            const maxVal = parseInt(maxInput.value) || 0;
            const qtyVal = parseInt(qtyInput.value) || 0;

            if (maxVal < minVal) {
                maxInput.value = minVal;
            }

            maxInput.min = minVal;
            qtyInput.max = maxVal;

            if (qtyVal > maxVal) {
                qtyInput.value = maxVal;
            }

            if (qtyVal < 0) {
                qtyInput.value = 0;
            }
        }

        minInput.addEventListener('input', validate);
        maxInput.addEventListener('input', validate);
        qtyInput.addEventListener('input', validate);
    });

    // Tambahkan di dalam DOMContentLoaded yang sudah ada
    const imageInput = document.getElementById('imageInput');
    const errorMsg = document.getElementById('error-msg');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const fileType = file['type'];
            const validImageTypes = ['image/gif', 'image/jpeg', 'image/png', 'image/webp'];

            if (!validImageTypes.includes(fileType)) {
                errorMsg.classList.remove('hidden');
                this.value = ''; // Reset input kalau bukan gambar
            } else {
                errorMsg.classList.add('hidden');
            }
        }
    });
</script>
@endsection