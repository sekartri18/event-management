<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight text-center">
            {{ __('Tiket Anda Siap Digunakan!') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="bg-white shadow-2xl rounded-2xl p-10">

                {{-- HEADER KONFIRMASI --}}
                <div class="text-center mb-10 border-b pb-6">
                    <span class="text-6xl text-green-500 block mb-4">✅</span>
                    <h3 class="text-3xl font-extrabold text-green-600 mb-3">Pembayaran Sukses!</h3>
                    <p class="text-lg text-gray-600">
                        Booking ID:
                        <span class="font-mono text-gray-800">#{{ $booking->id }}</span> |
                        Event:
                        <span class="font-semibold text-gray-800">{{ $booking->event->nama_event }}</span>
                    </p>
                </div>

                {{-- RINGKASAN PEMBAYARAN --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12 p-6 bg-indigo-50 rounded-xl border-l-4 border-indigo-500 shadow-inner">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-2">Status Pembayaran</p>
                        <span class="inline-flex items-center px-5 py-2 bg-green-600 border border-green-600 rounded-lg font-semibold text-xs text-white uppercase tracking-widest shadow-md">
                            Paid
                        </span>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-2">Total Dibayar</p>
                        <p class="text-2xl font-bold text-indigo-700">
                            Rp{{ number_format($booking->total_amount, 0, ',', '.') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-2">Metode Pembayaran</p>
                        <p class="text-base text-gray-800">{{ $booking->payment_method ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-2">Jumlah Tiket</p>
                        <p class="text-base text-gray-800">{{ $booking->tickets->count() }} Tiket</p>
                    </div>
                </div>

                {{-- DAFTAR TIKET --}}
                <h4 class="mt-8 text-2xl font-bold text-gray-800 mb-8 border-b-2 border-indigo-100 pb-3 tracking-wide">
                    🎟️ Daftar Tiket Anda
                </h4>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @foreach($booking->tickets as $ticket)
                        <div class="p-6 border border-gray-200 rounded-2xl shadow-lg bg-gradient-to-br from-white to-indigo-50 hover:shadow-xl transition-all duration-300 relative overflow-hidden">

                            {{-- Header --}}
                            <div class="mb-4 pb-3 border-b border-dashed border-gray-300">
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">
                                    Nama Pemegang Tiket
                                </p>
                                <p class="text-2xl font-extrabold text-gray-900 leading-snug">
                                    {{ $ticket->nama_pemegang_tiket }}
                                </p>
                            </div>

                            {{-- Detail Tiket --}}
                            <div class="text-center border-t border-gray-200 pt-4 mt-2">
                                <p class="text-sm font-semibold text-indigo-600">
                                    {{ $ticket->ticketType->name ?? 'VIP' }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Ticket ID: #{{ $ticket->id }}
                                </p>
                            </div>

                            {{-- QR Code --}}
                            <div class="text-center mt-6">
                              <div class="w-48 h-60 bg-gray-100 flex items-center justify-center rounded-xl border border-gray-300 mx-auto">
                                 {!! $ticket->qr_svg !!}
                              </div>
                              <p class="text-xs text-gray-500 font-mono mt-2">
                                    {{ Str::limit($ticket->qr_code, 20) }}
                              </p>
                            </div>


                            {{-- STATUS CHECK-IN (diletakkan di bawah, bukan di header) --}}
                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <p class="text-sm font-medium text-gray-700">Status Check-In:</p>
                                @if($ticket->statusCheckIn == 'pending')
                                    <span class="inline-flex items-center px-5 py-2 rounded-lg font-semibold text-xs text-white uppercase tracking-widest"
                                        style="background-color: #facc15; border: 1px solid #facc15;">
                                        Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-5 py-2 bg-green-600 border border-green-600 rounded-lg font-semibold text-xs text-white uppercase tracking-widest">
                                        {{ ucfirst($ticket->statusCheckIn) }}
                                    </span>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- FOOTER CTA --}}
                <div class="mt-12 pt-8 border-t border-gray-200 text-center">
                    <a href="{{ route('events.index') }}" 
                       class="inline-flex items-center px-8 py-3 bg-blue-600 border border-blue-600 rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                        ← Kembali ke Jelajah Event
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
