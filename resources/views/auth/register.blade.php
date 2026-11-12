<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create an account</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 sm:p-8">

<div class="w-full max-w-6xl bg-white rounded-[40px] p-6 sm:p-10 grid lg:grid-cols-2 gap-10 items-center">

    {{-- ====== FORM REGISTER ====== --}}
    <div class="flex flex-col items-center px-2 sm:px-8 py-6">
        <div class="w-full max-w-md">
            <h1 class="text-4xl font-extrabold text-center text-gray-900">Create an account</h1>
            <p class="mt-4 text-center text-gray-500">Sign up with your valid email and password.</p>

            <form method="POST" action="{{ route('register') }}" class="mt-10 space-y-4">
                @csrf

                {{-- Username (kolom "name" bawaan Breeze) --}}
                <div>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           placeholder="Username"
                           class="w-full rounded-full border border-gray-300 px-6 py-4 text-gray-700 placeholder-gray-400 focus:border-gray-900 focus:ring-0">
                    @error('name')<p class="mt-1 ml-4 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           placeholder="Email"
                           class="w-full rounded-full border border-gray-300 px-6 py-4 text-gray-700 placeholder-gray-400 focus:border-gray-900 focus:ring-0">
                    @error('email')<p class="mt-1 ml-4 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Password + toggle (Alpine.js sudah termasuk di Breeze) --}}
                <div x-data="{ show: false }">
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password"
                               placeholder="Password"
                               class="w-full rounded-full border border-gray-300 px-6 py-4 pr-14 text-gray-700 placeholder-gray-400 focus:border-gray-900 focus:ring-0">
                        <button type="button" @click="show = !show"
                                class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8M9.9 5.1A9.8 9.8 0 0112 5c5 0 9 4.5 10 7-.4 1-1.3 2.3-2.6 3.5M6.6 6.6C4.4 8 2.8 10.2 2 12c1 2.5 5 7 10 7 1.5 0 2.9-.4 4.1-1"/>
                            </svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M2 12c1-2.5 5-7 10-7s9 4.5 10 7c-1 2.5-5 7-10 7S3 14.5 2 12z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')<p class="mt-1 ml-4 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div x-data="{ show: false }">
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                               placeholder="Confirm Password"
                               class="w-full rounded-full border border-gray-300 px-6 py-4 pr-14 text-gray-700 placeholder-gray-400 focus:border-gray-900 focus:ring-0">
                        <button type="button" @click="show = !show"
                                class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8M9.9 5.1A9.8 9.8 0 0112 5c5 0 9 4.5 10 7-.4 1-1.3 2.3-2.6 3.5M6.6 6.6C4.4 8 2.8 10.2 2 12c1 2.5 5 7 10 7 1.5 0 2.9-.4 4.1-1"/>
                            </svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M2 12c1-2.5 5-7 10-7s9 4.5 10 7c-1 2.5-5 7-10 7S3 14.5 2 12z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit"
                        class="w-full rounded-full bg-gray-900 py-4 text-lg font-semibold text-white hover:bg-black transition mt-4">
                    Sign Up
                </button>

                {{-- Divider --}}
                <div class="flex items-center gap-4 py-2">
                    <div class="flex-1 h-px bg-gray-300"></div>
                    <span class="text-lg text-gray-600">or sign up with</span>
                    <div class="flex-1 h-px bg-gray-300"></div>
                </div>

                {{-- Ganti '#' dengan route Socialite jika sudah dipasang --}}
                <a href="#"
                   class="flex items-center justify-center gap-3 w-full rounded-full border border-gray-300 py-4 text-sm font-medium text-gray-900 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5" viewBox="0 0 48 48">
                        <path fill="#EA4335" d="M24 9.5c3.5 0 6.6 1.2 9.1 3.6l6.8-6.8C35.8 2.4 30.3 0 24 0 14.6 0 6.5 5.4 2.6 13.2l7.9 6.1C12.4 13.5 17.7 9.5 24 9.5z"/>
                        <path fill="#4285F4" d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v9h12.7c-.6 3-2.3 5.5-4.8 7.2l7.5 5.8c4.4-4.1 7.1-10.1 7.1-17.5z"/>
                        <path fill="#FBBC05" d="M10.5 28.7c-.5-1.5-.8-3-.8-4.7s.3-3.2.8-4.7l-7.9-6.1C.9 16.4 0 20.1 0 24s.9 7.6 2.6 10.8l7.9-6.1z"/>
                        <path fill="#34A853" d="M24 48c6.5 0 11.9-2.1 15.9-5.8l-7.5-5.8c-2.1 1.4-4.9 2.3-8.4 2.3-6.3 0-11.6-4-13.5-9.8l-7.9 6.1C6.5 42.6 14.6 48 24 48z"/>
                    </svg>
                    Sign up with Google
                </a>

                <a href="#"
                   class="flex items-center justify-center gap-3 w-full rounded-full border border-gray-300 py-4 text-sm font-medium text-gray-900 hover:bg-gray-50 transition">
                    <svg class="w-6 h-6" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="12" fill="#1877F2"/>
                        <path fill="#fff" d="M16.7 15.5l.5-3.5h-3.3V9.8c0-1 .5-1.9 2-1.9h1.5V4.9s-1.4-.2-2.7-.2c-2.7 0-4.5 1.7-4.5 4.7V12H7.2v3.5h3V24h3.7v-8.5h2.8z"/>
                    </svg>
                    Sign up with Facebook
                </a>
            </form>

            <p class="mt-14 text-center text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="font-bold text-gray-900 hover:underline">Login</a>
            </p>
        </div>
    </div>

    {{-- ====== GAMBAR KANAN (letakkan di public/images/salad.jpg) ====== --}}
    <div class="hidden lg:block relative h-[640px] rounded-3xl overflow-hidden">
        <img src="{{ asset('images/salad.jpg') }}" alt="Salad" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-gray-400/80"></span>
            <span class="w-9 h-3 rounded-full bg-gray-200"></span>
            <span class="w-3 h-3 rounded-full bg-gray-400/80"></span>
        </div>
    </div>
</div>

<style>[x-cloak]{display:none !important;}</style>
</body>
</html>