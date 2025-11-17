<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
            {{ __('Detail Event') }}
        </h2>
    </x-slot>

    {{-- START: DETAIL EVENT --}}
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Menampilkan pesan sukses/error dari Controller (misal setelah Delete Event) --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @elseif (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                
                {{-- BLOK GAMBAR UTAMA --}}
                @if ($event->gambar)
                    <img src="{{ Storage::url($event->gambar) }}" alt="{{ $event->nama_event }}" class="w-full h-80 object-cover rounded-t-xl">
                @else
                    <div class="w-full h-80 bg-gray-100 flex items-center justify-center rounded-t-xl border-b border-gray-200">
                        <p class="text-gray-500 text-lg">Tidak Ada Gambar Utama</p>
                    </div>
                @endif

                
                {{-- Event Header --}}
                <div class="p-6 sm:px-10 sm:py-8 bg-indigo-50 border-b border-indigo-200">
                    <h1 class="text-3xl font-extrabold text-indigo-800 mb-2 leading-tight">
                        {{ $event->nama_event }}
                    </h1>
                    <p class="text-indigo-600 font-medium flex items-center space-x-2">
                        <span>🗓️</span> 
                        {{-- Menggunakan $casts dari Model Event (jika ada) --}}
                        <span>{{ \Carbon\Carbon::parse($event->tanggal_mulai)->isoFormat('D MMMM YYYY') }} - {{ \Carbon\Carbon::parse($event->tanggal_selesai)->isoFormat('D MMMM YYYY') }}</span>
                    </p>
                </div>

                <div class="p-6 sm:px-10 sm:py-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- Detail Section --}}
                    <div class="md:col-span-2 space-y-6">
                        
                        <div class="border-b pb-4">
                            <p class="text-sm font-semibold text-gray-500 mb-1">Deskripsi Event</p>
                            <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $event->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-500 mb-1">Lokasi</p>
                                <p class="text-gray-800 font-medium flex items-center space-x-2">
                                    <span class="text-purple-500">📍</span>
                                    <span>{{ $event->lokasi }}</span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 mb-1">Status</p>
                                @php
                                    $statusClass = match($event->status) {
                                        'upcoming' => 'bg-blue-200 text-blue-800',
                                        'ongoing' => 'bg-green-200 text-green-800',
                                        'finished' => 'bg-red-200 text-red-800',
                                        default => 'bg-gray-200 text-gray-800',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $statusClass }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <p class="text-sm font-semibold text-gray-500 mb-1">Diselenggarakan oleh</p>
                            <p class="text-gray-800 font-medium">{{ $event->organizer->name ?? 'N/A' }}</p>
                        </div>

                        <!-- === BAGIAN BARU UNTUK KALENDER === -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <h4 class="text-base font-semibold text-gray-700 mb-2">Tambahkan ke Kalender Anda:</h4>
                            <div class="flex flex-wrap gap-3">
                                {{-- Variabel $calendarLinks dari EventController --}}
                                <a href="{{ $calendarLinks['google'] }}" target="_blank"
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition">
                                    <svg class="w-5 h-5 mr-1.5 -ml-1" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.3 4.2c-0.1-0.1-0.2-0.2-0.3-0.2C19.8 3.9 19.6 3.9 19.4 3.9H4.6C4.4 3.9 4.2 3.9 4 4c-0.1 0.1-0.2 0.1-0.3 0.2C3.5 4.4 3.4 4.7 3.4 5v14c0 0.3 0.1 0.6 0.3 0.8s0.5 0.3 0.8 0.3h14c0.3 0 0.6-0.1 0.8-0.3s0.3-0.5 0.3-0.8V5C20.6 4.7 20.5 4.4 20.3 4.2zM12 12.3H8.7v3.4H5.2V8.7h7.3c1.3 0 2.3 0.4 3 1.1s1 1.6 1 2.8c0 1.2-0.3 2.1-1 2.8C14.8 16.1 13.6 16.5 12 16.5c-1.2 0-2.3-0.2-3.3-0.7v-2.1c0.9 0.4 2 0.7 3.3 0.7c1.4 0 2.1-0.5 2.1-1.4c0-0.9-0.7-1.4-2.1-1.4H8.7v-2.9h3.3V12.3z"></path></svg>
                                    Google Calendar
                                </a>
                                <a href="{{ $calendarLinks['ics'] }}"
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                                    <svg class="w-5 h-5 mr-1.5 -ml-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5h8v8H6V7z" clip-rule="evenodd"></path></svg>
                                    Apple/Outlook (.ics)
                                </a>
                            </div>
                        </div>
                        <!-- === AKHIR BAGIAN KALENDER === -->

                    </div>
                    
                    {{-- Sidebar Aksi (Tampil hanya untuk Admin/Organizer yang memiliki izin) --}}
                    <div class="md:col-span-1 border-t md:border-t-0 md:border-l border-gray-100 pt-6 md:pt-0 md:pl-8 space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Aksi Event</h3>

                        {{-- Tombol Edit dan Kelola Tiket (Hanya untuk organizer yang punya akses) --}}
                        @can('update', $event)
                            <a href="{{ route('events.edit', $event) }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 transition shadow-md">
                                ✏️ Edit Event
                            </a>
                            
                            {{-- Tombol Kelola Tipe Tiket --}}
                            <a href="{{ route('events.tickets.index', $event) }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-600 transition shadow-md">
                                🎫 Kelola Tipe Tiket
                            </a>

                            {{-- Tombol Daftar Peserta / Attendees List --}}
                            <a href="{{ route('events.checkin.index', $event) }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition shadow-md">
                                👥 Daftar Peserta
                            </a>

                            {{-- Tombol Scanner / Check-in --}}
                            <a href="{{ route('events.checkin.scanner', $event) }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition shadow-md">
                                📱 Scan Check-in Tiket
                            </a>
                        @endcan

                        {{-- Tombol Hapus (Akses oleh delete policy) --}}
                        @can('delete', $event)
                            <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('ANDA YAKIN? Menghapus event ini akan menghapus semua tiket dan booking terkait!');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition shadow-md">
                                    🗑️ Hapus Event
                                </button>
                            </form>
                        @endcan
                        
                        {{-- Tombol Kembali --}}
                        <a href="{{ route('events.index') }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition shadow-sm">
                            &larr; Kembali ke Daftar
                        </a>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    {{-- END: DETAIL EVENT --}}


    {{-- ====================================================================== --}}
    {{-- =           MULAI BLOK KODE BARU (UNTUK ULASAN EVENT)            = --}}
    {{-- ====================================================================== --}}
    
    <!-- === BAGIAN ULASAN EVENT === -->
    <div class="py-6 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 sm:px-10 sm:py-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Ulasan Event</h3>
    
                <!-- Tampilkan Rata-rata Rating -->
                <div class="mb-6 p-4 bg-indigo-50 border border-indigo-200 rounded-lg flex items-center justify-center space-x-2">
                    <span class="text-4xl font-bold text-indigo-700">
                        {{-- Variabel $averageRating dari EventController (method show) --}}
                        {{ number_format($averageRating, 1) }}
                    </span>
                    <span class="text-yellow-400 text-3xl">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= round($averageRating))
                                ★ <!-- Bintang penuh -->
                            @else
                                ☆ <!-- Bintang kosong -->
                            @endif
                        @endfor
                    </span>
                    <span class="text-lg text-indigo-600">
                        ({{ $event->reviews->count() }} ulasan)
                    </span>
                </div>
    
                <!-- Form untuk Submit Review (Hanya jika $canReview true) -->
                {{-- Variabel $canReview dari EventController (method show) --}}
                @if ($canReview)
                    <form action="{{ route('reviews.store', $event) }}" method="POST" class="mb-8 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
                        @csrf
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Bagaimana event ini menurut Anda?</h4>
                        
                        <!-- Input Rating (Bintang) -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rating Anda (1-5)</label>
                            <select name="rating" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                                <option value="">-- Pilih Rating --</option>
                                <option value="5">★★★★★ (5 - Luar Biasa)</option>
                                <option value="4">★★★★☆ (4 - Bagus)</option>
                                <option value="3">★★★☆☆ (3 - Cukup)</option>
                                <option value="2">★★☆☆☆ (2 - Kurang)</option>
                                <option value="1">★☆☆☆☆ (1 - Buruk)</option>
                            </select>
                            @error('rating') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
    
                        <!-- Input Komentar -->
                        <div class="mb-4">
                            {{-- PERBAIKAN: 'name' dari form adalah 'comment' agar sesuai Controller --}}
                            <label for="comment" class="block text-sm font-medium text-gray-700">Komentar Anda (Opsional)</label>
                            <textarea name="comment" id="comment" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Ceritakan pengalaman Anda...">{{ old('comment') }}</textarea>
                            @error('comment') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
    
                        <x-primary-button type="submit">
                            Kirim Review
                        </x-primary-button>
                    </form>
                @elseif(session('review_error'))
                    {{-- Tampilkan pesan jika user tidak bisa review tapi event sudah selesai --}}
                    <div class="mb-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-center">
                        <p class="text-yellow-800">{{ session('review_error') }}</p>
                    </div>
                @endif
                <!-- Akhir Form Submit Review -->
    
                <!-- Daftar Review yang Sudah Ada -->
                <div class="space-y-6">
                    {{-- Variabel $event->reviews (dengan ->attendee) dari EventController (method show) --}}
                    @forelse ($event->reviews->sortByDesc('created_at') as $review)
                        <div class="flex space-x-4 border-b border-gray-200 pb-4">
                            <div class="flex-shrink-0">
                                <!-- Placeholder avatar sederhana -->
                                <div class="w-10 h-10 rounded-full bg-indigo-200 text-indigo-700 flex items-center justify-center font-bold">
                                    {{-- PERBAIKAN: Gunakan relasi 'attendee' (dari Model Review) --}}
                                    {{ strtoupper(substr($review->attendee->name, 0, 1)) }}
                                </div>
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-center">
                                    {{-- PERBAIKAN: Gunakan relasi 'attendee' --}}
                                    <span class="font-semibold text-gray-800">{{ $review->attendee->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="text-yellow-400 my-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->rating)
                                            ★ <!-- Bintang penuh -->
                                        @else
                                            ☆ <!-- Bintang kosong -->
                                        @endif
                                    @endfor
                                </div>
                                {{-- PERBAIKAN: Gunakan kolom 'komentar' (dari Model Review) --}}
                                <p class="text-gray-700">{{ $review->komentar }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-4">
                            Belum ada ulasan untuk event ini.
                        </div>
                    @endforelse
                </div>
                <!-- Akhir Daftar Review -->
    
            </div>
        </div>
    </div>
    <!-- === AKHIR BAGIAN ULASAN EVENT === -->
    {{-- ====================================================================== --}}


    {{-- START: FORM PEMBELIAN TIKET --}}
    
    {{-- KONDISI DITAMBAHKAN DI SINI: HANYA TAMPIL JIKA USER ADALAH ATTENDEE --}}
    @if(Auth::check() && Auth::user()->isAttendee())

        {{-- ====================================================================== --}}
        {{-- =          PERBAIKAN PARSE ERROR: BLOK PHP DIPINDAHKAN KE SINI         = --}}
        {{-- ====================================================================== --}}
        @php
            // Kita buat string HTML opsi di sini, di dalam PHP yang aman,
            // untuk menghindari error parser Blade di dalam blok <script>
            $optionsHtml = '';
            foreach($event->ticketTypes as $type) {
                // PERBAIKAN: Menggunakan magic property 'price' dan 'available_quantity' dari Model TicketType
                $priceFormatted = number_format($type->price, 0, ',', '.');
                $disabled = $type->available_quantity <= 0 ? 'disabled' : '';
                $soldOut = $type->available_quantity <= 0 ? ' (HABIS)' : '';
                
                // Gunakan kutip tunggal untuk string PHP agar kutip ganda HTML aman
                $optionsHtml .= '<option value="' . $type->id . '" data-price="' . $type->price . '" ' . $disabled . '>';
                $optionsHtml .= $type->nama_tiket . ' (Rp' . $priceFormatted . ')' . $soldOut;
                $optionsHtml .= '</option>';
            }
        @endphp
        {{-- ====================================================================== --}}
        {{-- =                        AKHIR BLOK PHP                              = --}}
        {{-- ====================================================================== --}}


        <div class="py-6 bg-white border-t border-gray-100"> 
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Pesan Tiket Event</h3>
                    
                    {{-- Form Pembelian Tiket --}}
                    <form id="booking-form" method="POST" action="{{ route('bookings.store', $event) }}">
                        @csrf
                        
                        @if($event->ticketTypes->isEmpty())
                            <p class="text-red-500">Saat ini belum ada tipe tiket yang tersedia untuk event ini.</p>
                        @else
                            
                            <div class="space-y-4 mb-4 p-4 border rounded-lg bg-gray-50">
                                <h4 class="text-lg font-semibold text-gray-700">Daftar Tiket Yang Dibeli:</h4>
                                
                                {{-- Container Utama Holder Tiket --}}
                                <div id="ticket-holders-container" class="space-y-3">
                                    {{-- Baris Tiket Default (Index 0) --}}
                                    <div class="flex space-x-2 holder-row items-center border-b pb-3" data-price="0">
                                        <div class="w-1/3">
                                            <label class="block text-xs font-medium text-gray-500">Jenis Tiket</label>
                                            <select name="holders[0][type_id]" required class="ticket-type-select border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                                                <option value="" data-price="0">-- Pilih Jenis Tiket --</option>
                                                
                                                {{-- ================================================ --}}
                                                {{-- =   PERBAIKAN PARSE ERROR: GANTI @foreach        = --}}
                                                {{-- ================================================ --}}
                                                {!! $optionsHtml !!}
                                                {{-- ================================================ --}}

                                            </select>
                                        </div>
                                        <div class="w-2/3">
                                            <label class="block text-xs font-medium text-gray-500">Nama Pemegang Tiket</label>
                                            <input type="text" name="holders[0][name]" placeholder="Nama Lengkap Pemegang Tiket" required class="flex-grow border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full"/>
                                        </div>
                                        <button type="button" class="text-red-500 hover:text-red-700 remove-holder hidden px-2 py-2 mt-4">Hapus</button>
                                    </div>
                                </div>

                                {{-- Tombol Tambah Tiket --}}
                                <button type="button" id="add-holder-btn" class="mt-4 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                    + Tambah Tiket Baru
                                </button>
                            </div>
                            
                            {{-- DISPLAY TOTAL HARGA --}}
                            <div class="mt-8 p-4 bg-indigo-100 rounded-lg border-l-4 border-indigo-600 shadow-md">
                                <h4 class="text-lg font-semibold text-gray-700 mb-1">Total yang Harus Dibayar:</h4>
                                <p class="text-4xl font-extrabold text-indigo-800">
                                    Rp<span id="total-price-display">0</span>
                                </p>
                            </div>

                            <div class="mt-8">
                                <x-primary-button type="submit" id="submit-booking-btn" class="bg-blue-600 hover:bg-blue-700">
                                    Beli Tiket Sekarang
                                </x-primary-button>
                            </div>
                        @endif
                    </form>
                    
                    {{-- JS untuk Menambah Holder Tiket dan Menghitung Harga --}}
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            // Pastikan script ini hanya berjalan jika form-nya ada di halaman
                            const bookingForm = document.getElementById('booking-form');
                            if (bookingForm) { 
                                let globalHolderIndex = 1;
                                const totalPriceDisplay = document.getElementById('total-price-display');
                                const ticketHoldersContainer = document.getElementById('ticket-holders-container');
                                
                                {{-- ====================================================================== --}}
                                {{-- =   PERBAIKAN PARSE ERROR: GANTI @foreach DENGAN @json               = --}}
                                {{-- ====================================================================== --}}
                                // Ambil daftar tiket dari PHP dan format sebagai string JS
                                const ticketOptionsHtml = @json($optionsHtml);
                                {{-- ====================================================================== --}}


                                function formatRupiah(number) {
                                    return new Intl.NumberFormat('id-ID').format(number);
                                }

                                function updateTotalPrice() {
                                    let total = 0;
                                    document.querySelectorAll('.holder-row').forEach(row => {
                                        const priceStr = row.dataset.price;
                                        const price = parseFloat(priceStr);
                                        
                                        if (!isNaN(price) && price > 0) {
                                            total += price;
                                        }
                                    });
                                    // Pastikan elemennya ada sebelum mengatur textContent
                                    if (totalPriceDisplay) {
                                        totalPriceDisplay.textContent = formatRupiah(total);
                                    }
                                    toggleRemoveButtons();
                                }

                                function handleTicketTypeChange(event) {
                                    const selectElement = event.target;
                                    const selectedOption = selectElement.options[selectElement.selectedIndex];
                                    const price = selectedOption.dataset.price || '0'; 
                                    
                                    const row = selectElement.closest('.holder-row');
                                    row.dataset.price = price; 
                                    
                                    updateTotalPrice();
                                }

                                function toggleRemoveButtons() {
                                    if (!ticketHoldersContainer) return; // Guard clause
                                    const holderRows = ticketHoldersContainer.querySelectorAll('.holder-row');
                                    holderRows.forEach(row => {
                                        const removeButton = row.querySelector('.remove-holder');
                                        if (removeButton) {
                                            // Sembunyikan tombol hapus jika hanya ada satu baris
                                            if (holderRows.length > 1) {
                                                removeButton.classList.remove('hidden');
                                                removeButton.style.display = 'block'; // Ensure it's displayed if needed
                                            } else {
                                                removeButton.classList.add('hidden');
                                                removeButton.style.display = 'none'; // Ensure it's hidden
                                            }
                                        }
                                    });
                                }
                                
                                // Cek jika tombol 'add' ada sebelum menambah event listener
                                const addHolderBtn = document.getElementById('add-holder-btn');
                                if (addHolderBtn) {
                                    addHolderBtn.addEventListener('click', function() {
                                        const newRow = document.createElement('div');
                                        newRow.className = 'flex space-x-2 holder-row items-center border-b pb-3';
                                        newRow.dataset.price = '0'; 
                                        
                                        newRow.innerHTML = `
                                            <div class="w-1/3">
                                                <label class="block text-xs font-medium text-gray-500">Jenis Tiket</label>
                                                <select name="holders[${globalHolderIndex}][type_id]" required class="ticket-type-select border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                                                    <option value="" data-price="0">-- Pilih Jenis Tiket --</option>
                                                    ${ticketOptionsHtml}
                                                </select>
                                            </div>
                                            <div class="w-2/3">
                                                <label class="block text-xs font-medium text-gray-500">Nama Pemegang Tiket</label>
                                                <input type="text" name="holders[${globalHolderIndex}][name]" placeholder="Nama Lengkap Pemegang Tiket" required class="flex-grow border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full"/>
                                            </div>
                                            <button type="button" class="text-red-500 hover:text-red-700 remove-holder px-2 py-2 mt-4">Hapus</button>
                                        `;
                                        
                                        newRow.querySelector('.remove-holder').addEventListener('click', function() {
                                            newRow.remove();
                                            updateTotalPrice(); 
                                        });

                                        newRow.querySelector('.ticket-type-select').addEventListener('change', handleTicketTypeChange);

                                        ticketHoldersContainer.appendChild(newRow);
                                        globalHolderIndex++;
                                        updateTotalPrice();
                                    });
                                }
                                
                                // Pasang listener pada baris pertama yang sudah ada di HTML
                                const firstRemoveButton = document.querySelector('.holder-row .remove-holder');
                                if(firstRemoveButton) {
                                    firstRemoveButton.addEventListener('click', function() {
                                        this.closest('.holder-row').remove();
                                        updateTotalPrice();
                                    });
                                }
                                
                                const firstTicketSelect = document.querySelector('.holder-row .ticket-type-select');
                                if(firstTicketSelect) {
                                    firstTicketSelect.addEventListener('change', handleTicketTypeChange);
                                }


                                updateTotalPrice();
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    @endif 
    {{-- END: FORM PEMBELIAN TIKET --}}
    
</x-app-layout>