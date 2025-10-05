<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }} - Platform Manajemen Event Digital</title>

        <!-- Fonts: Menggunakan Inter untuk tampilan modern -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Custom utility for Hero Background effect */
            .hero-bg {
                background-image: url('https://i.pinimg.com/1200x/f3/b2/3b/f3b23b82a1a536807aab03334ea61b75.jpg');
                background-size: cover;
                background-position: center;}
        </style>
    </head>
    
    <body class="font-sans antialiased bg-gray-50 text-gray-800">
        <div class="min-h-screen">
            
            {{-- Navigation Bar - Fixed on Top --}}
            <nav class="bg-transparent absolute top-0 left-0 w-full z-10 p-4">
                <div class="max-w-7xl mx-auto flex justify-between items-center">
                    <!-- Logo / Menu -->
                    <div class="flex items-center space-x-6">
                        <a href="{{ url('/') }}" class="text-3xl font-extrabold text-white">
                            Event<span class="text-indigo-400">Pro</span>
                        </a>
                    </div>

                    {{-- Auth Links --}}
                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="font-semibold text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg transition shadow-md">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="font-semibold text-white hover:text-indigo-300 px-3 py-2 transition">
                                    Log in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="font-semibold text-white bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg transition shadow-md">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </nav>

            <main>
                {{-- 1. HERO SECTION (Main Value Proposition) --}}
                <div class="hero-bg relative h-[80vh] min-h-[500px] flex items-center justify-center text-center">
                    {{-- Overlay Hitam --}}
                    <div class="absolute inset-0 bg-black opacity-70"></div>
                    
                    <div class="relative z-10 text-white p-6 max-w-4xl">
                        <h2 class="text-sm font-semibold uppercase tracking-widest text-indigo-400 mb-4">PLATFORM MANAJEMEN EVENT DIGITAL</h2>
                        <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight mb-8">
                            Kelola Event Anda dari Awal Sampai Akhir, <span class="text-purple-400">Tanpa Batas.</span>
                        </h1>
                        <p class="text-lg text-gray-200 mb-10">
                            Mudahkan perencanaan, pendaftaran, dan pemantauan event dengan sistem terintegrasi yang dirancang untuk Organizer profesional.
                        </p>
                        <a href="{{ route('register') }}" 
                           class="inline-block bg-purple-600 text-white text-xl font-bold px-10 py-4 rounded-xl hover:bg-purple-700 transition shadow-xl ring-4 ring-purple-400/50">
                            Mulai Kelola Event Anda
                        </a>
                    </div>
                </div>

                {{-- 2. FEATURE HIGHLIGHTS SECTION (Based on implemented system logic: CRUD & Roles) --}}
                <section class="py-16 md:py-24 bg-white">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="mx-auto max-w-2xl lg:text-center">
                            <h2 class="text-base font-semibold leading-7 text-indigo-600">FITUR UTAMA</h2>
                            <p class="mt-2 text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                                Semua Alat yang Dibutuhkan Organizer
                            </p>
                            <p class="mt-6 text-lg leading-8 text-gray-600">
                                EventPro menyediakan fondasi yang kuat untuk mengoperasikan event Anda dengan efisiensi dan kontrol penuh.
                            </p>
                        </div>
                        <div class="mx-auto mt-16 max-w-2xl lg:mt-20 lg:max-w-none">
                            <dl class="grid grid-cols-1 gap-x-8 gap-y-16 text-center md:grid-cols-3">
                                <div class="p-6 bg-gray-100 rounded-xl shadow-lg border-t-4 border-indigo-500">
                                    <div class="flex justify-center mb-4">
                                        <span class="text-4xl text-indigo-600">📝</span>
                                    </div>
                                    <dt class="text-xl font-bold leading-7 text-gray-900">CRUD Event Lengkap</dt>
                                    <dd class="mt-2 text-base leading-7 text-gray-600">
                                        Buat, Tampilkan, Ubah, dan Hapus detail event Anda. Kontrol penuh atas deskripsi, lokasi, dan tanggal acara.
                                    </dd>
                                </div>
                                <div class="p-6 bg-gray-100 rounded-xl shadow-lg border-t-4 border-purple-500">
                                    <div class="flex justify-center mb-4">
                                        <span class="text-4xl text-purple-600">🛡️</span>
                                    </div>
                                    <dt class="text-xl font-bold leading-7 text-gray-900">Otorisasi Berbasis Peran</dt>
                                    <dd class="mt-2 text-base leading-7 text-gray-600">
                                        Sistem membedakan akses antara **Admin**, **Organizer**, dan **Attendee** untuk keamanan dan kontrol data yang ketat.
                                    </dd>
                                </div>
                                <div class="p-6 bg-gray-100 rounded-xl shadow-lg border-t-4 border-indigo-500">
                                    <div class="flex justify-center mb-4">
                                        <span class="text-4xl text-indigo-600">📱</span>
                                    </div>
                                    <dt class="text-xl font-bold leading-7 text-gray-900">Akses Responsif</dt>
                                    <dd class="mt-2 text-base leading-7 text-gray-600">
                                        Kelola event Anda di mana saja. Antarmuka yang dioptimalkan untuk pengalaman desktop dan mobile yang lancar.
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </section>
                
                {{-- 3. FINAL CTA SECTION (Call to Action to start/login) --}}
                <section class="py-16 bg-indigo-600">
                    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
                        <h3 class="text-3xl font-extrabold mb-4">Siap Mengorganisir Event Hebat Berikutnya?</h3>
                        <p class="text-lg mb-8 opacity-90">
                            Bergabunglah dengan Organizer lain yang sudah memanfaatkan kemudahan EventPro.
                        </p>
                        <div class="flex justify-center space-x-4">
                            <a href="{{ route('register') }}" 
                               class="rounded-lg bg-white px-8 py-3 text-lg font-bold text-indigo-600 shadow-xl hover:bg-gray-100 transition">
                                Daftar Sebagai Organizer
                            </a>
                            {{-- Mengarahkan ke list event agar Attendee bisa melihat-lihat --}}
                            <a href="{{ route('events.index') }}" 
                               class="rounded-lg border-2 border-white px-8 py-3 text-lg font-bold text-white hover:bg-white hover:text-indigo-600 transition">
                                Lihat Semua Event &rarr;
                            </a>
                        </div>
                    </div>
                </section>
                
            </main>

            {{-- Footer --}}
            <footer class="bg-gray-800 text-white py-8">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm">
                    &copy; {{ date('Y') }} EventPro. All rights reserved. | Platform Manajemen Event.
                </div>
            </footer>
        </div>
    </body>
</html>
