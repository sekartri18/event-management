{{-- resources/views/events/checkin/scanner.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
            {{ __('Check-in Tiket (QR Scanner) - ' . $event->nama_event) }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                
                <h3 class="text-xl font-bold text-indigo-700 mb-6 border-b pb-2">Input Kode Tiket</h3>
                
                {{-- Alpine.js State Management --}}
                <div x-data="{
                    qrCode: '',
                    status: 'idle', // idle, loading, success, error, duplicate
                    message: 'Siap untuk scan. Fokuskan pada kolom input.',
                    ticketData: null,
                    submitCheckIn() {
                        if (!this.qrCode) {
                            this.status = 'error';
                            this.message = 'QR Code tidak boleh kosong.';
                            return;
                        }
                        
                        this.status = 'loading';
                        this.message = 'Memverifikasi tiket...';
                        this.ticketData = null;

                        // Menggunakan Axios (pastikan sudah diimpor di resources/js/bootstrap.js)
                        axios.post('{{ route('events.checkin.process', $event) }}', {
                            qr_code: this.qrCode
                        })
                        .then(response => {
                            this.status = 'success';
                            this.message = response.data.message;
                            this.ticketData = response.data.ticket;
                            this.qrCode = ''; 
                            setTimeout(() => { this.status = 'idle'; this.ticketData = null; this.message = 'Siap untuk scan. Fokuskan pada kolom input dan tekan ENTER.'; }, 5000);
                        })
                        .catch(error => {
                            this.status = 'error';
                            let msg = error.response.data.message || 'Verifikasi gagal. Coba lagi.';
                            this.message = msg;
                            this.ticketData = error.response.data.ticket || null;

                            if (error.response && error.response.status === 400 && msg.includes('sudah check-in')) {
                                this.status = 'duplicate';
                            }
                            
                            setTimeout(() => { this.status = 'idle'; this.ticketData = null; this.message = 'Siap untuk scan. Fokuskan pada kolom input dan tekan ENTER.'; }, 8000);
                        })
                        .finally(() => {
                            this.$refs.qrinput.focus();
                        });
                    }
                }" @keyup.enter.prevent="submitCheckIn()" class="space-y-6">

                    {{-- 1. Input QR Code (Simulasi Scan) --}}
                    <div>
                        <x-input-label for="qr_code" :value="__('Kode Tiket / QR Code String')" />
                        {{-- Menggunakan input HTML biasa dengan class Blade untuk styling --}}
                        <input x-model="qrCode" x-ref="qrinput" id="qr_code" class="block mt-1 w-full text-lg font-mono p-3 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" placeholder="Masukkan atau Scan Kode Tiket di sini" autofocus />
                    </div>

                    {{-- 2. Tombol Aksi (PERBAIKAN ERROR DI SINI) --}}
                    {{-- Menggunakan button HTML biasa dan kelas dari x-primary-button --}}
                    <button @click.prevent="submitCheckIn()" :disabled="status === 'loading'" 
                            class="w-full justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            :class="{'opacity-50 cursor-not-allowed': status === 'loading' || status === 'success', 'bg-indigo-600 hover:bg-indigo-700': status !== 'success', 'bg-green-600 hover:bg-green-700': status === 'success'}">

                        <span x-show="status === 'loading'">Memproses...</span>
                        <span x-show="status === 'success'">Check-in Berhasil!</span>
                        <span x-show="status !== 'loading' && status !== 'success'">Verifikasi & Check-In</span>
                    </button>
                    
                    {{-- 3. Area Pesan Status & Hasil --}}
                    <div :class="{
                        'bg-yellow-100 border-yellow-500 text-yellow-700': status === 'idle',
                        'bg-blue-100 border-blue-500 text-blue-700': status === 'loading',
                        'bg-green-100 border-green-500 text-green-700': status === 'success',
                        'bg-red-100 border-red-500 text-red-700': status === 'error',
                        'bg-orange-100 border-orange-500 text-orange-700': status === 'duplicate',
                    }" class="border-l-4 p-4 rounded-md transition-all duration-300">
                        <p class="font-bold" x-text="message"></p>
                        
                        {{-- Detail Tiket Setelah Verifikasi --}}
                        <div x-show="ticketData" class="mt-3 pt-3 border-t border-gray-200" x-cloak>
                             <p class="text-sm font-semibold">Nama Peserta: <span x-text="ticketData.nama_pemegang_tiket" :class="{'text-green-800': status === 'success' || status === 'duplicate', 'text-red-800': status === 'error'}"></span></p>
                             {{-- Memuat relasi nested (ticketType) mungkin memerlukan penyesuaian jika modelnya tidak di-load di controller. --}}
                             <p class="text-xs">Jenis: <span x-text="ticketData.ticket_type ? ticketData.ticket_type.nama_tiket : 'N/A'"></span></p>
                             <p class="text-xs">Status Awal: <span x-text="ticketData.statusCheckIn" class="uppercase"></span></p>
                             <p class="text-xs" x-show="ticketData.tanggalCheckIn">Waktu Check-In: <span x-text="new Date(ticketData.tanggalCheckIn).toLocaleTimeString('id-ID', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })"></span></p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 border-t pt-4 text-center">
                    <a href="{{ route('events.checkin.index', $event) }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                        Lihat Semua Daftar Peserta
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>