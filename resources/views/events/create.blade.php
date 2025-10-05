<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
            {{ __('Buat Event Baru') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white border-b border-gray-200">
                    
                    <h3 class="text-xl font-bold text-indigo-700 mb-6 border-b pb-2">Informasi Dasar Event</h3>

                    {{-- FORMULIR PEMBUATAN EVENT --}}
                    {{-- Arahkan ke route events.store untuk menyimpan data --}}
                    <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="nama_event" :value="__('Nama Event')" />
                            <x-text-input id="nama_event" class="block mt-1 w-full" type="text" name="nama_event" :value="old('nama_event')" required autofocus />
                            <x-input-error :messages="$errors->get('nama_event')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="deskripsi" :value="__('Deskripsi Lengkap Event')" />
                            <textarea id="deskripsi" name="deskripsi" rows="5" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>{{ old('deskripsi') }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="lokasi" :value="__('Lokasi (Alamat atau Nama Tempat)')" />
                            <x-text-input id="lokasi" class="block mt-1 w-full" type="text" name="lokasi" :value="old('lokasi')" required />
                            <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <x-input-label for="tanggal_mulai" :value="__('Tanggal Mulai')" />
                                <x-text-input id="tanggal_mulai" class="block mt-1 w-full" type="datetime-local" name="tanggal_mulai" :value="old('tanggal_mulai')" required />
                                <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="tanggal_selesai" :value="__('Tanggal Selesai')" />
                                <x-text-input id="tanggal_selesai" class="block mt-1 w-full" type="datetime-local" name="tanggal_selesai" :value="old('tanggal_selesai')" required />
                                <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="status" :value="__('Status Event')" />
                            <select id="status" name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                <option value="upcoming" {{ old('status', 'upcoming') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="finished" {{ old('status') == 'finished' ? 'selected' : '' }}>Finished</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="gambar" :value="__('Gambar Event (Opsional)')" />
                            <x-text-input id="gambar" class="block mt-1 w-full p-2 border" type="file" name="gambar" accept="image/*" />
                            <x-input-error :messages="$errors->get('gambar')" class="mt-2" />
                        </div>
                        
                        {{-- Catatan: Form ini tidak menyertakan input untuk Tiket/Ticket Type karena biasanya ini dikelola di halaman terpisah setelah Event dibuat. --}}

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition ease-in-out duration-150 mr-3">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Simpan Event') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>