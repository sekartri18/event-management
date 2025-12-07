<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            @php
                $user = Auth::user();
                $headerText = 'Jelajahi Semua Event'; 
                
                if ($user->isOrganizer()) {
                    $headerText = 'Manajemen Event Anda';
                } elseif ($user->isAdmin()) {
                    $headerText = 'Event Oversight (Admin Panel)';
                }
            @endphp
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
                {{ __($headerText) }}
            </h2>
            
            @can('create', App\Models\Event::class)
                <a href="{{ route('events.create') }}" 
                   class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-semibold text-sm transition shadow-md">
                   + Buat Event Baru
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- KARTU TOTAL DANA MASUK (KHUSUS ADMIN & ORGANIZER) --}}
            @if(Auth::user()->isOrganizer() || Auth::user()->isAdmin())
                <div class="mb-6 bg-white overflow-hidden shadow-xl sm:rounded-lg border-l-4 border-green-500">
                    <div class="p-6 flex items-center justify-between">
                        <div>
                            <div class="text-gray-500 text-sm font-bold uppercase tracking-wider">
                                Total Dana Masuk (Verifikasi Paid)
                            </div>
                            <div class="text-3xl font-extrabold text-gray-800 mt-1">
                                Rp {{ number_format($totalDanaMasuk ?? 0, 0, ',', '.') }}
                            </div>
                            <p class="text-xs text-gray-400 mt-1">*Akumulasi pendapatan dari semua event yang lunas.</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full text-green-600 hidden sm:block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                    <p class="font-bold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            {{-- FORM PENCARIAN --}}
            <div class="mb-6 p-4 bg-white shadow-md rounded-lg">
                <form method="GET" action="{{ route('events.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-4 items-end">
                    
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Event (Nama)</label>
                        <x-text-input type="text" name="search" id="search" 
                                      value="{{ request('search') }}" 
                                      placeholder="Cari nama event..." 
                                      class="w-full" />
                    </div>
                    
                    <div class="md:col-span-1">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Filter Status</label>
                        <select name="status" id="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                            <option value="">Semua Status</option>
                            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="finished" {{ request('status') == 'finished' ? 'selected' : '' }}>Finished</option>
                        </select>
                    </div>

                    <div class="md:col-span-1 flex space-x-2 justify-end"> 
                        <a href="{{ route('events.index') }}" 
                           class="bg-white text-gray-800 px-6 py-2 rounded-md hover:bg-gray-100 text-center transition font-semibold border border-gray-400 shadow-sm whitespace-nowrap">
                            Reset
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition font-semibold border border-blue-600 shadow-md whitespace-nowrap">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            @if(request()->hasAny(['search', 'status', 'location']) && $events->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center mb-6">
                    <p class="text-red-500 text-lg">Tidak ada event yang ditemukan dengan kriteria tersebut. 😥</p>
                    <a href="{{ route('events.index') }}" class="text-indigo-600 hover:text-indigo-800 mt-2 inline-block font-medium">Tampilkan Semua Event</a>
                </div>
            @endif

            {{-- ========================================================= --}}
            {{-- START: CARD VIEW (Mobile) --}}
            {{-- ========================================================= --}}
            @forelse($events as $event)
                @php
                    $statusColor = match($event->status) {
                        'upcoming' => 'bg-blue-100 text-blue-800 border-blue-300',
                        'ongoing' => 'bg-green-100 text-green-800 border-green-300',
                        'finished' => 'bg-red-100 text-red-800 border-red-300',
                        default => 'bg-gray-100 text-gray-800 border-gray-300',
                    };
                    $statusIcon = match($event->status) {
                        'upcoming' => '📅',
                        'ongoing' => '▶️',
                        'finished' => '✅',
                        default => '❓',
                    };
                    // Ambil Sisa Tiket (handle null jika belum ada tiket)
                    $sisaTiket = $event->ticket_types_sum_kuota ?? 0;
                @endphp

                <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-6 p-5 border border-gray-100 lg:hidden">
                    
                    @if ($event->gambar)
                        <div class="mb-4 h-40 overflow-hidden rounded-lg">
                            <img src="{{ Storage::url($event->gambar) }}" alt="{{ $event->nama_event }}" class="w-full h-full object-cover">
                        </div>
                    @else
                          <div class="mb-4 h-40 bg-gray-100 flex items-center justify-center rounded-lg border border-dashed border-gray-300">
                                <p class="text-gray-500 text-sm">Tidak Ada Gambar</p>
                          </div>
                    @endif
                    
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="text-xl font-bold text-gray-800 leading-snug">
                            <a href="{{ route('events.show', $event) }}" class="hover:text-indigo-600 transition">
                                {{ $event->nama_event }}
                            </a>
                        </h3>
                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full border {{ $statusColor }}">
                            {{ $statusIcon }} {{ ucfirst($event->status) }}
                        </span>
                    </div>

                    <div class="space-y-1 text-gray-600 text-sm">
                        {{-- INFO SISA TIKET BARU (MOBILE) --}}
                        <p class="flex items-center space-x-2 font-medium {{ $sisaTiket > 0 ? 'text-indigo-600' : 'text-red-500' }}">
                            <span>🎟️</span>
                            <span>
                                @if($sisaTiket > 0)
                                    Tiket Tersedia: <strong>{{ number_format($sisaTiket) }}</strong>
                                @else
                                    Habis Terjual (Sold Out)
                                @endif
                            </span>
                        </p>

                        <p class="flex items-center space-x-2">
                            <span class="text-gray-500">📍</span>
                            <span>{{ $event->lokasi }}</span>
                        </p>
                        <p class="flex items-center space-x-2">
                            <span class="text-gray-500">⏰</span>
                            <span>{{ \Carbon\Carbon::parse($event->tanggal_mulai)->isoFormat('D MMMM YYYY') }}</span>
                        </p>
                        <p class="text-xs pt-2 italic text-gray-400">Oleh: {{ $event->organizer->name ?? 'Admin' }}</p>
                    </div>

                    {{-- TOTAL PENDAPATAN (Mobile - Admin/Organizer Only) --}}
                    @if(Auth::user()->isOrganizer() || Auth::user()->isAdmin())
                        <div class="mt-4 p-3 bg-green-50 rounded-lg border border-green-200 text-center">
                            <span class="text-xs text-gray-500 uppercase font-bold">Pendapatan Event</span>
                            <div class="text-lg font-bold text-green-700">
                                Rp {{ number_format($event->bookings_sum_total_amount ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                    @endif

                    <div class="mt-4 text-right">
                        <a href="{{ route('events.show', $event) }}" 
                           class="text-indigo-600 hover:text-indigo-800 font-medium text-sm transition">
                            Lihat Detail &rarr;
                        </a>
                    </div>
                </div>
            @empty
                @if(!request()->hasAny(['search', 'status', 'location']))
                    @if(Auth::user()->isOrganizer())
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center border-l-4 border-indigo-500 mb-6 lg:hidden">
                            <p class="text-gray-500 text-lg mb-4">Anda belum memiliki event yang terdaftar.</p>
                            <a href="{{ route('events.create') }}">
                                <x-primary-button>
                                    {{ __('Buat Event Anda Sekarang!') }}
                                </x-primary-button>
                            </a>
                        </div>
                    @elseif(!Auth::user()->isOrganizer() && !Auth::user()->isAdmin()) 
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center mb-6 lg:hidden">
                            <p class="text-gray-500 text-lg">Belum ada event yang tersedia saat ini.</p>
                        </div>
                    @endif
                @endif
            @endforelse
            {{-- END: CARD VIEW --}}


            {{-- ========================================================= --}}
            {{-- START: TABLE VIEW (Desktop) --}}
            {{-- ========================================================= --}}
            <div class="hidden lg:block bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-1/12">Gambar</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-1/4">Nama Event</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-1/6">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-1/6">Status</th>
                                
                                {{-- KOLOM BARU: KUOTA TIKET (Desktop Header) --}}
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-1/6">Kuota Tiket</th>

                                @if(Auth::user()->isOrganizer() || Auth::user()->isAdmin())
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-1/6">Pendapatan</th>
                                @endif
                                
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider w-1/12">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($events as $event)
                                @php
                                    $statusColor = match($event->status) {
                                        'upcoming' => 'text-blue-600 bg-blue-50',
                                        'ongoing' => 'text-green-600 bg-green-50',
                                        'finished' => 'text-red-600 bg-red-50',
                                        default => 'text-gray-600 bg-gray-50',
                                    };
                                    // Ambil Sisa Tiket (handle null)
                                    $sisaTiket = $event->ticket_types_sum_kuota ?? 0;
                                @endphp
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if ($event->gambar)
                                            <img src="{{ Storage::url($event->gambar) }}" alt="Thumbnail" class="h-10 w-10 object-cover rounded-md">
                                        @else
                                            <span class="text-xs text-gray-400">N/A</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        <a href="{{ route('events.show', $event) }}" class="text-indigo-600 hover:text-indigo-800 transition">
                                            {{ $event->nama_event }}
                                        </a>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ \Carbon\Carbon::parse($event->tanggal_mulai)->isoFormat('D MMM YYYY') }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $event->lokasi }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $statusColor }} border border-current">
                                            {{ ucfirst($event->status) }}
                                        </span>
                                    </td>

                                    {{-- ISI KOLOM KUOTA TIKET (Desktop) --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($sisaTiket > 0)
                                            <span class="text-gray-700 font-bold">{{ number_format($sisaTiket) }}</span> 
                                            <span class="text-gray-500 text-xs">tersedia</span>
                                        @else
                                            <span class="text-red-500 font-bold text-xs uppercase bg-red-50 px-2 py-1 rounded">Sold Out</span>
                                        @endif
                                    </td>

                                    @if(Auth::user()->isOrganizer() || Auth::user()->isAdmin())
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                            Rp {{ number_format($event->bookings_sum_total_amount ?? 0, 0, ',', '.') }}
                                        </td>
                                    @endif

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('events.show', $event) }}" 
                                           class="text-purple-600 hover:text-purple-800 transition font-bold">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ (Auth::user()->isOrganizer() || Auth::user()->isAdmin()) ? '7' : '6' }}" class="px-6 py-8 whitespace-nowrap text-lg text-gray-500 text-center">
                                        @if(request()->hasAny(['search', 'status', 'location']))
                                            Tidak ada event yang ditemukan dengan kriteria tersebut.
                                        @elseif(Auth::user()->isOrganizer())
                                            Anda belum memiliki event yang terdaftar.
                                            <a href="{{ route('events.create') }}" class="text-indigo-600 hover:text-indigo-800 mt-2 inline-block font-medium">Buat Event Sekarang!</a>
                                        @else
                                            Belum ada event yang tersedia saat ini.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>