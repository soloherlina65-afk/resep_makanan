<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body{font-family:'Poppins',sans-serif;}</style>
</head>
<body class="bg-white min-h-screen overflow-x-hidden">
<div class="relative min-h-screen flex items-center">

    {{-- Logo kecil kiri atas --}}
    <div class="absolute top-5 left-5 w-4 h-4 rounded-full bg-orange-400"></div>

    {{-- ====== FORM LOGIN ====== --}}
    <div class="relative z-10 w-full lg:w-1/2 px-8 sm:px-16 lg:px-24 py-12">
        <div class="max-w-md mx-auto lg:mx-0">
            <h1 class="text-3xl font-bold text-gray-900">Login</h1>
            <p class="mt-3 text-sm text-gray-700">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-sky-500 font-medium hover:underline">Sign up</a>
            </p>

            <x-auth-session-status class="mt-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-4">
                @csrf

                <div>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="Email address"
                           class="w-full rounded-md border-0 bg-gray-50 px-5 py-4 text-sm text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-orange-400">
                    @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <input type="password" name="password" required autocomplete="current-password"
                           placeholder="Password"
                           class="w-full rounded-md border-0 bg-gray-50 px-5 py-4 text-sm text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-orange-400">
                    @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between text-sm text-gray-700 pt-1">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember"
                               class="rounded border-gray-300 text-orange-400 focus:ring-orange-400">
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="hover:text-orange-500">Forget Password?</a>
                    @endif
                </div>

                <button type="submit"
                        class="w-full rounded-md bg-orange-400 py-3.5 text-sm font-medium text-white hover:bg-orange-500 transition">
                    Log in
                </button>

                {{-- Ganti '#' dengan route Socialite jika sudah dipasang, mis. route('auth.google') --}}
                <a href="#"
                   class="flex items-center justify-center gap-3 w-full rounded-md border border-gray-200 bg-white py-3.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5" viewBox="0 0 48 48">
                        <path fill="#EA4335" d="M24 9.5c3.5 0 6.6 1.2 9.1 3.6l6.8-6.8C35.8 2.4 30.3 0 24 0 14.6 0 6.5 5.4 2.6 13.2l7.9 6.1C12.4 13.5 17.7 9.5 24 9.5z"/>
                        <path fill="#4285F4" d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v9h12.7c-.6 3-2.3 5.5-4.8 7.2l7.5 5.8c4.4-4.1 7.1-10.1 7.1-17.5z"/>
                        <path fill="#FBBC05" d="M10.5 28.7c-.5-1.5-.8-3-.8-4.7s.3-3.2.8-4.7l-7.9-6.1C.9 16.4 0 20.1 0 24s.9 7.6 2.6 10.8l7.9-6.1z"/>
                        <path fill="#34A853" d="M24 48c6.5 0 11.9-2.1 15.9-5.8l-7.5-5.8c-2.1 1.4-4.9 2.3-8.4 2.3-6.3 0-11.6-4-13.5-9.8l-7.9 6.1C6.5 42.6 14.6 48 24 48z"/>
                    </svg>
                    Log in with google
                </a>
            </form>
        </div>
    </div>

    {{-- ====== DEKORASI LINGKARAN + GAMBAR (kanan) ====== --}}
    <div class="hidden lg:block absolute top-1/2 -translate-y-1/2 -right-40 w-[820px] h-[820px]">
        {{-- Lingkaran oranye --}}
        <div class="absolute inset-0 rounded-full bg-orange-400"></div>
        {{-- Piring putih --}}
        <div class="absolute inset-[90px] rounded-full bg-gray-100 shadow-xl"></div>
        {{-- Gambar makanan (letakkan di public/images/pasta.png, sebaiknya PNG bulat/transparan) --}}
        <img src="{{ asset('images/pasta.png') }}" alt="Pasta"
             class="absolute inset-[130px] w-[560px] h-[560px] rounded-full object-cover">
    </div>
</div>
</body>
</html>