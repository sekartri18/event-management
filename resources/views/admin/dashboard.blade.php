<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard (Akses Penuh Sistem)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ALERT MODE ADMIN --}}
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 shadow-lg sm:rounded-lg mb-8" role="alert">
                <p class="font-bold text-lg">Mode Administrator Aktif</p>
                <p>Anda memiliki akses penuh untuk mengelola semua pengguna, event, dan konfigurasi sistem. Gunakan dengan bijak.</p>
            </div>
            
            {{-- =============================================== --}}
            {{-- KARTU STATISTIK UTAMA (Dynamic Data) --}}
            {{-- =============================================== --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3 mb-8">
                
                {{-- Kartu 1: Total Pengguna (DIPERBAIKI) --}}
                <div class="bg-white p-6 rounded-lg shadow-xl hover:shadow-2xl transition duration-300 border-b-4 border-indigo-600">
                    <div class="flex justify-between items-center">
                        <h4 class="text-base font-semibold text-gray-500 uppercase tracking-wider">Total Pengguna</h4>
                        <span class="text-3xl text-indigo-500">👥</span>
                    </div>
                    <p class="text-5xl text-gray-900 font-extrabold mt-2">
                        {{ number_format($totalUsers) }}
                    </p>
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 mt-3 block font-medium">
                        Kelola Pengguna &rarr;
                    </a>
                </div>
                
                {{-- Kartu 2: Total Event --}}
                <div class="bg-white p-6 rounded-lg shadow-xl hover:shadow-2xl transition duration-300 border-b-4 border-green-600">
                    <div class="flex justify-between items-center">
                        <h4 class="text-base font-semibold text-gray-500 uppercase tracking-wider">Total Event</h4>
                        <span class="text-3xl text-green-500">📅</span>
                    </div>
                    <p class="text-5xl text-gray-900 font-extrabold mt-2">
                        {{ number_format($totalEvents) }}
                    </p>
                    <a href="{{ route('events.index') }}" class="text-sm text-green-600 hover:text-green-800 mt-3 block font-medium">
                        Lihat Semua Event &rarr;
                    </a>
                </div>

                {{-- Kartu 3: Total Ulasan --}}
                <div class="bg-white p-6 rounded-lg shadow-xl hover:shadow-2xl transition duration-300 border-b-4 border-purple-600">
                    <div class="flex justify-between items-center">
                        <h4 class="text-base font-semibold text-gray-500 uppercase tracking-wider">Total Ulasan</h4>
                        <span class="text-3xl text-purple-500">⭐</span>
                    </div>
                    <p class="text-5xl text-gray-900 font-extrabold mt-2">
                        {{ number_format($totalReviews) }}
                    </p>
                    <a href="#" class="text-sm text-purple-600 hover:text-purple-800 mt-3 block font-medium">
                        Moderasi Ulasan &rarr;
                    </a>
                </div>
                
            </div>
            
            {{-- BAGIAN AKTIVITAS TERKINI (Contoh penambahan konten) --}}
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-bold text-xl mb-3 border-b pb-2 text-gray-800">Aktivitas Sistem Terbaru</h3>
                    <p class="text-sm text-gray-600">Ini adalah tempat untuk menampilkan log atau daftar aktivitas terbaru (misalnya, 5 Event baru, 5 Pengguna baru, dll.)</p>
                    
                    <ul class="mt-4 space-y-3 text-sm">
                        <li class="p-3 bg-gray-50 rounded border-l-2 border-green-400">
                            **{{ Auth::user()->name }}** berhasil masuk ke **Admin Panel** pada {{ now()->isoFormat('D MMMM YYYY, HH:mm') }}.
                        </li>
                        <li class="p-3 bg-gray-50 rounded border-l-2 border-indigo-400">
                            Organizer baru terdaftar: **[Nama Organizer]**.
                        </li>
                        <li class="p-3 bg-gray-50 rounded border-l-2 border-blue-400">
                            Event **'{{ $totalEvents > 0 ? 'Tech Conference' : 'Event Baru' }}'** telah dibuat.
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>