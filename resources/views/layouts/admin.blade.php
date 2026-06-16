<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - SIBAIM</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* ── TOM SELECT OVERRIDE ── */
        .ts-wrapper {
            width: 100%;
        }

        .ts-control {
            padding: 1rem 1.25rem !important;
            background-color: #fff !important;
            border: 1px solid #f3f4f6 !important;
            border-radius: 1rem !important;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05) !important;
            font-weight: 700 !important;
            font-size: 0.875rem !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            cursor: pointer !important;
            outline: none !important;
            transition: all 0.15s !important;
            min-height: unset !important;
        }

        .ts-wrapper.focus .ts-control {
            border-color: transparent !important;
            box-shadow: 0 0 0 2px #5EEAD4 !important;
        }

        .ts-control input {
            font-weight: 700 !important;
            font-size: 0.875rem !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            color: #374151 !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .ts-control input::placeholder {
            color: #d1d5db !important;
            font-weight: 400 !important;
        }

        .ts-dropdown {
            border: 1px solid #f3f4f6 !important;
            border-radius: 1rem !important;
            box-shadow: 0 4px 24px 0 rgb(0 0 0 / 0.07) !important;
            font-size: 0.875rem !important;
            font-weight: 700 !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            overflow: hidden !important;
            margin-top: 4px !important;
        }

        .ts-dropdown .option {
            padding: 0.75rem 1.25rem !important;
            color: #374151 !important;
            cursor: pointer !important;
        }

        .ts-dropdown .option:hover,
        .ts-dropdown .option.active {
            background-color: #f0fdfb !important;
            color: #1E4D9C !important;
        }

        .ts-dropdown .option.selected {
            background-color: #5EEAD4 !important;
            color: #1E4D9C !important;
        }

        .ts-wrapper.single .ts-control::after {
            display: none !important;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">

        <aside class="hidden md:flex md:flex-shrink-0 w-64 bg-[#1E4D9C] flex-col shadow-xl">
            <div class="flex items-center gap-3 px-6 py-8 border-b border-white/10">
                <div class="p-2 bg-white/10 rounded-lg">
                    <svg class="w-7 h-7 text-[#5EEAD4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-extrabold tracking-tighter text-white uppercase">si<span class="text-[#5EEAD4]">Baim</span></span>
                    <span class="text-[10px] text-blue-200 -mt-1 uppercase tracking-widest font-bold">sistem in out barang</span>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto hide-scrollbar">

                {{-- MAIN MENU --}}
                <div class="pb-2">
                    <p class="px-4 text-[10px] font-bold text-blue-300/60 uppercase tracking-[0.2em] mb-2">Main Menu</p>

                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 group
                        {{ request()->routeIs('admin.dashboard') ? 'bg-[#5EEAD4] text-[#1E4D9C] font-bold shadow-lg' : 'text-blue-100 hover:bg-white/10' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-[#1E4D9C]' : 'text-blue-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="text-sm">Dashboard</span>
                    </a>
                </div>

                {{-- MANAGEMENT --}}
                <div class="pt-4 pb-2 mt-4 border-t border-white/5">
                    <p class="px-4 text-[10px] font-bold text-blue-300/60 uppercase tracking-[0.2em] mb-2">Management</p>

                    <a href="{{ route('admin.inventory.index') }}"
                        class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 group
        {{ request()->routeIs('admin.inventory.*') ? 'bg-[#5EEAD4] text-[#1E4D9C] font-bold shadow-lg' : 'text-blue-100 hover:bg-white/10' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.inventory.*') ? 'text-[#1E4D9C]' : 'text-blue-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span class="text-sm">Inventory</span>
                    </a>

                    {{-- Kelola Barang (Operasional) --}}
                    <div x-data="{ open: {{ request()->routeIs('admin.kelola_barang.create', 'admin.kelola_barang.stok', 'admin.kelola_barang.pindah', 'admin.kelola_barang.keluar', 'admin.kelola_barang.sto') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 group mt-1
            {{ request()->routeIs('admin.kelola_barang.create', 'admin.kelola_barang.stok', 'admin.kelola_barang.pindah', 'admin.kelola_barang.keluar', 'admin.kelola_barang.sto') ? 'bg-[#5EEAD4] text-[#1E4D9C] font-bold shadow-lg' : 'text-blue-100 hover:bg-white/10' }}">
                            <div class="flex items-center gap-4">
                                {{-- Icon: Shelves/Grid --}}
                                <svg class="w-5 h-5 {{ request()->routeIs('admin.kelola_barang.create', 'admin.kelola_barang.stok', 'admin.kelola_barang.pindah', 'admin.kelola_barang.keluar', 'admin.kelola_barang.sto') ? 'text-[#1E4D9C]' : 'text-blue-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                <span class="text-sm">Kelola Barang</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition class="mt-1 ml-4 pl-4 border-l border-white/10 space-y-1">
                            <a href="{{ route('admin.kelola_barang.create') }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200
                {{ request()->routeIs('admin.kelola_barang.create') ? 'bg-white/20 text-white font-bold' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Barang
                            </a>

                            <a href="{{ route('admin.kelola_barang.edit') }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200
                {{ request()->routeIs('admin.kelola_barang.edit') ? 'bg-white/20 text-white font-bold' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Barang
                            </a>

                            <a href="{{ route('admin.kelola_barang.stok') }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200
                {{ request()->routeIs('admin.kelola_barang.stok') ? 'bg-white/20 text-white font-bold' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Update Stock
                            </a>

                            <a href="{{ route('admin.kelola_barang.pindah') }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200
                {{ request()->routeIs('admin.kelola_barang.pindah') ? 'bg-white/20 text-white font-bold' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Pindah Rak
                            </a>

                            <a href="{{ route('admin.kelola_barang.keluar') }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200
                {{ request()->routeIs('admin.kelola_barang.keluar') ? 'bg-white/20 text-white font-bold' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H3m12 0l-4-4m4 4l-4 4" />
                                </svg>
                                Barang Keluar
                            </a>

                            <a href="{{ route('admin.kelola_barang.sto') }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200
                {{ request()->routeIs('admin.kelola_barang.sto') ? 'bg-white/20 text-white font-bold' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                Stock Take Over
                            </a>
                        </div>
                    </div>

                    {{-- Riwayat Barang (dengan submenu) --}}
                    <div x-data="{ open: {{ request()->routeIs('admin.history.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 group mt-1
        {{ request()->routeIs('admin.history.*') ? 'bg-[#5EEAD4] text-[#1E4D9C] font-bold shadow-lg' : 'text-blue-100 hover:bg-white/10' }}">
                            <div class="flex items-center gap-4">
                                <svg class="w-5 h-5 {{ request()->routeIs('admin.history.*') ? 'text-[#1E4D9C]' : 'text-blue-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm">Riwayat Barang</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition class="mt-1 ml-4 pl-4 border-l border-white/10 space-y-1">
                            <a href="{{ route('admin.history.in.index') }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200
            {{ request()->routeIs('admin.history.in.*') ? 'bg-white/20 text-white font-bold' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7-7l7 7-7 7" />
                                </svg>
                                Riwayat Masuk
                            </a>

                            <a href="{{ route('admin.history.out.index') }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200
            {{ request()->routeIs('admin.history.out.*') ? 'bg-white/20 text-white font-bold' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H3m12 0l-4-4m4 4l-4 4" />
                                </svg>
                                Riwayat Keluar
                            </a>

                            <a href="{{ route('admin.history.move.index') }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200
            {{ request()->routeIs('admin.history.move.*') ? 'bg-white/20 text-white font-bold' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                                Riwayat Move
                            </a>

                            <a href="{{ route('admin.history.sto.index') }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200
            {{ request()->routeIs('admin.history.sto.*') ? 'bg-white/20 text-white font-bold' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                Riwayat STO
                            </a>

                            <a href="{{ route('admin.history.permintaan.index') }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200
            {{ request()->routeIs('admin.history.permintaan.*') ? 'bg-white/20 text-white font-bold' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Riwayat Permintaan
                            </a>
                        </div>
                    </div>

                    <div>
                        <a href="{{ route('admin.laporan.pending_permintaan') }}"
                            class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 group mt-1
                        {{ request()->routeIs('admin.laporan.pending_permintaan') ? 'bg-[#5EEAD4] text-[#1E4D9C] font-bold shadow-lg' : 'text-blue-100 hover:bg-white/10' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.laporan.pending_permintaan') ? 'text-[#1E4D9C]' : 'text-blue-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                            </svg>
                            <span class="text-sm">Pending Permintaan</span>
                        </a>
                    </div>

                    <div>
                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 group
                        {{ request()->routeIs('admin.users.*') ? 'bg-[#5EEAD4] text-[#1E4D9C] font-bold shadow-lg' : 'text-blue-100 hover:bg-white/10' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('admin.users.*') ? 'text-[#1E4D9C]' : 'text-blue-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-sm">Kelola User</span>
                        </a>
                    </div>
                </div>
            </nav>

            {{-- USER INFO --}}
            <div class="p-4 border-t border-white/10 bg-black/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#FBBF24] flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0 text-white">
                        <p class="text-sm font-bold truncate">{{ auth()->user()->username }}</p>
                        <p class="text-[10px] opacity-60 uppercase">{{ auth()->user()->role }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-2 text-blue-300 hover:text-red-400 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

            <header class="bg-white border-b border-gray-200 z-10">
                <div class="flex items-center justify-between px-8 py-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">@yield('title')</h2>
                        <p class="text-xs text-gray-500">@yield('subtitle', 'Warehouse Management System')</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="text-sm text-gray-500 font-medium">
                            {{ now()->translatedFormat('l, d F Y') }}
                        </div>

                        {{-- Notifikasi --}}
                        <div class="relative" id="notifWrapper">
                            @php
                            $cacheKey = 'notif_' . auth()->user()->role . '_' . auth()->id();
                            $notifData = cache()->remember($cacheKey, now()->addMinutes(3), function () {
                            $role = auth()->user()->role;
                            return [
                            'unread' => \App\Models\Notifikasi::whereJsonContains('for_roles', $role)
                            ->where('is_read', false)->count(),
                            'items' => \App\Models\Notifikasi::whereJsonContains('for_roles', $role)
                            ->latest()->limit(10)->get()->toArray(),
                            ];
                            });
                            $unread = $notifData['unread'];
                            $notifs = collect($notifData['items'])->map(fn($n) => (object) $n);
                            @endphp

                            <button onclick="toggleNotif(event)" class="relative p-2 text-gray-400 hover:text-gray-600 transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if($unread > 0)
                                <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center">
                                    {{ $unread > 9 ? '9+' : $unread }}
                                </span>
                                @endif
                            </button>

                            <div id="notifDropdown" class="hidden absolute right-0 top-12 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Notifikasi</p>
                                    <form action="{{ route('notifikasi.readAll') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-[10px] font-black text-[#1E4D9C] hover:text-[#5EEAD4] transition-all uppercase tracking-widest">
                                            Tandai Semua Dibaca
                                        </button>
                                    </form>
                                </div>
                                <div class="max-h-96 overflow-y-auto divide-y divide-gray-50">
                                    @forelse($notifs as $notif)
                                    <div class="px-5 py-4 hover:bg-gray-50 transition-all {{ $notif->is_read ? 'opacity-50' : '' }}">
                                        <div class="flex items-start gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-[#1E4D9C] flex items-center justify-center flex-shrink-0 mt-0.5">
                                                <svg class="w-4 h-4 text-[#5EEAD4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V11" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-[11px] font-black text-gray-800">{{ $notif->judul }}</p>
                                                <p class="text-[10px] text-gray-500 mt-0.5 break-words">{{ $notif->pesan }}</p>
                                                <p class="text-[9px] text-gray-300 mt-1 font-bold uppercase tracking-widest">
                                                    {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                                                </p>
                                            </div>
                                            @if(!$notif->is_read)
                                            <span class="w-2 h-2 bg-[#1E4D9C] rounded-full flex-shrink-0 mt-2"></span>
                                            @endif
                                        </div>
                                        @if($notif->permintaan_id)
                                        <a href="{{ route('notifikasi.detail', $notif->permintaan_id) }}"
                                            class="mt-2 ml-11 text-[9px] font-black text-[#1E4D9C] uppercase tracking-widest hover:text-[#5EEAD4] transition-all inline-block">
                                            Lihat Detail →
                                        </a>
                                        @endif
                                    </div>
                                    @empty
                                    <div class="px-5 py-10 text-center text-[10px] font-black text-gray-300 uppercase tracking-widest">
                                        Tidak ada notifikasi
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-8">
                @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    {{ is_string(session('success')) ? session('success') : 'Berhasil.' }}
                </div>
                @endif
                @if(session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                    {{ session('error') }}
                </div>
                @endif
                @if(session('warning'))
                <div class="mb-6 bg-amber-100 border-l-4 border-amber-500 text-amber-700 p-4 rounded shadow-sm">
                    {{ session('warning') }}
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

    <script>
        function toggleNotif(event) {
            event.stopPropagation();
            document.getElementById('notifDropdown').classList.toggle('hidden');
        }
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('notifWrapper');
            const dropdown = document.getElementById('notifDropdown');
            if (wrapper && !wrapper.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>

</html>