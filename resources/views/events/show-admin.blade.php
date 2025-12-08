@extends('layouts.admin-layout')

@section('header')
<h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
    {{ __('Detail Event - Admin') }}
</h2>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @elseif (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
            {{-- Event Image --}}
            @if ($event->gambar)
                <img src="{{ Storage::url($event->gambar) }}" alt="{{ $event->nama_event }}" class="w-full h-80 object-cover rounded-t-xl">
            @else
                <div class="w-full h-80 bg-gray-100 flex items-center justify-center rounded-t-xl border-b border-gray-200">
                    <p class="text-gray-500 text-lg">Tidak Ada Gambar Utama</p>
                </div>
            @endif

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

            {{-- Event Details --}}
            <div class="p-6 sm:px-10 sm:py-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Main Details --}}
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

                {{-- Admin Panel (Oversight & Actions) --}}
                <div class="md:col-span-1 bg-red-50 p-4 rounded-lg border border-red-200 h-fit">
                    <h3 class="font-bold text-red-800 mb-4">Admin Oversight</h3>
                    <div class="space-y-3">
                        
                        {{-- TOMBOL: DAFTAR PESERTA --}}
                        <a href="{{ route('events.checkin.index', $event) }}" 
                           class="block w-full px-4 py-2 bg-white text-indigo-700 border border-indigo-200 rounded-lg hover:bg-indigo-50 text-center font-semibold text-sm shadow-sm transition">
                            📋 Daftar Peserta
                        </a>

                        <a href="{{ route('events.edit', $event) }}" 
                           class="block w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-center font-semibold text-sm shadow-sm transition">
                            ✏️ Edit Event
                        </a>
                        
                        <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Yakin menghapus event ini?');" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold text-sm shadow-sm transition">
                                🗑️ Hapus Event
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Booking & Attendees Stats --}}
            <div class="p-6 sm:px-10 sm:py-8 border-t border-gray-200">
                <h3 class="font-bold text-lg text-gray-800 mb-4">Pendapatan & Performa</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <p class="text-gray-600 text-sm font-semibold">Total Pendapatan</p>
                        {{-- Menggunakan data dari withSum controller --}}
                        <p class="text-2xl font-bold text-green-700">Rp {{ number_format($event->bookings_sum_total_amount ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <p class="text-gray-600 text-sm font-semibold">Jumlah Booking</p>
                        <p class="text-2xl font-bold text-blue-700">{{ $event->bookings_count ?? 0 }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                        <p class="text-gray-600 text-sm font-semibold">Rating Rata-rata</p>
                        <p class="text-2xl font-bold text-purple-700">{{ number_format($averageRating, 1) }}/5 ⭐</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reviews Section --}}
        <div class="mt-6 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Ulasan Event</h3>
            
            @if($event->reviews->isNotEmpty())
                <div class="space-y-4">
                    @foreach($event->reviews->sortByDesc('tanggal_review') as $review)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $review->attendee->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $review->attendee->email }}</p>
                                </div>
                                <span class="text-xs text-gray-500">{{ $review->tanggal_review->diffForHumans() }}</span>
                            </div>
                            
                            <div class="mb-2">
                                <span class="text-yellow-400 text-lg">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->rating)
                                            ★
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </span>
                                <span class="ml-2 font-semibold text-gray-700">({{ $review->rating }}/5)</span>
                            </div>
                            
                            <p class="text-gray-700">{{ $review->komentar }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="font-semibold">Belum ada ulasan</p>
                    <p class="text-sm text-gray-400">Peserta belum memberikan ulasan untuk event ini</p>
                </div>
            @endif
        </div>

        {{-- Back Button --}}
        <div class="mt-6">
            <a href="{{ route('events.index') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                ← Kembali ke Daftar Event
            </a>
        </div>
    </div>
@endsection