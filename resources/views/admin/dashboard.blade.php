<x-admin-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard (Akses Penuh Sistem)') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ALERT MODE ADMIN --}}
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 shadow-lg sm:rounded-lg mb-8" role="alert">
                <p class="font-bold text-lg">Mode Administrator Aktif</p>
                <p>Anda memiliki akses penuh untuk mengelola semua pengguna, event, dan konfigurasi sistem.</p>
            </div>

            {{-- =============================================== --}}
            {{-- BAGIAN LAPORAN KEUANGAN (BARU) --}}
            {{-- =============================================== --}}
            <div class="mb-10">
                <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center">
                    <span class="bg-gray-800 text-white rounded-full p-1 mr-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                    Laporan Keuangan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- KARTU 1: TOTAL DANA MASUK (GROSS) --}}
                    <div class="bg-white overflow-hidden shadow-lg rounded-xl border-l-8 border-blue-500">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-blue-600 uppercase">Total Dana Masuk (Gross)</p>
                                    <p class="text-3xl font-extrabold text-blue-900">
                                        Rp{{ number_format($totalGrossRevenue ?? 0, 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-blue-600 mt-1">Total nilai transaksi tiket + fee</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- KARTU 2: KEUNTUNGAN FEE (NET) --}}
                    <div class="bg-white overflow-hidden shadow-lg rounded-xl border-l-8 border-green-500">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-green-600 uppercase">Keuntungan Platform (Net)</p>
                                    <p class="text-3xl font-extrabold text-green-700">
                                        Rp{{ number_format($totalNetRevenue ?? 0, 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-green-600 mt-1">Akumulasi Biaya Admin (5%)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
            {{-- =============================================== --}}
            {{-- KARTU STATISTIK UTAMA --}}
            {{-- =============================================== --}}
            <h3 class="text-lg font-bold text-gray-700 mb-4 mt-8">Statistik Data</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3 mb-10">
                
                {{-- Kartu Total Pengguna --}}
                <div class="bg-white p-6 rounded-lg shadow-md border-b-4 border-indigo-600">
                    <div class="flex justify-between items-center">
                        <h4 class="text-base font-semibold text-gray-500 uppercase tracking-wider">Total Pengguna</h4>
                        <span class="text-3xl text-indigo-500">👥</span>
                    </div>
                    <p class="text-4xl text-gray-900 font-bold mt-2">
                        {{ number_format($totalUsers ?? 0) }}
                    </p>
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 mt-3 block font-medium">
                        Kelola Pengguna &rarr;
                    </a>
                </div>
                
                {{-- Kartu Total Event --}}
                <div class="bg-white p-6 rounded-lg shadow-md border-b-4 border-yellow-500">
                    <div class="flex justify-between items-center">
                        <h4 class="text-base font-semibold text-gray-500 uppercase tracking-wider">Total Event</h4>
                        <span class="text-3xl text-yellow-500">📅</span>
                    </div>
                    <p class="text-4xl text-gray-900 font-bold mt-2">
                        {{ number_format($totalEvents ?? 0) }}
                    </p>
                    <a href="{{ route('events.index') }}" class="text-sm text-yellow-600 hover:text-yellow-800 mt-3 block font-medium">
                        Lihat Semua Event &rarr;
                    </a>
                </div>

                {{-- Kartu Total Ulasan --}}
                <div class="bg-white p-6 rounded-lg shadow-md border-b-4 border-purple-600">
                    <div class="flex justify-between items-center">
                        <h4 class="text-base font-semibold text-gray-500 uppercase tracking-wider">Total Ulasan</h4>
                        <span class="text-3xl text-purple-500">⭐</span>
                    </div>
                    <p class="text-4xl text-gray-900 font-bold mt-2">
                        {{ number_format($totalReviews ?? 0) }}
                    </p>
                    <a href="{{ route('admin.reviews.index') }}" class="text-sm text-purple-600 hover:text-purple-800 mt-3 block font-medium">
                        Kelola Ulasan &rarr;
                    </a>
                </div>
                
            </div>
            
            {{-- BAGIAN AKTIVITAS TERKINI --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-bold text-xl mb-3 border-b pb-2 text-gray-800">Aktivitas Sistem Terbaru</h3>
                    <ul class="mt-4 space-y-3 text-sm">
                        <li class="p-3 bg-white border border-gray-200 rounded-lg shadow-sm border-l-4 border-green-500 hover:shadow-md transition duration-150">
                            <span class="text-green-700 font-semibold mr-2">LOGIN:</span>
                            <span class="font-bold text-gray-800">{{ Auth::user()->name }}</span> sedang memantau dashboard admin.
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-admin-app-layout>