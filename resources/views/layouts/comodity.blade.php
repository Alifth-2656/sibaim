<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') -  SIBAIM</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

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
                    <span class="text-xl font-extrabold tracking-tighter text-white uppercase">Si<span class="text-[#5EEAD4]">BaIm</span></span>
                    <span class="text-[10px] text-blue-200 -mt-1 uppercase tracking-widest font-bold">Sistem In Out Barang</span>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto hide-scrollbar">

                <div class="pb-2">
                    <p class="px-4 text-[10px] font-bold text-blue-300/60 uppercase tracking-[0.2em] mb-2">Main Menu</p>

                    <a href="{{ route(auth()->user()->role . '.dashboard') }}"
                        class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 group
            {{ request()->routeIs('*.dashboard') ? 'bg-[#5EEAD4] text-[#1E4D9C] font-bold shadow-lg' : 'text-blue-100 hover:bg-white/10' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('*.dashboard') ? 'text-[#1E4D9C]' : 'text-blue-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="text-sm">Dashboard</span>
                    </a>
                </div>

                <div class="pt-4 pb-2 mt-4 border-t border-white/5">
                    <p class="px-4 text-[10px] font-bold text-blue-300/60 uppercase tracking-[0.2em] mb-2">Warehouse</p>

                    <a href="{{ route('comodity.data_barang.index') }}"
                        class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 group
            {{ request()->routeIs('comodity.data_barang.*') ? 'bg-[#5EEAD4] text-[#1E4D9C] font-bold shadow-lg' : 'text-blue-100 hover:bg-white/10' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('comodity.data_barang.*') ? 'text-[#1E4D9C]' : 'text-blue-300 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 11m8 4v10M4 11v10l8 4" />
                        </svg>
                        <span class="text-sm">Data Barang</span>
                    </a>
                </div>

                <div class="pt-4 pb-2 mt-4 border-t border-white/5">
                    <p class="px-4 text-[10px] font-bold text-blue-300/60 uppercase tracking-[0.2em] mb-2">Management</p>

                    <a href="{{ route('comodity.permintaan.index') }}"
                        class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-200 group
        {{ request()->routeIs('comodity.permintaan.*') ? 'bg-[#5EEAD4] text-[#1E4D9C] font-bold shadow-lg' : 'text-blue-100 hover:bg-white/10' }}">

                        <svg class="w-5 h-5 {{ request()->routeIs('comodity.permintaan.*') ? 'text-[#1E4D9C]' : 'text-blue-300 group-hover:text-white' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4m0 0l-4-4m4 4l-4 4m-5 15v-2a4 4 0 014-4h4m0 0l-4-4m4 4l-4 4" />
                        </svg>
                        <span class="text-sm">Permintaan</span>
                    </a>
                </div>

            </nav>


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
                        <button type="submit" class="p-2 text-blue-300 hover:text-red-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

            <header class="bg-white border-b border-gray-200 z-10">
                <div class="flex items-center justify-between px-8 py-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">@yield('title')</h2>
                        <p class="text-xs text-gray-500">@yield('subtitle', 'Warehouse Management System')</p>
                    </div>
                    <div class="text-sm text-gray-500 font-medium">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-8">
                @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                    {{ session('error') }}
                </div>
                @endif




                @yield('content')
            </main>

            @if(session('success'))
            <script>
                let data = @json(session('success'));

                let message = "STRUK PERMINTAAN\n\n";
                message += "PIC: " + data.pic + "\n";
                message += "Commodity: " + data.commodity + "\n\n";

                data.items.forEach(item => {
                    message += "- " + item + "\n";
                });

                alert(message);
            </script>
            @endif

        </div>
    </div>

    @stack('scripts')
</body>

</html>