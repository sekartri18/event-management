<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
            {{ __('Langkah Pembayaran') }}
        </h2>
    </x-slot>

    @php
        // Tentukan URL Midtrans Snap berdasarkan environment
        $midtransScriptUrl = env('MIDTRANS_IS_PRODUCTION') 
                            ? 'https://app.midtrans.com/snap/snap.js' 
                            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    @endphp
    
    {{-- PASTIKAN $clientKey dan $snapToken DIKIRIM DARI CONTROLLER --}}
    @isset($snapToken)
        {{-- Script Midtrans Snap dipanggil dengan data-client-key --}}
        <script type="text/javascript" src="{{ $midtransScriptUrl }}" data-client-key="{{ $clientKey }}"></script>
    @endisset

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md">
                    <p class="font-bold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md">
                    <p class="font-bold">Error!</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                
                <h3 class="text-3xl font-bold text-indigo-700 mb-2">Checkout Pesanan Anda</h3>
                <p class="text-xl text-gray-700 mb-6">Event: <strong>{{ $booking->event->nama_event }}</strong></p>
                <p class="text-gray-500 mb-8">Selesaikan pembayaran untuk Booking ID: <strong>#{{ $booking->id }}</strong></p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    {{-- ==================================================== --}}
                    {{-- KOLOM KIRI: RINCIAN PEMBAYARAN (DIPERBARUI) --}}
                    {{-- ==================================================== --}}
                    <div class="space-y-6">
                        <div class="border-b pb-4">
                            <h4 class="text-lg font-bold text-gray-800 mb-4">Rincian Tagihan</h4>
                            
                            {{-- 1. Subtotal Tiket --}}
                            <div class="flex justify-between text-gray-600 mb-2">
                                <span>Subtotal Tiket ({{ $booking->tickets->count() }}x)</span>
                                <span class="font-medium">
                                    Rp{{ number_format($booking->total_amount - $booking->admin_fee, 0, ',', '.') }}
                                </span>
                            </div>

                            {{-- 2. Biaya Admin --}}
                            <div class="flex justify-between text-gray-600 mb-4">
                                <span>Biaya Layanan (Admin Fee)</span>
                                <span class="font-medium text-gray-800">
                                    Rp{{ number_format($booking->admin_fee, 0, ',', '.') }}
                                </span>
                            </div>

                            {{-- Garis Pemisah --}}
                            <div class="border-t border-dashed border-gray-300 my-4"></div>

                            {{-- 3. Total Akhir --}}
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-800">Total Bayar:</span>
                                <span class="text-3xl font-extrabold text-red-600">
                                    Rp{{ number_format($booking->total_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                            <p class="font-semibold text-yellow-800 flex items-center space-x-2">
                                ⚠️ Status Saat Ini: Pending
                            </p>
                            <p class="text-sm text-yellow-700 mt-1">Pesanan Anda akan dibatalkan jika pembayaran tidak diselesaikan dalam 24 jam (Simulasi).</p>
                        </div>
                    </div>
                    {{-- ==================================================== --}}

                    {{-- KOLOM KANAN: TOMBOL PEMBAYARAN MIDTRANS --}}
                    <div class="border-l pl-8 space-y-6">
                        <h4 class="text-xl font-bold text-gray-800">Bayar dengan Midtrans Snap</h4>
                        
                        <p class="text-gray-600">Tekan tombol di bawah untuk memilih metode pembayaran (Kartu Kredit, Virtual Account, E-wallet, dll.) melalui Midtrans.</p>

                        @isset($snapToken)
                            <div>
                                {{-- Tombol Pemicu Pembayaran Midtrans Snap --}}
                                <button id="pay-button" 
                                        class="w-full inline-flex justify-center items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 transition shadow-md mt-4">
                                    Bayar Sekarang
                                </button>
                            </div>
                        @else
                            <div class="p-4 bg-red-100 rounded-lg text-red-700">Gagal memuat token pembayaran.</div>
                        @endisset
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk memicu Midtrans Snap Pop-up --}}
    @isset($snapToken)
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            // Memanggil Snap Pop-up dengan Snap Token yang didapatkan dari Controller
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    // Midtrans akan mengirimkan Webhook, tapi kita juga redirect ke halaman konfirmasi
                    alert("Pembayaran berhasil! Silakan tunggu konfirmasi tiket.");
                    window.location.href = "{{ route('bookings.confirmation', $booking) }}";
                },
                onPending: function(result){
                    // Midtrans akan mengirimkan Webhook, tapi kita juga redirect ke halaman konfirmasi
                    alert("Transaksi pending. Silakan selesaikan pembayaran sesuai instruksi.");
                    window.location.href = "{{ route('bookings.confirmation', $booking) }}";
                },
                onError: function(result){
                    // Pembayaran gagal. Tetap di halaman checkout
                    alert("Pembayaran gagal. Silakan coba lagi.");
                    // Tidak perlu redirect, biarkan pengguna mencoba lagi
                },
                onClose: function(){
                    // Pop-up ditutup tanpa menyelesaikan pembayaran
                    alert('Anda menutup pop-up pembayaran.');
                }
            });
        };
    </script>
    @endisset
</x-app-layout>