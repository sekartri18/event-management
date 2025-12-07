<x-app-layout>
    {{-- Hapus slot header default --}}

    {{-- LATAR BELAKANG VIBRANT --}}
    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-5xl mx-auto space-y-8">

            {{-- HEADER SECTION --}}
            <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 leading-tight drop-shadow-md">
                        {{ __('Riwayat Tiket Saya') }}
                    </h2>
                    <p class="text-indigo-800 mt-2 text-lg font-medium">
                        Pantau status pesanan dan akses tiket event Anda di sini.
                    </p>
                </div>
                
                <a href="{{ route('events.index') }}" class="text-white hover:text-indigo-100 font-semibold flex items-center transition">
                    &larr; Cari Event Lainnya
                </a>
            </div>

            {{-- BOOKING LIST --}}
            <div class="space-y-6">
                @forelse ($bookings as $booking)
                    @php
                        // Logika Warna Status
                        $statusStyles = match($booking->status_pembayaran) {
                            'paid' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-200', 'icon' => '✅', 'label' => 'Lunas (Paid)'],
                            'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200', 'icon' => '⏳', 'label' => 'Menunggu Pembayaran'],
                            'failed' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200', 'icon' => '❌', 'label' => 'Gagal / Dibatalkan'],
                            default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-200', 'icon' => '❓', 'label' => $booking->status_pembayaran],
                        };
                    @endphp

                    {{-- KARTU BOOKING (GLASSMORPHISM) --}}
                    <div class="bg-white/90 backdrop-blur-xl shadow-xl rounded-2xl overflow-hidden border border-white/50 transition transform hover:-translate-y-1 hover:shadow-2xl">
                        
                        {{-- Header Kartu --}}
                        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Order ID: #{{ $booking->id }}</span>
                                    <span class="text-gray-300">•</span>
                                    <span class="text-xs text-gray-500">{{ $booking->tanggal_booking->isoFormat('D MMMM YYYY, HH:mm') }}</span>
                                </div>
                                <h3 class="text-xl font-extrabold text-gray-900">
                                    <a href="{{ route('events.show', $booking->event) }}" class="hover:text-indigo-600 transition">
                                        {{ $booking->event->nama_event }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600 flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $booking->event->lokasi }}
                                </p>
                            </div>

                            {{-- Badge Status --}}
                            <div class="flex flex-col items-end gap-2">
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide border {{ $statusStyles['bg'] }} {{ $statusStyles['text'] }} {{ $statusStyles['border'] }}">
                                    <span class="mr-1.5">{{ $statusStyles['icon'] }}</span>
                                    {{ $statusStyles['label'] }}
                                </span>
                                <div class="text-right">
                                    <span class="block text-xs text-gray-500">Total Tagihan</span>
                                    <span class="font-black text-lg text-gray-800">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Body Kartu: Detail Tiket --}}
                        <div class="p-6 bg-gray-50/50">
                            <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-3">Rincian Tiket ({{ $booking->jumlah_tiket }} Item)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($booking->tickets as $ticket)
                                    <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100 shadow-sm">
                                        <div>
                                            <p class="font-bold text-indigo-700 text-sm">{{ $ticket->ticketType->nama_tiket }}</p>
                                            <p class="text-xs text-gray-500">{{ $ticket->nama_pemegang_tiket }}</p>
                                        </div>
                                        <div class="text-right">
                                            @if($booking->status_pembayaran == 'paid')
                                                <span class="text-xs bg-green-50 text-green-600 px-2 py-1 rounded font-bold">Siap Check-in</span>
                                            @else
                                                <span class="text-xs text-gray-400 italic">Menunggu</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Footer Kartu: Tombol Aksi --}}
                        <div class="p-4 bg-white border-t border-gray-100 flex justify-end gap-3">
                            
                            @if($booking->status_pembayaran == 'pending')
                                {{-- Tombol Bayar Sekarang --}}
                                <a href="{{ route('bookings.checkout', $booking) }}" 
                                   class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 transition shadow-lg shadow-indigo-500/30">
                                    💳 Bayar Sekarang
                                </a>
                            @elseif($booking->status_pembayaran == 'paid')
                                {{-- Tombol Lihat E-Ticket --}}
                                <a href="{{ route('bookings.confirmation', $booking) }}" 
                                   class="inline-flex items-center px-5 py-2.5 bg-green-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 transition shadow-lg shadow-green-500/30">
                                    🎟️ Lihat E-Ticket (QR)
                                </a>
                            @endif

                        </div>
                    </div>
                @empty
                    {{-- Empty State (Glass) --}}
                    <div class="bg-white/80 backdrop-blur-md rounded-3xl p-12 text-center shadow-xl border border-white/50">
                        <div class="mx-auto w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mb-6 text-indigo-400">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-900">Belum Ada Tiket</h3>
                        <p class="text-gray-600 mt-2 mb-8 max-w-md mx-auto">Anda belum memiliki riwayat pembelian tiket. Yuk, cari event menarik dan beli tiket pertamamu!</p>
                        <a href="{{ route('events.index') }}" class="inline-flex items-center px-8 py-3 bg-indigo-600 text-white font-bold rounded-full hover:bg-indigo-700 shadow-lg transition transform hover:-translate-y-1">
                            Cari Event Sekarang
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION (JIKA ADA) --}}
            {{-- <div class="mt-8">
                {{ $bookings->links() }}
            </div> --}}
            
        </div>
    </div>
</x-app-layout>