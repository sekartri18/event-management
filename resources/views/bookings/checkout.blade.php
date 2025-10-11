<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
            {{ __('Langkah Pembayaran') }}
        </h2>
    </x-slot>

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
                <p class="text-xl text-gray-700 mb-6">Event: **{{ $booking->event->nama_event }}**</p>
                <p class="text-gray-500 mb-8">Selesaikan pembayaran untuk Booking ID: **#{{ $booking->id }}**</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    {{-- KOLOM KIRI: RINGKASAN & TOTAL --}}
                    <div class="space-y-6">
                        <div class="border-b pb-4">
                            <p class="text-lg font-semibold text-gray-800">Total Pembayaran:</p>
                            <p class="text-4xl font-extrabold text-red-600 mt-1">
                                Rp{{ number_format($booking->total_amount, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-gray-500 mt-2">Termasuk {{ $booking->tickets->count() }} tiket.</p>
                        </div>
                        
                        <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                            <p class="font-semibold text-yellow-800 flex items-center space-x-2">
                                ⚠️ Status Saat Ini: Pending
                            </p>
                            <p class="text-sm text-yellow-700 mt-1">Pesanan Anda akan dibatalkan jika pembayaran tidak diselesaikan dalam 24 jam (Simulasi).</p>
                        </div>
                    </div>

                    {{-- KOLOM KANAN: PILIH METODE PEMBAYARAN --}}
                    <div class="border-l pl-8 space-y-6">
                        <h4 class="text-xl font-bold text-gray-800">Pilih Metode Pembayaran</h4>
                        
                        <form method="POST" action="{{ route('bookings.pay', $booking) }}" class="space-y-4">
                            @csrf
                            
                            {{-- Dropdown Pilihan Metode --}}
                            <div class="space-y-2">
                                <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                                <select id="payment_method" name="payment_method" required
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                                    <option value="">-- Pilih Metode --</option>
                                    @foreach($paymentMethods as $method)
                                        <option value="{{ $method }}">{{ $method }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tombol Konfirmasi Pembayaran --}}
                            <div>
                                <button type="submit" 
                                        class="w-full inline-flex justify-center items-center px-6 py-3 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 transition shadow-md mt-4">
                                    Simulasi Bayar Sekarang
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
