@extends('layouts.admin-layout')

@section('header')
    <h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
        {{ __('Data Semua Peserta') }}
    </h2>
@endsection

@section('content')
    {{-- Latar Belakang Vibrant (Khusus Area Konten Utama) --}}
    <div class="min-h-screen bg-gray-50 py-10 px-4 sm:px-6 lg:px-8 rounded-xl shadow-inner">
        
        <div class="max-w-7xl mx-auto space-y-6">
            
            {{-- Header & Judul (White Text) --}}
            <div class="flex flex-col md:flex-row justify-between items-end md:items-center">
                <div>
                    <h1 class="text-3xl font-black text-grey-800 drop-shadow-md">Global Participant Data</h1>
                    <p class="text-indigo-800 mt-1">Daftar seluruh pemegang tiket dari semua event.</p>
                </div>
            </div>

            {{-- FILTER SECTION (Glassmorphism) --}}
            <div class="bg-white/95 backdrop-blur-xl shadow-xl rounded-2xl p-6 border border-white/20">
                <form method="GET" action="{{ route('admin.participants.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    
                    {{-- Search --}}
                    <div class="md:col-span-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Cari Peserta</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Nama, Email, atau Kode Tiket..."
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    {{-- Filter Event --}}
                    <div class="md:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Filter Event</label>
                        <select name="event_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Semua Event --</option>
                            @foreach($events as $id => $name)
                                <option value="{{ $id }}" {{ request('event_id') == $id ? 'selected' : '' }}>
                                    {{ Str::limit($name, 20) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Status Pembayaran --}}
                    <div class="md:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Status Pembayaran</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="md:col-span-2 flex space-x-2">
                        <button type="submit" class="flex-1 bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 font-bold transition">
                            Cari
                        </button>
                        <a href="{{ route('admin.participants.index') }}" class="px-3 py-2 bg-gray-200 text-gray-600 rounded-lg hover:bg-gray-300 transition" title="Reset">
                            ↻
                        </a>
                    </div>
                </form>
            </div>

            {{-- TABLE (White Card) --}}
            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Event & Tiket</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kontak Pembeli</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu Daftar</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($participants as $ticket)
                                <tr class="hover:bg-indigo-50/50 transition">
                                    {{-- Nama Peserta (Tiket) --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs mr-3">
                                                {{ substr($ticket->nama_pemegang_tiket, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $ticket->nama_pemegang_tiket }}</div>
                                                <div class="text-xs text-gray-500 font-mono">{{ substr($ticket->qr_code, 0, 8) }}...</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Info Event --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">{{ $ticket->booking->event->nama_event ?? 'Event Dihapus' }}</div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 mt-1">
                                            {{ $ticket->ticketType->nama_tiket ?? '-' }}
                                        </span>
                                    </td>

                                    {{-- Info Pembeli (Akun) --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $ticket->booking->attendee->name ?? 'Guest' }}</div>
                                        <div class="text-xs text-gray-500">{{ $ticket->booking->attendee->email ?? '-' }}</div>
                                    </td>

                                    {{-- Status Check-in & Pembayaran --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex flex-col items-center gap-1">
                                            {{-- Status Bayar --}}
                                            @if($ticket->booking->status_pembayaran == 'paid')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Paid
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @endif

                                            {{-- Status Checkin --}}
                                            @if($ticket->statusCheckIn == 'checked-in')
                                                <span class="text-xs text-blue-600 font-bold">✓ Hadir</span>
                                            @else
                                                <span class="text-xs text-gray-400">Belum Hadir</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Waktu --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                        {{ $ticket->created_at->format('d M Y') }}
                                        <span class="block text-xs">{{ $ticket->created_at->format('H:i') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                                        Tidak ada data peserta yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $participants->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection