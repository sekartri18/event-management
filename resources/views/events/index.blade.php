<x-app-layout>
    {{-- Hapus slot header default --}}
    
    {{-- ========================================================================= --}}
    {{-- LATAR BELAKANG VIBRANT (WARNA LEBIH KUAT) --}}
    {{-- ========================================================================= --}}
    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- 1. HEADER SECTION (TEXT WHITE AGAR KONTRAS) --}}
            <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4">
                <div>
                    @php
                        $user = Auth::user();
                        $title = 'Jelajahi Event Seru';
                        $subtitle = 'Temukan pengalaman baru dan bergabunglah sekarang.';
                        
                        if ($user->isOrganizer()) {
                            $title = 'Kelola Event Anda';
                            $subtitle = 'Pantau performa dan manajemen tiket event Anda di sini.';
                        } elseif ($user->isAdmin()) {
                            $title = 'Event Oversight (Admin)';
                            $subtitle = 'Panel kontrol untuk memantau seluruh aktivitas event dalam sistem.';
                        }
                    @endphp
                    {{-- Judul Putih --}}
                    <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-tight drop-shadow-md">
                        {{ $title }}
                    </h1>
                    {{-- Subjudul Terang --}}
                    <p class="text-lg text-indigo-800 mt-2 font-medium">{{ $subtitle }}</p>
                </div>

                @can('create', App\Models\Event::class)
                    <a href="{{ route('events.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white text-indigo-600 border border-transparent rounded-full font-bold text-sm uppercase tracking-widest hover:bg-gray-100 hover:text-indigo-800 active:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-600 transition transform hover:scale-105 shadow-lg">
                       <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                       Buat Event Baru
                    </a>
                @endcan
            </div>

            {{-- 2. KARTU TOTAL DANA MASUK (ADMIN & ORGANIZER ONLY) --}}
            @if(Auth::user()->isOrganizer() || Auth::user()->isAdmin())
                <div class="bg-white/90 backdrop-blur-md overflow-hidden shadow-2xl sm:rounded-2xl border border-white/50 relative group">
                    <div class="p-8 flex items-center justify-between relative z-10">
                        <div>
                            <div class="text-indigo-600 text-sm font-bold uppercase tracking-wider mb-1">
                                Total Dana Masuk (Paid)
                            </div>
                            <div class="text-4xl font-extrabold text-gray-900">
                                Rp {{ number_format($totalDanaMasuk ?? 0, 0, ',', '.') }}
                            </div>
                            <p class="text-sm text-gray-500 mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Akumulasi pendapatan bersih dari event yang lunas.
                            </p>
                        </div>
                        <div class="hidden sm:flex p-4 bg-indigo-100 rounded-2xl text-indigo-600 shadow-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-lg" role="alert">
                    <p class="font-bold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            {{-- 3. FILTER & PENCARIAN (GLASS EFFECT LEBIH KUAT) --}}
            <div class="bg-white/95 backdrop-blur-xl shadow-xl rounded-2xl p-6 border border-white/20">
                <form method="GET" action="{{ route('events.index') }}" class="grid grid-cols-1 gap-6 md:grid-cols-12 items-end">
                    
                    {{-- Search Input --}}
                    <div class="md:col-span-6">
                        <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">Cari Event</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                   class="w-full pl-10 pr-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition"
                                   placeholder="Nama event, artis, atau topik..." />
                        </div>
                    </div>
                    
                    {{-- Filter Status --}}
                    <div class="md:col-span-3">
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <div class="relative">
                            <select name="status" id="status" class="w-full pl-4 pr-10 py-3 bg-gray-50 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 shadow-sm appearance-none transition">
                                <option value="">Semua Status</option>
                                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>📅 Upcoming</option>
                                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>▶️ Ongoing</option>
                                <option value="finished" {{ request('status') == 'finished' ? 'selected' : '' }}>✅ Finished</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="md:col-span-3 flex space-x-3">
                        <a href="{{ route('events.index') }}" 
                           class="flex-1 py-3 px-4 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 text-center font-semibold transition border border-gray-200">
                            Reset
                        </a>
                        <button type="submit" class="flex-1 py-3 px-4 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 shadow-md text-center font-semibold transition transform active:scale-95">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            {{-- Pesan Not Found --}}
            @if(request()->hasAny(['search', 'status', 'location']) && $events->isEmpty())
                <div class="bg-white/80 backdrop-blur rounded-2xl p-12 text-center shadow-lg">
                    <div class="mx-auto w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mb-4 text-red-500">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="mt-2 text-lg font-bold text-gray-900">Tidak ada event ditemukan</h3>
                    <p class="mt-1 text-gray-600">Coba ubah kata kunci pencarian atau filter Anda.</p>
                    <div class="mt-6">
                        <a href="{{ route('events.index') }}" class="text-indigo-600 hover:text-indigo-800 font-bold hover:underline">Reset Filter &rarr;</a>
                    </div>
                </div>
            @endif

            {{-- 4. GRID EVENT CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($events as $event)
                    @php
                        $statusStyles = match($event->status) {
                            'upcoming' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'icon' => '📅'],
                            'ongoing' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200', 'icon' => '▶️'],
                            'finished' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'border' => 'border-gray-200', 'icon' => '🏁'],
                            default => ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'border' => 'border-gray-200', 'icon' => '❓'],
                        };
                        
                        $sisaTiket = $event->ticket_types_sum_kuota ?? 0;
                    @endphp

                    <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl hover:shadow-indigo-500/20 border border-gray-100 overflow-hidden transition-all duration-300 transform hover:-translate-y-2 flex flex-col h-full">
                        
                        {{-- GAMBAR HEADER --}}
                        <div class="relative h-56 overflow-hidden">
                            @if ($event->gambar)
                                <img src="{{ Storage::url($event->gambar) }}" alt="{{ $event->nama_event }}" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-sm font-medium">No Image</span>
                                </div>
                            @endif
                            
                            {{-- Badge Status --}}
                            <div class="absolute top-4 right-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide shadow-md {{ $statusStyles['bg'] }} {{ $statusStyles['text'] }} backdrop-blur-md border {{ $statusStyles['border'] }}">
                                    {{ $statusStyles['icon'] }} {{ ucfirst($event->status) }}
                                </span>
                            </div>
                        </div>

                        {{-- KONTEN --}}
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center text-xs text-gray-500 mb-2 font-medium">
                                    <span class="text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">{{ \Carbon\Carbon::parse($event->tanggal_mulai)->isoFormat('D MMMM YYYY') }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $event->lokasi }}</span>
                                </div>

                                <h3 class="text-xl font-extrabold text-gray-900 leading-snug mb-3 group-hover:text-indigo-600 transition-colors">
                                    <a href="{{ route('events.show', $event) }}">
                                        {{ Str::limit($event->nama_event, 50) }}
                                    </a>
                                </h3>

                                <p class="text-gray-600 text-sm line-clamp-2 mb-4 leading-relaxed">
                                    {{ $event->deskripsi ?? 'Tidak ada deskripsi singkat.' }}
                                </p>
                            </div>

                            {{-- INFO BAWAH --}}
                            <div class="space-y-3">
                                <div class="border-t border-gray-100"></div>

                                <div class="flex items-center justify-between">
                                    {{-- Kiri: Kuota --}}
                                    <div class="flex items-center space-x-2 text-sm">
                                        @if($sisaTiket > 0)
                                            <span class="bg-indigo-50 text-indigo-700 p-1.5 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                            </span>
                                            <span class="font-bold text-gray-700">{{ number_format($sisaTiket) }} <span class="font-normal text-gray-500">Tiket</span></span>
                                        @else
                                            <span class="bg-red-50 text-red-600 p-1.5 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </span>
                                            <span class="font-bold text-red-600">Sold Out</span>
                                        @endif
                                    </div>

                                    {{-- Kanan: Penyelenggara --}}
                                    <div class="text-xs text-gray-400 font-semibold uppercase tracking-wide">
                                        {{ Str::limit($event->organizer->name ?? 'Admin', 10) }}
                                    </div>
                                </div>

                                {{-- PENDAPATAN (Admin/Organizer) --}}
                                @if(Auth::user()->isOrganizer() || Auth::user()->isAdmin())
                                    <div class="mt-2 py-2 px-3 bg-green-50 border border-green-100 rounded-lg flex items-center justify-between">
                                        <span class="text-xs font-bold text-green-600 uppercase">Pendapatan</span>
                                        <span class="text-sm font-bold text-green-700">Rp {{ number_format($event->bookings_sum_total_amount ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                @endif

                                {{-- Tombol Aksi --}}
                                <a href="{{ route('events.show', $event) }}" class="block w-full text-center py-3 bg-gray-900 text-white rounded-xl text-sm font-bold tracking-wide hover:bg-indigo-600 transition-all duration-300 shadow-lg mt-4 transform group-hover:scale-105">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    @if(!request()->hasAny(['search', 'status']))
                        <div class="col-span-full bg-white/80 backdrop-blur rounded-2xl p-12 text-center border-2 border-dashed border-indigo-200 shadow-xl">
                            <div class="mx-auto w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mb-4 text-indigo-500">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Belum Ada Event</h3>
                            <p class="text-gray-500 mt-2 mb-6">Jadilah yang pertama membuat event menarik di platform ini!</p>
                            @can('create', App\Models\Event::class)
                                <a href="{{ route('events.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-bold rounded-full hover:bg-indigo-700 shadow-md transition transform hover:-translate-y-1">
                                    + Buat Event Sekarang
                                </a>
                            @endcan
                        </div>
                    @endif
                @endforelse
            </div>

            {{-- PAGINATION --}}
            <div class="mt-8">
                {{ $events->appends(request()->query())->links() }}
            </div>
            
        </div>
    </div>
</x-app-layout>