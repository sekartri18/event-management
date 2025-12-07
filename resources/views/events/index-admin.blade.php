@extends('layouts.admin-layout')

@php $user = Auth::user(); @endphp

@section('header')
<div class="flex justify-between items-center">
    <h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
        {{ __('Semua Event - Admin Panel') }}
    </h2>
</div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
        {{-- Total Dana Masuk Card --}}
        @if($user && $user->isAdmin())
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

        {{-- Success Message --}}
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Search Form --}}
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
                    <a href="{{ route('events.index') }}" class="bg-white text-gray-800 px-6 py-2 rounded-md hover:bg-gray-100 text-center transition font-semibold border border-gray-400 shadow-sm whitespace-nowrap">Reset</a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition font-semibold border border-blue-600 shadow-md whitespace-nowrap">Cari</button>
                </div>
            </form>
        </div>

        {{-- Events Table --}}
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Gambar</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Nama Event</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Organizer</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Pendapatan</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($events as $event)
                        @php
                            $statusColor = match($event->status) {
                                'upcoming' => 'text-blue-600 bg-blue-50',
                                'ongoing' => 'text-green-600 bg-green-50',
                                'finished' => 'text-red-600 bg-red-50',
                                default => 'text-gray-600 bg-gray-50',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if ($event->gambar)
                                    <img src="{{ Storage::url($event->gambar) }}" alt="Thumbnail" class="h-10 w-10 object-cover rounded-md">
                                @else
                                    <span class="text-xs text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ $event->nama_event }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $event->organizer->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($event->tanggal_mulai)->isoFormat('D MMM YYYY') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $statusColor }} border border-current">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                Rp {{ number_format($event->bookings_sum_total_amount ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('events.show', $event) }}" class="text-purple-600 hover:text-purple-800 font-bold">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada event yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $events->withQueryString()->links() }}
        </div>
    </div>
@endsection
