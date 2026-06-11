@extends('layouts.admin')

@section('title', 'Kelola User')
@section('subtitle', 'Manajemen akun dan hak akses pengguna')

@section('content')
<div class="space-y-6">

    {{-- HEADER + TOMBOL TAMBAH --}}
    <div class="flex items-center justify-between">
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total {{ $users->total() }} user terdaftar</p>
        </div>
        <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
            class="flex items-center gap-2 bg-[#1E4D9C] text-white px-5 py-2.5 rounded-xl text-[11px] font-black uppercase tracking-widest hover:bg-[#5EEAD4] hover:text-[#1E4D9C] transition-all shadow-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
            </svg>
            Tambah User
        </button>
    </div>

    {{-- TABEL --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">No</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Username</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Role</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Dibuat</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition-all">
                    <td class="px-6 py-4 text-sm text-gray-400 font-bold">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#FBBF24] flex items-center justify-center text-white text-xs font-black">
                                {{ strtoupper(substr($user->username, 0, 1)) }}
                            </div>
                            <p class="text-sm font-black text-gray-800">{{ $user->username }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-[10px] font-black rounded-full uppercase tracking-widest
                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-600' : '' }}
                            {{ $user->role === 'comodity' ? 'bg-teal-100 text-teal-600' : '' }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-400">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            {{-- Edit --}}
                            <button onclick="openEdit({{ $user->id }}, '{{ $user->username }}', '{{ $user->role }}')"
                                class="p-2 text-blue-400 hover:text-[#1E4D9C] transition-all" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            {{-- Reset Password --}}
                            <button onclick="openReset({{ $user->id }}, '{{ $user->username }}')"
                                class="p-2 text-yellow-400 hover:text-yellow-600 transition-all" title="Reset Password">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </button>
                            {{-- Hapus --}}
                            @if($user->id !== auth()->id())
                            <button onclick="openHapus({{ $user->id }}, '{{ $user->username }}')"
                                class="p-2 text-red-300 hover:text-red-500 transition-all" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center text-[10px] font-black text-gray-300 uppercase tracking-widest">Belum ada user</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} user
            </p>
            <div class="flex items-center gap-2">
                @if($users->onFirstPage())
                <span class="px-3 py-1.5 text-[10px] font-black text-gray-300 cursor-not-allowed">← Prev</span>
                @else
                <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1.5 text-[10px] font-black text-[#1E4D9C] hover:text-[#5EEAD4] transition-all">← Prev</a>
                @endif
                @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="w-7 h-7 flex items-center justify-center rounded-lg text-[10px] font-black transition-all {{ $page == $users->currentPage() ? 'bg-[#1E4D9C] text-white' : 'text-gray-400 hover:bg-gray-100' }}">{{ $page }}</a>
                @endforeach
                @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1.5 text-[10px] font-black text-[#1E4D9C] hover:text-[#5EEAD4] transition-all">Next →</a>
                @else
                <span class="px-3 py-1.5 text-[10px] font-black text-gray-300 cursor-not-allowed">Next →</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div id="modalTambah" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8">
        <h3 class="text-lg font-black text-gray-800 uppercase tracking-tight mb-6">Tambah User Baru</h3>
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Username</label>
                <input type="text" name="username" required
                    class="w-full px-4 py-3 bg-gray-50 rounded-xl border-none outline-none focus:ring-2 focus:ring-[#5EEAD4] font-bold text-gray-700">
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 bg-gray-50 rounded-xl border-none outline-none focus:ring-2 focus:ring-[#5EEAD4] font-bold text-gray-700">
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Role</label>
                <select name="role" required class="w-full px-4 py-3 bg-gray-50 rounded-xl border-none outline-none focus:ring-2 focus:ring-[#5EEAD4] font-bold text-gray-700">
                    <option value="">-- Pilih Role --</option>
                    <option value="admin">Admin</option>
                    <option value="comodity">Comodity</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')"
                    class="flex-1 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-all">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-3 bg-[#1E4D9C] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-[#5EEAD4] hover:text-[#1E4D9C] transition-all">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
<div id="modalEdit" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8">
        <h3 class="text-lg font-black text-gray-800 uppercase tracking-tight mb-6">Edit User</h3>
        <form id="formEdit" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Username</label>
                <input type="text" name="username" id="editUsername" required
                    class="w-full px-4 py-3 bg-gray-50 rounded-xl border-none outline-none focus:ring-2 focus:ring-[#5EEAD4] font-bold text-gray-700">
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Role</label>
                <select name="role" id="editRole" required class="w-full px-4 py-3 bg-gray-50 rounded-xl border-none outline-none focus:ring-2 focus:ring-[#5EEAD4] font-bold text-gray-700">
                    <option value="admin">Admin</option>
                    <option value="comodity">Comodity</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')"
                    class="flex-1 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-all">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-3 bg-[#1E4D9C] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-[#5EEAD4] hover:text-[#1E4D9C] transition-all">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL RESET PASSWORD --}}
<div id="modalReset" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8">
        <h3 class="text-lg font-black text-gray-800 uppercase tracking-tight mb-1">Reset Password</h3>
        <p id="resetUsername" class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6"></p>
        <form id="formReset" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Password Baru</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 bg-gray-50 rounded-xl border-none outline-none focus:ring-2 focus:ring-[#5EEAD4] font-bold text-gray-700">
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-3 bg-gray-50 rounded-xl border-none outline-none focus:ring-2 focus:ring-[#5EEAD4] font-bold text-gray-700">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modalReset').classList.add('hidden')"
                    class="flex-1 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-all">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-3 bg-yellow-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-yellow-600 transition-all">
                    Reset
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL HAPUS --}}
<div id="modalHapus" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 text-center">
        <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </div>
        <h3 class="text-lg font-black text-gray-800 uppercase tracking-tight mb-1">Hapus User?</h3>
        <p id="hapusUsername" class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6"></p>
        <form id="formHapus" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('modalHapus').classList.add('hidden')"
                    class="flex-1 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-all">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-3 bg-red-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-600 transition-all">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEdit(id, username, role) {
        document.getElementById('formEdit').action = `/admin/users/${id}`;
        document.getElementById('editUsername').value = username;
        document.getElementById('editRole').value = role;
        document.getElementById('modalEdit').classList.remove('hidden');
    }

    function openReset(id, username) {
        document.getElementById('formReset').action = `/admin/users/${id}/reset-password`;
        document.getElementById('resetUsername').innerText = username;
        document.getElementById('modalReset').classList.remove('hidden');
    }

    function openHapus(id, username) {
        document.getElementById('formHapus').action = `/admin/users/${id}`;
        document.getElementById('hapusUsername').innerText = username;
        document.getElementById('modalHapus').classList.remove('hidden');
    }

    // Tutup modal kalau klik backdrop
    ['modalTambah', 'modalEdit', 'modalReset', 'modalHapus'].forEach(id => {
        document.getElementById(id).addEventListener('click', function(e) {
            if (e.target === this) this.classList.add('hidden');
        });
    });
</script>
@endpush

@endsection