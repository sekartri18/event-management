<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
            {{ __('Konfirmasi Pemesanan & Tiket Anda') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8 text-center">
                
                <h1 class="text-3xl font-bold text-green-600 mb-4">Pembayaran Berhasil! 🎉</h1>
                <p class="text-gray-700 mb-8">Terima kasih atas pemesanan Anda untuk event **{{ $booking->event->nama_event }}**.</p>
                
                {{-- RINCIAN PEMBAYARAN DIPERBARUI --}}
                <div class="border-t border-b py-4 mb-8 text-left max-w-lg mx-auto">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Subtotal Tiket:</span>
                        <span class="font-semibold">Rp{{ number_format($booking->total_amount - $booking->admin_fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Biaya Admin:</span>
                        <span class="font-semibold">Rp{{ number_format($booking->admin_fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t my-2"></div>
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-800">Total Dibayar:</span>
                        <span class="text-xl font-bold text-green-600">Rp{{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <p class="text-xs text-center text-gray-400 mt-4">Metode Pembayaran: {{ $booking->payment_method }}</p>
                </div>

                <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Detail Tiket Anda ({{ $booking->tickets->count() }} Tiket)</h3>

                <div class="space-y-6">
                    @foreach ($booking->tickets as $ticket)
                        <div class="border border-gray-200 rounded-xl p-6 flex items-center justify-between bg-white shadow-sm hover:shadow-md transition duration-300">
                            
                            {{-- DETAIL TIKET --}}
                            <div class="text-left flex-1 mr-4">
                                <p class="text-xl font-bold text-indigo-700">{{ $ticket->ticketType->nama_tiket }}</p>
                                <p class="text-sm text-gray-600">Pemegang: <span class="font-semibold text-gray-800">{{ $ticket->nama_pemegang_tiket }}</span></p>
                                <p class="text-sm text-gray-500">Harga: Rp{{ number_format($ticket->ticketType->harga, 0, ',', '.') }}</p>
                            </div>
                            
                            {{-- QR CODE --}}
                            <div class="flex-shrink-0">
                                @if (isset($ticket->qr_svg))
                                    {{-- INI PENTING: Menggunakan {!! !!} untuk merender SVG sebagai HTML --}}
                                    {!! $ticket->qr_svg !!} 
                                    <p class="text-xs text-gray-400 mt-2">Kode: {{ substr($ticket->qr_code, 0, 8) }}...</p>
                                @else
                                    <div class="w-24 h-24 bg-red-100 flex items-center justify-center border border-dashed border-red-400">
                                        QR Error
                                    </div>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
                
                <div class="mt-8 pt-4 border-t">
                    <p class="text-gray-500 text-sm">Harap simpan halaman ini atau cetak sebagai bukti tiket Anda. Tunjukkan QR Code ini saat check-in.</p>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>