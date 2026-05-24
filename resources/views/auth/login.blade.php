<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIBAIM</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/wh.png') }}?v=2">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
            url("{{ asset('images/wh.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>

<body class="gradient-bg min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-lg mb-4">
                <span class="text-4xl">📋</span>
            </div>
            <h1 class="text-3xl font-bold text-white">SIBAIM</h1>
            <p class="text-white/80 mt-2">Sistem In Out Barang Improvement</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-2 text-center">PT.SUAI</h2>
            <p class="text-gray-500 mb-6 text-center">Silakan login untuk melanjutkan</p>

            <!-- 🔴 ERROR GLOBAL -->
            @if ($errors->has('login'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm text-center">
                {{ $errors->first('login') }}
            </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST">
                @csrf

                <!-- Username -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <!-- icon -->
                        </span>
                        <input type="text" name="username" value="{{ old('username') }}"
                            class="w-full pl-4 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all outline-none @error('username') border-red-500 @enderror"
                            placeholder="Masukkan Username">
                    </div>

                    @error('username')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                            class="w-full pl-4 pr-12 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all outline-none @error('password') border-red-500 @enderror"
                            placeholder="Masukkan password">

                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            👁️
                        </button>
                    </div>

                    @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full bg-gradient-to-r from-orange-600 to-yellow-500 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    Masuk
                </button>
            </form>

            <!-- Quick Login Section -->
            <!-- Quick Login Section -->
            <div class="mt-8">
                <div class="relative flex items-center mb-4">
                    <div class="flex-grow border-t border-gray-200"></div>
                    <span class="mx-3 text-xs text-gray-400 font-medium tracking-wide">Demo Login Cepat</span>
                    <div class="flex-grow border-t border-gray-200"></div>
                </div>
                <div class="flex justify-center gap-3">
                    <button type="button"
                        onclick="quickLogin('comodity', '123')"
                        class="flex-1 py-2.5 bg-gradient-to-r from-blue-500 to-blue-400 text-white text-xs font-semibold rounded-xl shadow hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200">
                        comodity
                    </button>
                    <button type="button"
                        onclick="quickLogin('improvement', '123')"
                        class="flex-1 py-2.5 bg-gradient-to-r from-orange-600 to-yellow-500 text-white text-xs font-semibold rounded-xl shadow hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200">
                        improvement
                    </button>
                    <button type="button"
                        onclick="quickLogin('admin', '123')"
                        class="flex-1 py-2.5 bg-gradient-to-r from-purple-500 to-purple-400 text-white text-xs font-semibold rounded-xl shadow hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200">
                        admin
                    </button>
                </div>
            </div>
        </div>



        <!-- Footer -->
        <p class="text-center text-white/60 text-sm mt-8">
            &copy; {{ date('Y') }} SIBAIM. All rights reserved.
        </p>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';

        }

        function quickLogin(username, password) {
            document.querySelector('input[name="username"]').value = username;
            document.querySelector('input[name="password"]').value = password;
            document.querySelector('form').submit();
        }
    </script>

</body>

</html>