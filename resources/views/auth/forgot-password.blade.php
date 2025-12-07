<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Lupa Password - {{ config('app.name', 'EventPro') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-white">
    
    <div class="min-h-screen flex">
        
        {{-- BAGIAN KIRI: GAMBAR --}}
        <div class="hidden lg:flex lg:w-1/2 relative bg-indigo-900 overflow-hidden">
            <img src="{{ asset('images/eventpro.jpg') }}" 
                 alt="Event Background" 
                 class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay">
            
            <div class="relative z-10 w-full flex flex-col justify-center px-12 text-white">
                <div class="mb-6">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h1 class="text-5xl font-extrabold mb-4 leading-tight">Keamanan <br>Prioritas Kami.</h1>
                <p class="text-lg text-indigo-100 max-w-md">Kami membantu mengamankan akun Anda. Ikuti langkah mudah untuk memulihkan akses.</p>
            </div>
            
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-indigo-600 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
        </div>

        {{-- BAGIAN KANAN: FORM FORGOT PASSWORD --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
            <div class="w-full max-w-md">
                
                <div class="text-center lg:text-left mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Lupa Password? 🔒</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Jangan khawatir. Masukkan email Anda dan kami akan mengirimkan link untuk mereset password.
                    </p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    {{-- Email Address --}}
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-semibold" />
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </span>
                            <x-text-input id="email" class="block mt-1 w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-3" 
                                          type="email" name="email" :value="old('email')" required autofocus 
                                          placeholder="nama@email.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 transform hover:scale-[1.02]">
                            {{ __('Kirim Link Reset Password') }}
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-600">
                        Ingat password Anda? 
                        <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-500 transition">
                            Kembali ke Login
                        </a>
                    </p>
                </div>

                <div class="mt-8 border-t border-gray-200 pt-6 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} {{ config('app.name', 'EventPro') }}. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</body>
</html>