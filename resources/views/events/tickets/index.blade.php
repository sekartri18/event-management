<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
            {{ __('Kelola Tipe Tiket Event: ' . $event->nama_event) }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- KOLOM KIRI: TAMBAH TIPE TIKET BARU --}}
                <div class="lg:col-span-1 bg-white overflow-hidden shadow-lg sm:rounded-lg h-fit sticky top-6">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-indigo-700 mb-4 border-b pb-2">➕ Tambah Tipe Tiket Baru</h3>
                        
                        <form method="POST" action="{{ route('events.tickets.store', $event) }}">
                            @csrf

                            <div class="mb-4">
                                <x-input-label for="nama_tiket" :value="__('Nama Tiket (e.g. VIP, Regular)')" />
                                <x-text-input id="nama_tiket" class="block mt-1 w-full" type="text" name="nama_tiket" :value="old('nama_tiket')" required autofocus />
                                <x-input-error :messages="$errors->get('nama_tiket')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="harga" :value="__('Harga (Rp)')" />
                                <x-text-input id="harga" class="block mt-1 w-full" type="number" step="1000" min="0" name="harga" :value="old('harga')" required />
                                <x-input-error :messages="$errors->get('harga')" class="mt-2" />
                            </div>

                            <div class="mb-6">
                                <x-input-label for="kuota" :value="__('Kuota / Jumlah Tersedia')" />
                                <x-text-input id="kuota" class="block mt-1 w-full" type="number" min="1" name="kuota" :value="old('kuota')" required />
                                <x-input-error :messages="$errors->get('kuota')" class="mt-2" />
                            </div>

                            <x-primary-button class="w-full bg-green-600 hover:bg-green-700">
                                {{ __('Simpan Tipe Tiket') }}
                            </x-primary-button>
                        </form>
                    </div>
                </div>

                {{-- KOLOM KANAN: DAFTAR TIPE TIKET --}}
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Daftar Tipe Tiket ({{ $ticketTypes->count() }})</h3>
                        
                        @forelse ($ticketTypes as $type)
                            <div class="mb-4 p-4 border border-gray-200 rounded-lg shadow-sm hover:border-indigo-400 transition-all">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-lg font-bold text-gray-900">{{ $type->nama_tiket }}</p>
                                        <p class="text-sm text-gray-600">
                                            Harga: <span class="font-semibold text-indigo-600">Rp{{ number_format($type->harga, 0, ',', '.') }}</span>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-gray-500">Kuota Tersedia</p>
                                        <p class="text-2xl font-extrabold text-green-700">{{ $type->kuota }}</p>
                                    </div>
                                </div>
                                
                                {{-- Form Edit/Hapus (Disembunyikan, menggunakan modal atau toggle di dunia nyata) --}}
                                <div x-data="{ open: false }" class="mt-3">
                                    <button @click="open = !open" class="text-xs text-blue-500 hover:text-blue-700 font-medium underline">
                                        Edit / Hapus
                                    </button>
                                    
                                    <div x-show="open" x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-200"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95" 
                                         class="mt-4 p-4 bg-gray-50 border rounded-lg">
                                        
                                        <form method="POST" action="{{ route('events.tickets.update', ['event' => $event, 'ticket' => $type]) }}" class="space-y-3">
                                            @csrf
                                            @method('PATCH')

                                            <p class="text-sm font-bold text-gray-700 mb-2">Edit Tipe Tiket</p>

                                            <x-input-label for="edit_nama_{{ $type->id }}" :value="__('Nama Tiket')" />
                                            <x-text-input id="edit_nama_{{ $type->id }}" class="block w-full" type="text" name="nama_tiket" :value="old('nama_tiket', $type->nama_tiket)" required />

                                            <x-input-label for="edit_harga_{{ $type->id }}" :value="__('Harga')" />
                                            <x-text-input id="edit_harga_{{ $type->id }}" class="block w-full" type="number" step="1000" min="0" name="harga" :value="old('harga', $type->harga)" required />

                                            <x-input-label for="edit_kuota_{{ $type->id }}" :value="__('Kuota')" />
                                            <x-text-input id="edit_kuota_{{ $type->id }}" class="block w-full" type="number" min="1" name="kuota" :value="old('kuota', $type->kuota)" required />

                                            <div class="flex justify-between items-center pt-3">
                                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-600 transition">
                                                    Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>

                                        {{-- Form Delete di bawah form Edit --}}
                                        <form method="POST" action="{{ route('events.tickets.destroy', ['event' => $event, 'ticket' => $type]) }}" 
                                              onsubmit="return confirm('YAKIN ingin menghapus tipe tiket {{ $type->nama_tiket }}? Ini tidak bisa dibatalkan.');"
                                              class="mt-3">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button type="submit" class="text-xs bg-red-500 hover:bg-red-600">
                                                Hapus Tipe Tiket Ini
                                            </x-danger-button>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center p-6 bg-gray-50 border-dashed border-gray-300 rounded-lg">
                                <p class="text-gray-500">Belum ada tipe tiket yang terdaftar untuk event ini. Silakan tambahkan!</p>
                            </div>
                        @endforelse

                        <div class="mt-6 border-t pt-4 text-right">
                             <a href="{{ route('events.show', $event) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition shadow-sm">
                                &larr; Kembali ke Detail Event
                            </a>
                        </div>
                        
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>