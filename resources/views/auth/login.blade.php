<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - {{ config('app.name', 'EventPro') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-white">
    
    <div class="min-h-screen flex">
        
       {{-- BAGIAN KIRI: MODEL SEPERTI GAMBAR CONTOH --}}
<div class="hidden lg:flex lg:w-1/2 relative bg-[#3A63FF] overflow-hidden">
    
    {{-- Lingkaran Dekoratif --}}
    <div class="absolute top-10 left-10 w-40 h-40 bg-white/10 rounded-full"></div>
    <div class="absolute bottom-20 right-10 w-56 h-56 bg-white/10 rounded-full"></div>
    <div class="absolute bottom-0 left-1/3 w-72 h-72 bg-white/5 rounded-full blur-2xl"></div>

    {{-- Konten Welcome --}}
    <div class="relative z-10 flex flex-col justify-center px-12 text-white">
        
        {{-- Logo --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-white rounded-full"></div>
        </div>

        {{-- Text Headline --}}
        <h1 class="text-6xl font-extrabold leading-tight mb-4">
            Hello,<br> welcome!
        </h1>

        {{-- Subtext --}}
        <p class="text-lg text-white/80 max-w-sm">
            Rencanakan, kelola, dan nikmati event dengan lebih mudah.
        </p>

    </div>
</div>

        {{-- BAGIAN KANAN: FORM LOGIN --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
            <div class="w-full max-w-md">
                
                {{-- Header (Logo & Title) - DIPERBAIKI: Menghapus 'lg:text-left' agar selalu Rata Tengah --}}
                <div class="text-center mb-10">
                    <div class="flex justify-center mb-4">
                        <x-application-logo class="w-12 h-12 fill-current text-indigo-600" />
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">Selamat Datang Kembali! 👋</h2>
                    <p class="mt-2 text-sm text-gray-600">Masuk ke akun Anda untuk mulai mengelola event.</p>
                </div>

                {{-- Session Status --}}
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    {{-- Email Address --}}
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-semibold" />
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </span>
                            <x-text-input id="email" class="block mt-1 w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-3" 
                                          type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                                          placeholder="nama@email.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Password --}}
<div>
    <div class="flex justify-between items-center">
        <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold" />
    </div>

    <div class="relative mt-1">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </span>
        <x-text-input id="password"
            class="block mt-1 w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-3"
            type="password" name="password" required autocomplete="current-password"
            placeholder="••••••••" />
    </div>

    <x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>


                    {{-- Remember Me --}}
                    <div class="block">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-4 w-4" name="remember">
                            <span class="ml-2 text-sm text-gray-600">{{ __('Ingat Saya') }}</span>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <div>
                        <button type="submit" 
                                class="w-full justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-[#3A63FF] hover:bg-[#335bcc] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 transform hover:scale-[1.02]">
                            {{ __('Masuk Sekarang') }}
                        </button>
                    </div>
                </form>

                {{-- Register Link --}}
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-600">
                        Belum memiliki akun? 
                        <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-500 transition">
                            Daftar Gratis
                        </a>
                    </p>
                </div>

                {{-- Footer Text --}}
                <div class="mt-8 border-t border-gray-200 pt-6 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} {{ config('app.name', 'EventPro') }}. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</body>
</html>