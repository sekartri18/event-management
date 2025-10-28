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

                <h3 class="text-xl font-bold text-indigo-700 mb-6 border-b pb-2">Input Kode Tiket atau Scan</h3>

                {{-- Alpine.js State Management (Tambahkan state untuk scanner) --}}
                <div x-data="{
                    qrCode: '',
                    status: 'idle', // idle, loading, success, error, duplicate
                    message: 'Siap untuk input manual atau scan.',
                    ticketData: null,
                    isScanning: false, // State baru untuk status scanner kamera
                    html5QrCode: null, // Untuk menyimpan instance scanner
                    startScanner() {
                        this.isScanning = true;
                        this.message = 'Mempersiapkan kamera...';
                        this.$nextTick(() => {
                            this.html5QrCode = new Html5Qrcode('reader'); // 'reader' adalah ID div scanner
                            const config = { fps: 10, qrbox: { width: 250, height: 250 } };

                            // Fungsi callback saat QR code terdeteksi
                            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                                this.qrCode = decodedText; // Masukkan hasil scan ke input
                                this.stopScanner();      // Hentikan scanner
                                this.submitCheckIn();    // Langsung proses check-in
                            };

                            // Mulai scanning
                            this.html5QrCode.start({ facingMode: 'environment' }, config, qrCodeSuccessCallback)
                                .then(() => {
                                    this.message = 'Arahkan kamera ke QR Code.';
                                })
                                .catch(err => {
                                    this.message = `Gagal memulai kamera: ${err}`;
                                    this.isScanning = false;
                                });
                        });
                    },
                    stopScanner() {
                        if (this.html5QrCode && this.isScanning) {
                            this.html5QrCode.stop().then(() => {
                                this.isScanning = false;
                                this.message = 'Scanner dihentikan. Siap input manual.';
                                this.$refs.qrinput.focus(); // Fokus kembali ke input manual
                            }).catch(err => console.error('Gagal menghentikan scanner:', err));
                        } else {
                             this.isScanning = false; // Pastikan status benar jika scanner tidak aktif
                        }
                    },
                    submitCheckIn() {
                        // ... (Fungsi submitCheckIn yang sudah ada sebelumnya) ...
                        if (!this.qrCode) {
                            this.status = 'error';
                            this.message = 'Kode tiket tidak boleh kosong.';
                            return;
                        }

                        this.status = 'loading';
                        this.message = 'Memverifikasi tiket...';
                        this.ticketData = null;

                        axios.post('{{ route('events.checkin.process', $event) }}', { qr_code: this.qrCode })
                        .then(response => {
                            this.status = 'success';
                            this.message = response.data.message;
                            this.ticketData = response.data.ticket;
                            this.qrCode = '';
                            setTimeout(() => { this.status = 'idle'; this.ticketData = null; this.message = 'Siap untuk input manual atau scan.'; }, 5000);
                        })
                        .catch(error => {
                            this.status = 'error';
                            let msg = error.response?.data?.message || 'Verifikasi gagal. Coba lagi.';
                            this.message = msg;
                            this.ticketData = error.response?.data?.ticket || null;

                            if (error.response && error.response.status === 400 && msg.includes('sudah check-in')) {
                                this.status = 'duplicate';
                            }
                            setTimeout(() => { this.status = 'idle'; this.ticketData = null; this.message = 'Siap untuk input manual atau scan.'; }, 8000);
                        })
                        .finally(() => {
                             if (!this.isScanning) { // Hanya fokus jika tidak sedang scan
                                 this.$refs.qrinput.focus();
                             }
                        });
                    }
                }" @keyup.enter.prevent="submitCheckIn()" class="space-y-6">

                    {{-- 1. Area Scanner Kamera --}}
                    <div x-show="isScanning" x-transition class="border rounded-md mb-4" x-cloak>
                        <div id="reader" width="100%"></div>
                    </div>

                    {{-- 2. Tombol Start/Stop Scan --}}
                    <div class="text-center">
                        <button type="button" @click="isScanning ? stopScanner() : startScanner()"
                                class="inline-flex items-center px-4 py-2 border rounded-md font-semibold text-xs uppercase tracking-widest transition ease-in-out duration-150"
                                :class="isScanning ? 'bg-red-600 text-white hover:bg-red-700' : 'bg-green-600 text-white hover:bg-green-700'">
                            <span x-show="!isScanning">📷 Mulai Scan Kamera</span>
                            <span x-show="isScanning">⏹️ Hentikan Scan</span>
                        </button>
                    </div>

                    {{-- 3. Input QR Code Manual --}}
                    <div x-show="!isScanning" x-transition>
                        <x-input-label for="qr_code" :value="__('Kode Tiket / QR Code String')" />
                        <input x-model="qrCode" x-ref="qrinput" id="qr_code" class="block mt-1 w-full text-lg font-mono p-3 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" placeholder="Masukkan atau Scan Kode Tiket di sini" autofocus />
                    </div>

                    {{-- 4. Tombol Aksi Verifikasi Manual (Opsional, bisa dihapus jika scan langsung trigger submit) --}}
                    <button type="button" @click.prevent="submitCheckIn()" :disabled="status === 'loading'" x-show="!isScanning"
                            class="w-full justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            :class="{'opacity-50 cursor-not-allowed': status === 'loading' || status === 'success', 'bg-indigo-600 hover:bg-indigo-700': status !== 'success', 'bg-green-600 hover:bg-green-700': status === 'success'}">
                        <span x-show="status === 'loading'">Memproses...</span>
                        <span x-show="status === 'success'">Check-in Berhasil!</span>
                        <span x-show="status !== 'loading' && status !== 'success'">Verifikasi & Check-In (Manual)</span>
                    </button>

                    {{-- 5. Area Pesan Status & Hasil (Sama seperti sebelumnya) --}}
                    <div :class="{
                        'bg-yellow-100 border-yellow-500 text-yellow-700': status === 'idle',
                        'bg-blue-100 border-blue-500 text-blue-700': status === 'loading',
                        'bg-green-100 border-green-500 text-green-700': status === 'success',
                        'bg-red-100 border-red-500 text-red-700': status === 'error',
                        'bg-orange-100 border-orange-500 text-orange-700': status === 'duplicate',
                    }" class="border-l-4 p-4 rounded-md transition-all duration-300">
                        <p class="font-bold" x-text="message"></p>
                        <div x-show="ticketData" class="mt-3 pt-3 border-t border-gray-200 text-sm" x-cloak>
                             <p>Nama: <strong x-text="ticketData.nama_pemegang_tiket"></strong></p>
                             <p class="text-xs">Jenis Tiket: <span x-text="ticketData.ticket_type ? ticketData.ticket_type.nama_tiket : 'N/A'"></span></p>
                             <p class="text-xs" x-show="ticketData.tanggalCheckIn">Waktu Check-In: <span x-text="new Date(ticketData.tanggalCheckIn).toLocaleString('id-ID')"></span></p>
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

    {{-- Script untuk library html5-qrcode --}}
    {{-- Pastikan URL ini benar atau unduh library-nya ke direktori public Anda --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

</x-app-layout>