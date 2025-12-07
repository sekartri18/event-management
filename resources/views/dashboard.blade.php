<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-3xl text-gray-900 leading-tight">
            {{ __('Dashboard Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
            
            {{-- 1. HERO BANNER: CALL TO ACTION UTAMA (Mencari Event) --}}
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 shadow-2xl rounded-2xl p-8 md:p-12 text-white transform transition duration-500 hover:shadow-3xl hover:-translate-y-1">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    
                    <div class="md:w-3/4 mb-6 md:mb-0">
                        <h1 class="text-4xl md:text-5xl font-black mb-2 leading-tight">
                            Halo, {{ Auth::user()->name }}!
                        </h1>
                        <p class="text-indigo-100 text-lg md:text-xl">
                            @if(Auth::user()->role->name === 'admin')
                                Selamat datang di Panel Admin. Kelola sistem dan pengguna dari sini.
                            @elseif(Auth::user()->role->name === 'organizer')
                                Kelola event Anda dan pantau penjualan tiket dengan mudah.
                            @else
                                Siap untuk pengalaman tak terlupakan? Mulailah petualangan event Anda sekarang.
                            @endif
                        </p>
                    </div>

                    <a href="{{ route('events.index') }}" 
                       class="w-full md:w-auto inline-flex justify-center items-center px-10 py-3 bg-white text-indigo-700 border-2 border-white rounded-full font-bold text-lg uppercase tracking-wider hover:bg-indigo-50 hover:text-indigo-800 transition duration-300 shadow-xl whitespace-nowrap">
                        @if(Auth::user()->role->name === 'attendee')
                            Cari Event &rarr;
                        @else
                            Kelola Event &rarr;
                        @endif
                    </a>
                </div>
            </div>

            {{-- 2. QUICK ACCESS CARD: TIKET SAYA --}}
            <div class="bg-white shadow-xl rounded-2xl p-6 md:p-8 border-l-8 border-yellow-500 transition duration-300 hover:shadow-2xl">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="flex items-center">
                         <svg class="w-10 h-10 mr-4 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Tiket Saya</h3>
                            <p class="text-gray-500">Akses cepat ke semua riwayat pembelian tiket dan status pembayaran Anda.</p>
                        </div>
                    </div>
                    
                    <a href="{{ route('bookings.index') }}" 
                       class="w-full md:w-auto mt-4 md:mt-0 inline-flex justify-center items-center px-6 py-2 bg-yellow-500 border border-transparent rounded-lg font-semibold text-base text-white hover:bg-yellow-600 transition shadow-md">
                        Lihat Semua Tiket &rarr;
                    </a>
                </div>
            </div>
            
            {{-- Bagian Alert (Jika ada) --}}
            @if(session('status'))
            <div class="p-4 bg-green-100 border-l-4 border-green-500 text-green-700 sm:rounded-lg">
                <p class="font-bold">Sukses!</p>
                <p>{{ session('status') }}</p>
            </div>
            @endif
            
        </div>
    </div>
</x-app-layout>