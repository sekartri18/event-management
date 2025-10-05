{{-- resources/views/events/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
            {{ __('Detail Event') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                
                {{-- Event Header --}}
                <div class="p-6 sm:px-10 sm:py-8 bg-indigo-50 border-b border-indigo-200">
                    <h1 class="text-3xl font-extrabold text-indigo-800 mb-2 leading-tight">
                        {{ $event->nama_event }}
                    </h1>
                    <p class="text-indigo-600 font-medium flex items-center space-x-2">
                        <span>🗓️</span> 
                        <span>{{ \Carbon\Carbon::parse($event->tanggal_mulai)->isoFormat('D MMMM YYYY') }} - {{ \Carbon\Carbon::parse($event->tanggal_selesai)->isoFormat('D MMMM YYYY') }}</span>
                    </p>
                </div>

                <div class="p-6 sm:px-10 sm:py-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- Detail Section --}}
                    <div class="md:col-span-2 space-y-6">
                        
                        <div class="border-b pb-4">
                            <p class="text-sm font-semibold text-gray-500 mb-1">Deskripsi Event</p>
                            <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $event->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-500 mb-1">Lokasi</p>
                                <p class="text-gray-800 font-medium flex items-center space-x-2">
                                    <span class="text-purple-500">📍</span>
                                    <span>{{ $event->lokasi }}</span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 mb-1">Status</p>
                                @php
                                    $statusClass = match($event->status) {
                                        'upcoming' => 'bg-blue-200 text-blue-800',
                                        'ongoing' => 'bg-green-200 text-green-800',
                                        'finished' => 'bg-red-200 text-red-800',
                                        default => 'bg-gray-200 text-gray-800',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $statusClass }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <p class="text-sm font-semibold text-gray-500 mb-1">Diselenggarakan oleh</p>
                            <p class="text-gray-800 font-medium">{{ $event->organizer->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    {{-- Sidebar Aksi --}}
                    <div class="md:col-span-1 border-t md:border-t-0 md:border-l border-gray-100 pt-6 md:pt-0 md:pl-8 space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Aksi Event</h3>

                        {{-- Tombol Edit --}}
                        @can('update', $event)
                            <a href="{{ route('events.edit', $event) }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 transition shadow-md">
                                ✏️ Edit Event
                            </a>
                        @endcan

                        {{-- Tombol Hapus --}}
                        @can('delete', $event)
                            <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('ANDA YAKIN? Menghapus event ini akan menghapus semua tiket dan booking terkait!');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition shadow-md">
                                    🗑️ Hapus Event
                                </button>
                            </form>
                        @endcan
                        
                        {{-- Tombol Kembali --}}
                        <a href="{{ route('events.index') }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition shadow-sm">
                            &larr; Kembali ke Daftar
                        </a>
                        
                    </div>
                </div>

                {{-- Feedback / Success Message --}}
                @if (session('success'))
                    <div class="bg-green-100 border-t border-green-300 text-green-700 p-4 rounded-b-xl">
                        <p class="font-medium">Pembaruan Berhasil:</p>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border-t border-red-300 text-red-700 p-4 rounded-b-xl">
                        <p class="font-medium">Error:</p>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</x-app-layout>
