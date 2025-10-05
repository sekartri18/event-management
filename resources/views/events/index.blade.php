{{-- resources/views/events/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            @php
                $user = Auth::user();
                $headerText = 'Jelajahi Semua Event'; 
                
                // Ubah judul berdasarkan role
                if ($user->isOrganizer()) {
                    $headerText = 'Manajemen Event Anda';
                } elseif ($user->isAdmin()) {
                    $headerText = 'Event Oversight (Admin Panel)';
                }
            @endphp
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
                {{ __($headerText) }}
            </h2>
            
            {{-- TOMBOL INI SELALU ADA DI HEADER --}}
            @can('create_event')
                <a href="{{ route('events.create') }}" 
                   class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-semibold text-sm transition shadow-md">
                    + Buat Event Baru
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- KARTU RINGKASAN KHUSUS ORGANIZER / ADMIN --}}
            @if(Auth::user()->isOrganizer() || Auth::user()->isAdmin())
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border-l-4 border-{{ Auth::user()->isAdmin() ? 'red' : 'indigo' }}-500 mb-6">
                    <div class="p-6 text-gray-900">
                        @if(Auth::user()->isOrganizer())
                            <h3 class="text-xl font-bold text-indigo-700 mb-2">👋 Selamat Datang di Dashboard Event Anda!</h3>
                            <p class="mb-4 text-gray-600">Event di bawah ini adalah event yang Anda kelola. Cepat periksa status, edit detail, atau lihat pendaftar.</p>
                            <div class="flex space-x-6 text-sm">
                                <span class="text-gray-700">Event Dibuat: **{{ count($events) }}**</span> 
                                <span class="text-gray-700">Event Aktif: **...**</span>
                                <span class="text-gray-700">Total Pendaftar: **...**</span>
                            </div>
                        @elseif(Auth::user()->isAdmin())
                            <h3 class="text-xl font-bold text-red-700 mb-2">👁️ Mode Admin Oversight Aktif</h3>
                            <p class="mb-4 text-gray-600">Anda melihat **semua** event dalam sistem untuk tujuan audit.</p>
                        @endif
                    </div>
                </div>
            @endif
            
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                    <p class="font-bold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

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
                @endphp

                <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-6 p-5 border border-gray-100 lg:hidden">
                    
                    {{-- BLOK GAMBAR (Mobile) --}}
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

                    <div class="mt-4 text-right">
                        <a href="{{ route('events.show', $event) }}" 
                           class="text-indigo-600 hover:text-indigo-800 font-medium text-sm transition">
                            Lihat Detail &rarr;
                        </a>
                    </div>
                </div>
            @empty
                {{-- Empty State UNTUK ORGANIZER (DIPERBAIKI JADI TOMBOL) --}}
                @if(Auth::user()->isOrganizer())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center border-l-4 border-indigo-500">
                        <p class="text-gray-500 text-lg mb-4">Anda belum memiliki event yang terdaftar.</p>
                        
                        {{-- TOMBOL CREATE EVENT DI EMPTY STATE --}}
                        <a href="{{ route('events.create') }}">
                            <x-primary-button>
                                {{ __('Buat Event Anda Sekarang!') }}
                            </x-primary-button>
                        </a>
                    </div>
                @else
                    {{-- Empty State untuk Attendee (Jika tidak ada event sama sekali) --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <p class="text-gray-500 text-lg">Belum ada event yang tersedia saat ini.</p>
                    </div>
                @endif
            @endforelse

            {{-- Table View (Desktop Only) --}}
            <div class="hidden lg:block bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-1/12">Gambar</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-1/3">Nama Event</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-1/4">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-1/4">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-1/6">Status</th>
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
                                @endphp
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    {{-- Data Gambar (Desktop) --}}
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
                                        <p class="text-xs text-gray-500 mt-0.5">Oleh: {{ $event->organizer->name ?? 'Admin' }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($event->tanggal_mulai)->isoFormat('D MMM') }} - 
                                        {{ \Carbon\Carbon::parse($event->tanggal_selesai)->isoFormat('D MMM YYYY') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $event->lokasi }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $statusColor }} border border-current">
                                            {{ ucfirst($event->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('events.show', $event) }}" 
                                           class="text-purple-600 hover:text-purple-800 transition font-bold">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 whitespace-nowrap text-lg text-gray-500 text-center">
                                        <p>Belum ada event yang terdaftar dalam sistem.</p>
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