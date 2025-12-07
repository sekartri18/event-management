<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Daftar Akun - {{ config('app.name', 'EventPro') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-white">
    
    <div class="min-h-screen flex">
        
        {{-- BAGIAN KIRI: GAMBAR (Hanya tampil di Desktop) --}}
        <div class="hidden lg:flex lg:w-1/2 relative bg-indigo-900 overflow-hidden">
            {{-- Pastikan gambar tersedia di public/images/eventpro.jpg --}}
            <img src="{{ asset('images/eventpro.jpg') }}" 
                 alt="Event Background" 
                 class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay">
            
            <div class="relative z-10 w-full flex flex-col justify-center px-12 text-white">
                <div class="mb-6">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h1 class="text-5xl font-extrabold mb-4 leading-tight">Bergabunglah <br>Bersama Kami.</h1>
                <p class="text-lg text-indigo-100 max-w-md">Mulai perjalanan Anda dalam membuat event yang luar biasa atau temukan pengalaman baru hari ini.</p>
            </div>
            
            {{-- Hiasan Dekoratif --}}
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-indigo-600 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
        </div>

        {{-- BAGIAN KANAN: FORM REGISTER --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50 overflow-y-auto">
            <div class="w-full max-w-md">
                
                {{-- HEADER: JUDUL RATA TENGAH --}}
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Buat Akun Baru 🚀</h2>
                    <p class="mt-2 text-sm text-gray-600">Lengkapi data diri Anda untuk mendaftar.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    {{-- 1. Nama Lengkap --}}
                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap')" class="text-gray-700 font-semibold" />
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </span>
                            <x-text-input id="name" class="block mt-1 w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-3" 
                                          type="text" name="name" :value="old('name')" required autofocus autocomplete="name" 
                                          placeholder="Nama Anda" />
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    {{-- 2. Email --}}
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-semibold" />
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </span>
                            <x-text-input id="email" class="block mt-1 w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-3" 
                                          type="email" name="email" :value="old('email')" required autocomplete="username" 
                                          placeholder="nama@email.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- 3. Nomor HP (DITAMBAHKAN KEMBALI) --}}
                    <div>
                        <x-input-label for="phone" :value="__('Nomor HP')" class="text-gray-700 font-semibold" />
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </span>
                            <x-text-input id="phone" class="block mt-1 w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-3" 
                                          type="text" name="phone" :value="old('phone')" required 
                                          placeholder="0812xxxx" />
                        </div>
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    {{-- 4. Role (DITAMBAHKAN KEMBALI) --}}
                    <div>
                        <x-input-label for="role" :value="__('Daftar Sebagai')" class="text-gray-700 font-semibold" />
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </span>
                            <select id="role" name="role" class="block w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-3 appearance-none bg-white" required>
                                <option value="" disabled selected>-- Pilih Peran Anda --</option>
                                <option value="attendee" {{ old('role') == 'attendee' ? 'selected' : '' }}>Attendee (Peserta)</option>
                                <option value="organizer" {{ old('role') == 'organizer' ? 'selected' : '' }}>Organizer (Penyelenggara)</option>
                            </select>
                            {{-- Panah Dropdown Custom --}}
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    {{-- 5. Password --}}
                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold" />
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </span>
                            <x-text-input id="password" class="block mt-1 w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-3" 
                                          type="password" name="password" required autocomplete="new-password" 
                                          placeholder="••••••••" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- 6. Konfirmasi Password --}}
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-gray-700 font-semibold" />
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                            <x-text-input id="password_confirmation" class="block mt-1 w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-3" 
                                          type="password" name="password_confirmation" required autocomplete="new-password" 
                                          placeholder="••••••••" />
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 transform hover:scale-[1.02]">
                            {{ __('Daftar Sekarang') }}
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-500 transition">
                            Masuk di sini
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