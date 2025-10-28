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
                    </div>
                    
                    {{-- Sidebar Aksi (Tampil hanya untuk Admin/Organizer yang memiliki izin) --}}
                    <div class="md:col-span-1 border-t md:border-t-0 md:border-l border-gray-100 pt-6 md:pt-0 md:pl-8 space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Aksi Event</h3>

                        {{-- Tombol Edit --}}
                        @can('update', $event)
                            <a href="{{ route('events.edit', $event) }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 transition shadow-md">
                                ✏️ Edit Event
                            </a>
                        @endcan

                        {{-- Tombol Hapus --}}
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

    {{-- START: FORM PEMBELIAN TIKET --}}
    
    {{-- KONDISI DITAMBAHKAN DI SINI: HANYA TAMPIL JIKA USER ADALAH ATTENDEE --}}
    @if(Auth::check() && Auth::user()->isAttendee())
        <div class="py-6 bg-gray-50"> 
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
                                                @foreach($event->ticketTypes as $type)
                                                    <option value="{{ $type->id }}" data-price="{{ $type->price }}" {{ $type->available_quantity <= 0 ? 'disabled' : '' }}>
                                                        {{ $type->name }} (Rp{{ number_format($type->price, 0, ',', '.') }})
                                                        @if($type->available_quantity <= 0) (HABIS) @endif
                                                    </option>
                                                @endforeach
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
                    
                    {{-- JS untuk Menambah Holder Tiket dan Menghitung Harga (dibiarkan di sini untuk fungsionalitas) --}}
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            let globalHolderIndex = 1;
                            const totalPriceDisplay = document.getElementById('total-price-display');
                            const ticketHoldersContainer = document.getElementById('ticket-holders-container');
                            
                            // FIX: ticketOptionsHtml sekarang dibuat dengan benar
                            const ticketOptionsHtml = `@foreach($event->ticketTypes as $type)
                                <option value="{{ $type->id }}" data-price="{{ $type->price }}" {{ $type->available_quantity <= 0 ? 'disabled' : '' }}>
                                    {{ $type->name }} (Rp{{ number_format($type->price, 0, ',', '.') }})
                                    @if($type->available_quantity <= 0) (HABIS) @endif
                                </option>
                            @endforeach`;

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
                                totalPriceDisplay.textContent = formatRupiah(total);
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
                                const holderRows = ticketHoldersContainer.querySelectorAll('.holder-row');
                                holderRows.forEach(row => {
                                    const removeButton = row.querySelector('.remove-holder');
                                    if (removeButton) {
                                        if (holderRows.length > 1) {
                                            removeButton.classList.remove('hidden');
                                        } else {
                                            removeButton.classList.add('hidden');
                                        }
                                    }
                                });
                            }
                            
                            document.getElementById('add-holder-btn').addEventListener('click', function() {
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
                            
                            // Pasang listener pada baris pertama yang sudah ada di HTML
                            document.querySelector('.holder-row .remove-holder').addEventListener('click', function() {
                                this.closest('.holder-row').remove();
                                updateTotalPrice();
                            });
                            document.querySelector('.holder-row .ticket-type-select').addEventListener('change', handleTicketTypeChange);

                            updateTotalPrice();
                        });
                    </script>
                </div>
            </div>
        </div>
    {{-- ENDIF: Form pembelian hanya untuk Attendee --}}
    @endif 
    {{-- END: FORM PEMBELIAN TIKET --}}
    
    </x-app-layout>