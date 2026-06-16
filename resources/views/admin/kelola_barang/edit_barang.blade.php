@extends('layouts.admin')

@section('title', 'Edit Barang')
@section('subtitle', 'Ubah data informasi barang di sistem gudang')

@section('content')
<div class="max-w-5xl mx-auto pb-20">

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl flex items-center gap-3 text-sm font-bold">
        <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl flex items-center gap-3 text-sm font-bold">
        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">

        <!-- HEADER -->
        <div class="bg-[#1E4D9C] px-10 py-8 text-white relative overflow-hidden">
            <div class="flex justify-between items-center relative z-10">
                <div class="flex items-center gap-6">
                    <div class="p-4 bg-[#5EEAD4] rounded-2xl shadow-lg shadow-teal-400/20">
                        <svg class="w-8 h-8 text-[#1E4D9C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black uppercase tracking-tight text-white">Edit Barang</h3>
                        <p class="text-teal-200 text-[10px] mt-1 uppercase tracking-[0.2em] font-bold opacity-80">Ubah Data & Informasi Barang</p>
                    </div>
                </div>
                <div class="text-right hidden md:block">
                    <p class="text-[10px] text-teal-200 font-bold uppercase tracking-widest">Tanggal</p>
                    <p class="text-lg font-black text-white">{{ now()->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <div class="p-10 space-y-10">

            <!-- PILIH BARANG -->
            <div class="bg-gray-50/50 p-8 rounded-[2rem] border border-gray-100 shadow-inner">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Pilih Barang yang Akan Diedit</p>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-9">
                        <select id="barangSelector">
                            <option value="">-- Cari & Pilih Barang --</option>
                            @foreach($barangs as $item)
                            <option value="{{ $item->id }}"
                                data-kode="{{ $item->kode_barang }}"
                                data-nama="{{ $item->nama_barang }}"
                                data-satuan="{{ $item->satuan }}"
                                data-alamat="{{ $item->alamat ?? '' }}"
                                data-min="{{ $item->min }}"
                                data-max="{{ $item->max }}"
                                data-qty="{{ $item->qty }}"
                                data-image="{{ $item->image ? asset('storage/' . $item->image) : '' }}">
                                {{ $item->kode_barang }} | {{ $item->nama_barang }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-3">
                        <button type="button" onclick="addToEditTable()"
                            class="w-full py-4 bg-[#1E4D9C] text-white rounded-2xl font-black text-[11px] uppercase tracking-widest hover:bg-[#5EEAD4] hover:text-[#1E4D9C] transition-all shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                            </svg>
                            Pilih Barang
                        </button>
                    </div>
                </div>
            </div>

            <!-- TABLE EDIT -->
            <div class="space-y-4">
                <div class="flex items-center justify-between px-2">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Daftar Barang yang Diedit</h4>
                    <span id="itemCount" class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">0 Items</span>
                </div>

                <form method="POST" action="{{ route('admin.kelola_barang.edit.update') }}" id="editForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="overflow-x-auto rounded-[2rem] border border-gray-100">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/80 border-b border-gray-100">
                                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Kode</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Barang</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Satuan</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Min</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Max</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Alamat Rak</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="editTableBody">
                                <tr id="emptyRow">
                                    <td colspan="7" class="px-8 py-20 text-center opacity-20 text-[10px] font-black uppercase tracking-[0.2em]">
                                        Belum ada barang dipilih — pilih dari dropdown di atas
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    <div id="paginationWrapper" class="px-4 py-3 border-t border-gray-100 items-center justify-between hidden">
                        <p id="paginationInfo" class="text-[10px] font-black text-gray-400 uppercase tracking-widest"></p>
                        <div id="paginationControls" class="flex items-center gap-1"></div>
                    </div>

                    <!-- FOOTER -->
                    <div class="flex flex-col md:flex-row items-center justify-between pt-10 border-t border-gray-100 gap-6 mt-6">
                        <a href="{{ route('admin.kelola_barang.index') }}"
                            class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 hover:text-gray-600 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Batalkan
                        </a>
                        <button type="submit" id="submitBtn"
                            class="w-full md:w-auto bg-[#5EEAD4] text-[#1E4D9C] px-12 py-5 rounded-2xl font-black text-sm shadow-xl shadow-teal-100 hover:scale-105 active:scale-95 transition-all group flex items-center justify-center gap-3 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:scale-100"
                            disabled>
                            SIMPAN PERUBAHAN
                            <svg class="w-5 h-5 group-hover:translate-y-[-2px] transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    let editedIds = new Set();
    let itemCount = 0;
    let allRows = [];
    const PER_PAGE = 10;
    let currentPage = 1;

    const tsEdit = new TomSelect('#barangSelector', {
        placeholder: 'Cari kode / nama barang...',
        searchField: ['text'],
        maxOptions: 100,
        onChange(value) {
            document.getElementById('barangSelector').value = value;
        }
    });

    function addToEditTable() {
        const selector = document.getElementById('barangSelector');
        const id = selector.value;

        if (!id) return alert('Pilih barang terlebih dahulu!');
        if (editedIds.has(id)) return alert('Barang ini sudah ada di daftar edit!');

        const opt = selector.options[selector.selectedIndex];
        const kode = opt.getAttribute('data-kode');
        const nama = opt.getAttribute('data-nama');
        const satuan = opt.getAttribute('data-satuan');
        const alamat = opt.getAttribute('data-alamat');
        const min = opt.getAttribute('data-min');
        const max = opt.getAttribute('data-max');
        const image = opt.getAttribute('data-image');

        addRowToTable(id, kode, nama, satuan, alamat, min, max, image);

        tsEdit.removeOption(id);
        tsEdit.clear(true);
    }

    function addRowToTable(id, kode, nama, satuan, alamat, min, max, image) {
        const emptyRow = document.getElementById('emptyRow');
        if (emptyRow) emptyRow.remove();

        editedIds.add(id);

        const safeNama = nama.replace(/'/g, "\\'");
        const safeSatuan = satuan.replace(/'/g, "\\'");
        const safeAlamat = alamat.replace(/'/g, "\\'");

        const row = document.createElement('tr');
        row.setAttribute('data-id', id);
        row.className = 'border-b border-gray-50 hover:bg-blue-50/10 transition-all';
        row.innerHTML = `
            <td class="px-6 py-5">
                <p class="text-[11px] font-black text-gray-800 uppercase tracking-tight">${kode}</p>
                <input type="hidden" name="items[${id}][id]" value="${id}">
            </td>
            <td class="px-6 py-4">
                <input type="text" name="items[${id}][nama_barang]" value="${nama}" required placeholder="Nama Barang"
                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#5EEAD4] transition-all min-w-[140px]">
            </td>
            <td class="px-6 py-4">
                <input type="text" name="items[${id}][satuan]" value="${satuan}" required placeholder="Pcs / Box"
                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#5EEAD4] transition-all min-w-[80px]">
            </td>
            <td class="px-6 py-4 text-center">
                <input type="number" name="items[${id}][min]" value="${min}" min="0" required
                    class="w-20 text-center px-2 py-2 bg-orange-50 border border-orange-100 rounded-xl text-xs font-black text-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-300 transition-all">
            </td>
            <td class="px-6 py-4 text-center">
                <input type="number" name="items[${id}][max]" value="${max}" min="0" required
                    class="w-20 text-center px-2 py-2 bg-blue-50 border border-blue-100 rounded-xl text-xs font-black text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 transition-all">
            </td>
            <td class="px-6 py-4">
                <input type="text" name="items[${id}][alamat]" value="${alamat}" placeholder="A1-01"
                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#5EEAD4] transition-all min-w-[80px]">
            </td>
            <td class="px-6 py-5 text-center">
                <div style="display:flex;gap:6px;justify-content:center">
                    <button type="button" onclick="togglePhotoRow('${id}')"
                        class="p-2 text-blue-300 hover:text-blue-500 transition-all" title="Upload foto">
                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </button>
                    <button type="button" onclick="removeRow(this, '${id}', '${kode}', '${safeNama}', '${safeSatuan}', '${safeAlamat}', '${min}', '${max}', '${image}')"
                        class="p-2 text-red-200 hover:text-red-500 transition-all" title="Hapus dari daftar">
                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
            </td>`;

        const expandRow = document.createElement('tr');
        expandRow.setAttribute('data-photo-row', id);
        expandRow.className = 'hidden bg-blue-50/40 border-b border-gray-50';
        expandRow.innerHTML = `
            <td colspan="7" class="px-6 py-4">
                <div class="flex items-center gap-4">
                    <img id="preview-${id}" src="${image}" alt=""
                        class="${image ? '' : 'hidden'} h-14 w-20 object-cover rounded-xl border border-gray-100 shrink-0">
                    <div id="noimg-${id}" class="${image ? 'hidden' : 'flex'} items-center justify-center h-14 w-20 rounded-xl bg-white border border-dashed border-gray-200 shrink-0">
                        <span class="text-[9px] font-bold text-gray-300 uppercase tracking-widest">No Image</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Upload Foto — ${kode}</p>
                        <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-white border border-dashed border-gray-300 rounded-xl hover:border-[#5EEAD4] hover:bg-teal-50 transition-all">
                            <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest" id="label-${id}">
                                ${image ? 'Ganti Foto' : 'Pilih Foto'}
                            </span>
                            <input type="file" name="images[${id}]" accept="image/jpg,image/jpeg,image/png" class="hidden"
                                onchange="previewImage(this, '${id}')">
                        </label>
                    </div>
                    <button type="button" onclick="togglePhotoRow('${id}')"
                        class="text-[10px] font-black text-blue-400 hover:text-blue-600 uppercase tracking-widest transition-all shrink-0">
                        Tutup ↑
                    </button>
                </div>
            </td>`;

        allRows.push({
            main: row,
            expand: expandRow
        });
        itemCount++;
        updateCounter();

        currentPage = Math.ceil(allRows.length / PER_PAGE);
        renderPage();
    }

    function togglePhotoRow(id) {
        const expandRow = document.querySelector(`tr[data-photo-row="${id}"]`);
        if (!expandRow) return;
        expandRow.classList.toggle('hidden');
    }

    function renderPage() {
        const tableBody = document.getElementById('editTableBody');
        tableBody.innerHTML = '';

        if (allRows.length === 0) {
            tableBody.innerHTML = `
                <tr id="emptyRow">
                    <td colspan="7" class="px-8 py-20 text-center opacity-20 text-[10px] font-black uppercase tracking-[0.2em]">
                        Belum ada barang dipilih — pilih dari dropdown di atas
                    </td>
                </tr>`;
            document.getElementById('paginationWrapper').classList.add('hidden');
            document.getElementById('paginationWrapper').classList.remove('flex');
            return;
        }

        const start = (currentPage - 1) * PER_PAGE;
        const end = Math.min(start + PER_PAGE, allRows.length);

        for (let i = start; i < end; i++) {
            tableBody.appendChild(allRows[i].main);
            tableBody.appendChild(allRows[i].expand);
        }

        renderPagination(start + 1, end);
    }

    function renderPagination(from, to) {
        const wrapper = document.getElementById('paginationWrapper');
        const info = document.getElementById('paginationInfo');
        const controls = document.getElementById('paginationControls');
        const total = allRows.length;
        const lastPage = Math.ceil(total / PER_PAGE);

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

        const pages = buildPageList(currentPage, lastPage);
        let prevP = null;
        pages.forEach(page => {
            if (prevP !== null && page - prevP > 1) {
                const dots = document.createElement('span');
                dots.textContent = '…';
                dots.className = 'text-gray-300 font-black text-xs';
                controls.appendChild(dots);
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
            prevP = page;
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

    function previewImage(input, id) {
        const file = input.files[0];
        if (!file) return;
        const label = document.getElementById(`label-${id}`);
        const preview = document.getElementById(`preview-${id}`);
        const noimg = document.getElementById(`noimg-${id}`);
        label.textContent = file.name.length > 16 ? file.name.substring(0, 16) + '...' : file.name;
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            noimg.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }

    function removeRow(btn, id, kode, nama, satuan, alamat, min, max, image) {
        allRows = allRows.filter(r => r.main !== btn.closest('tr'));
        document.querySelector(`tr[data-photo-row="${id}"]`)?.remove();
        btn.closest('tr').remove();

        editedIds.delete(id);
        itemCount--;
        updateCounter();

        tsEdit.addOption({
            value: id,
            text: `${kode} | ${nama}`
        });
        tsEdit.refreshOptions(false);

        const lastPage = Math.ceil(allRows.length / PER_PAGE) || 1;
        if (currentPage > lastPage) currentPage = lastPage;

        renderPage();
    }

    function updateCounter() {
        document.getElementById('itemCount').innerText = itemCount + ' Items';
        document.getElementById('submitBtn').disabled = itemCount === 0;
    }
</script>
@endsection