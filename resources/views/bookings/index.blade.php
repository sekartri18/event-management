<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
            {{ __('Riwayat Pembelian Tiket Anda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">

                @forelse ($bookings as $booking)
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 border-l-4 border-indigo-600 hover:shadow-lg transition">
                        
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $booking->event->nama_event }}</h3>
                                <p class="text-sm text-gray-500">
                                    Dibeli pada: {{ \Carbon\Carbon::parse($booking->tanggal_booking)->isoFormat('D MMMM YYYY, HH:mm') }}
                                </p>
                            </div>
                            <span class="text-xs font-semibold py-1 px-3 rounded-full text-white 
                                @if($booking->status == 'paid') bg-green-600
                                @elseif($booking->status == 'pending') bg-yellow-600
                                @else bg-red-600
                                @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 border-t pt-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-500">Jumlah Tiket</p>
                                <p class="text-lg font-bold text-indigo-700">{{ $booking->tickets->count() }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500">Total Pembayaran</p>
                                <p class="text-lg font-bold text-indigo-700">Rp{{ number_format($booking->total_amount, 0, ',', '.') }}</p>
                            </div>
                            <div class="md:col-span-2 text-right self-end">
                                
                                @if($booking->status == 'pending')
                                    {{-- Tombol Lanjutkan Pembayaran (Jika Pending) --}}
                                    <a href="{{ route('bookings.checkout', $booking) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-red-600 border border-red-600 rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition shadow-md">
                                        Lanjutkan Pembayaran &rarr;
                                    </a>
                                @else
                                    {{-- Tombol Lihat Detail & Tiket (Jika Paid/Lunas) --}}
                                    <a href="{{ route('bookings.confirmation', $booking) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-blue-600 rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition shadow-md">
                                        Lihat Detail & Tiket &rarr;
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 text-center">
                        <p class="text-gray-500 text-lg mb-4">Anda belum memiliki riwayat pembelian tiket.</p>
                        <a href="{{ route('events.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                            Mulai jelajahi event sekarang!
                        </a>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
</x-app-layout>
