{{-- resources/views/events/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Event: ' . $event->nama_event) }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('events.update', $event) }}">
                    @csrf
                    @method('PATCH') {{-- Menggunakan PATCH method untuk update --}}

                    <div class="mb-4">
                        <label for="nama_event" class="block text-sm font-medium text-gray-700">
                            Nama Event
                        </label>
                        <x-text-input type="text" name="nama_event" id="nama_event" 
                               value="{{ old('nama_event', $event->nama_event) }}" 
                               class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('nama_event')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <label for="lokasi" class="block text-sm font-medium text-gray-700">
                            Lokasi
                        </label>
                        <x-text-input type="text" name="lokasi" id="lokasi" 
                               value="{{ old('lokasi', $event->lokasi) }}" 
                               class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">
                            Tanggal Mulai
                        </label>
                        <x-text-input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                               value="{{ old('tanggal_mulai', $event->tanggal_mulai) }}" 
                               class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700">
                            Tanggal Selesai
                        </label>
                        <x-text-input type="date" name="tanggal_selesai" id="tanggal_selesai" 
                               value="{{ old('tanggal_selesai', $event->tanggal_selesai) }}" 
                               class="mt-1 block w-full" required />
                        <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="4"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('deskripsi', $event->deskripsi) }}</textarea>
                        <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            Status
                        </label>
                        <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @php $currentStatus = old('status', $event->status); @endphp
                            <option value="upcoming" {{ $currentStatus == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="ongoing" {{ $currentStatus == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="finished" {{ $currentStatus == 'finished' ? 'selected' : '' }}>Finished</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div class="flex items-center space-x-3">
                         <button type="submit" 
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-semibold text-sm">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('events.show', $event) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition ease-in-out duration-150">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>