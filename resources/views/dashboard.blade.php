<x-app-layout>
    {{-- Hapus slot header default --}}

    {{-- ========================================================================= --}}
    {{-- LATAR BELAKANG VIBRANT (Sama seperti Halaman Event) --}}
    {{-- ========================================================================= --}}
    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-7xl mx-auto space-y-10">
            
            {{-- HEADER TITLE (Text White) --}}
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-black text-4xl text-gray-900 leading-tight drop-shadow-md">
                        {{ __('Dashboard Pengguna') }}
                    </h2>
                    <p class="text-indigo-800 mt-2 text-lg font-medium">Selamat datang!</p>
                </div>
                {{-- Tanggal Hari Ini (Opsional, pemanis UI) --}}
                <div class="hidden md:block text-right text-white/80">
                    <p class="text-sm uppercase tracking-widest font-bold">{{ now()->isoFormat('dddd') }}</p>
                    <p class="text-2xl font-light">{{ now()->isoFormat('D MMMM YYYY') }}</p>
                </div>
            </div>

            {{-- 1. HERO BANNER (UBAH JADI GLASS CARD) --}}
            {{-- Sebelumnya gradien, sekarang putih transparan agar kontras dengan background --}}
            <div class="bg-white/90 backdrop-blur-xl shadow-2xl rounded-3xl p-8 md:p-12 border border-white/50 relative overflow-hidden group">
                
                {{-- Dekorasi Circle di dalam kartu --}}
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 group-hover:scale-150 transition duration-700"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                    
                    <div class="md:w-3/4">
                        <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4 leading-tight">
                            Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">{{ Auth::user()->name }}</span>! 👋
                        </h1>
                        <p class="text-gray-600 text-lg md:text-xl leading-relaxed">
                            @if(Auth::user()->role->name === 'admin')
                                Sistem berjalan lancar. Siap untuk memantau dan mengelola platform hari ini?
                            @elseif(Auth::user()->role->name === 'organizer')
                                Ide event apa lagi yang akan Anda wujudkan? Kelola penjualan tiket Anda sekarang.
                            @else
                                Siap untuk pengalaman tak terlupakan? Temukan event seru berikutnya di sini.
                            @endif
                        </p>
                    </div>

                    {{-- Tombol CTA (Warna Solid Indigo agar menonjol di kartu putih) --}}
                    <a href="{{ route('events.index') }}" 
                       class="whitespace-nowrap inline-flex justify-center items-center px-8 py-4 bg-indigo-600 text-white rounded-full font-bold text-lg shadow-lg hover:bg-indigo-700 hover:shadow-indigo-500/30 transition transform hover:-translate-y-1">
                        @if(Auth::user()->role->name === 'attendee')
                            Cari Event Seru 🔍
                        @else
                            Kelola Event ⚙️
                        @endif
                    </a>
                </div>
            </div>

            {{-- 2. QUICK ACCESS CARDS (GRID LAYOUT) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- KARTU TIKET SAYA --}}
                <div class="bg-white/80 backdrop-blur-md shadow-xl rounded-2xl p-8 border border-white/40 hover:bg-white/90 transition duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="p-3 bg-yellow-100 text-yellow-600 rounded-2xl group-hover:scale-110 transition duration-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Tiket Saya</h3>
                        </div>
                        <p class="text-gray-600">Lihat status pembayaran dan akses QR Code tiket event yang sudah Anda beli.</p>
                    </div>
                    
                    <a href="{{ route('bookings.index') }}" 
                       class="mt-6 inline-flex items-center text-yellow-600 font-bold hover:text-yellow-700 transition">
                        Buka Riwayat Tiket &rarr;
                    </a>
                </div>

                {{-- KARTU PROFILE (OPSIONAL / TAMBAHAN) --}}
                <div class="bg-white/80 backdrop-blur-md shadow-xl rounded-2xl p-8 border border-white/40 hover:bg-white/90 transition duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="p-3 bg-pink-100 text-pink-600 rounded-2xl group-hover:scale-110 transition duration-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Profil Akun</h3>
                        </div>
                        <p class="text-gray-600">Perbarui informasi pribadi, password, dan preferensi akun Anda.</p>
                    </div>
                    
                    <a href="{{ route('profile.edit') }}" 
                       class="mt-6 inline-flex items-center text-pink-600 font-bold hover:text-pink-700 transition">
                        Edit Profil &rarr;
                    </a>
                </div>

            </div>
            
            {{-- ALERT NOTIFIKASI (Floating Glass) --}}
            @if(session('status'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                     class="fixed bottom-5 right-5 bg-white/90 backdrop-blur shadow-2xl p-4 rounded-xl border-l-4 border-green-500 flex items-center space-x-3 animate-bounce-in">
                    <div class="text-green-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800">Sukses!</p>
                        <p class="text-sm text-gray-600">{{ session('status') }}</p>
                    </div>
                </div>
            @endif
            
        </div>
    </div>
</x-app-layout>