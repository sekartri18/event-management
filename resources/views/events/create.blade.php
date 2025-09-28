{{-- resources/views/events/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Event Baru') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('events.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="nama_event" class="block text-sm font-medium text-gray-700">
                            Nama Event
                        </label>
                        <input type="text" name="nama_event" id="nama_event" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label for="lokasi" class="block text-sm font-medium text-gray-700">
                            Lokasi
                        </label>
                        <input type="text" name="lokasi" id="lokasi" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">
                            Tanggal Mulai
                        </label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700">
                            Tanggal Selesai
                        </label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="4"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            Status
                        </label>
                        <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="upcoming">Upcoming</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="finished">Finished</option>
                        </select>
                    </div>

                    <button type="submit" 
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
