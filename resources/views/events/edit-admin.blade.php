@extends('layouts.admin-layout')

@section('header')
<h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
    {{ __('Edit Event - Admin') }}
</h2>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        
        {{-- PENTING: enctype="multipart/form-data" sudah ada dan sudah benar --}}
        <form method="POST" action="{{ route('events.update', $event) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH') 

            {{-- 1. NAMA EVENT --}}
            <div class="mb-4">
                <label for="nama_event" class="block text-sm font-medium text-gray-700">
                    Nama Event
                </label>
                <x-text-input type="text" name="nama_event" id="nama_event" 
                       value="{{ old('nama_event', $event->nama_event) }}" 
                       class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('nama_event')" class="mt-2" />
            </div>

            {{-- 2. LOKASI --}}
            <div class="mb-4">
                <label for="lokasi" class="block text-sm font-medium text-gray-700">
                    Lokasi
                </label>
                <x-text-input type="text" name="lokasi" id="lokasi" 
                       value="{{ old('lokasi', $event->lokasi) }}" 
                       class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
            </div>

            {{-- 3. TANGGAL MULAI --}}
            <div class="mb-4">
                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">
                    Tanggal Mulai
                </label>
                <x-text-input type="datetime-local" name="tanggal_mulai" id="tanggal_mulai" 
                       value="{{ old('tanggal_mulai', \Carbon\Carbon::parse($event->tanggal_mulai)->format('Y-m-d\TH:i')) }}" 
                       class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
            </div>

            {{-- 4. TANGGAL SELESAI --}}
            <div class="mb-4">
                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700">
                    Tanggal Selesai
                </label>
                <x-text-input type="datetime-local" name="tanggal_selesai" id="tanggal_selesai" 
                       value="{{ old('tanggal_selesai', \Carbon\Carbon::parse($event->tanggal_selesai)->format('Y-m-d\TH:i')) }}" 
                       class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
            </div>
            
            {{-- 5. DESKRIPSI --}}
            <div class="mb-4">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700">
                    Deskripsi
                </label>
                <textarea name="deskripsi" id="deskripsi" rows="4"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('deskripsi', $event->deskripsi) }}</textarea>
                <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
            </div>

            {{-- ====================================================== --}}
            {{-- 6. UPLOAD GAMBAR BARU (BAGIAN YANG DIPERBAIKI) --}}
            {{-- ====================================================== --}}
            <div class="mb-6">
                {{-- Diubah dari 'for="gambar"' menjadi 'for="image"' --}}
                <x-input-label for="image" :value="__('Ganti Gambar Event (Opsional)')" />
                
                {{-- Tampilkan gambar lama (kolom Anda di DB sepertinya 'gambar', jadi ini tetap) --}}
                @if ($event->gambar)
                    <div class="mt-2 mb-3">
                        <p class="text-xs text-gray-500 mb-1">Gambar saat ini:</p>
                        <img src="{{ Storage::url($event->gambar) }}" alt="Gambar Lama" class="w-32 h-32 object-cover rounded-lg border">
                    </div>
                    <input type="hidden" name="existing_gambar" value="{{ $event->gambar }}">
                @else
                    <p class="text-sm text-gray-500 mb-1">Saat ini tidak ada gambar utama.</p>
                @endif

                {{-- Input file untuk upload baru --}}
                {{-- Diubah dari 'id="gambar"' dan 'name="gambar"' menjadi 'id="image"' dan 'name="image"' --}}
                <x-text-input id="image" class="block mt-1 w-full p-2 border" type="file" name="image" accept="image/*" />
                
                {{-- Diubah dari 'get('gambar')' menjadi 'get('image')' --}}
                <x-input-error :messages="$errors->get('image')" class="mt-2" />
            </div>
            {{-- ====================================================== --}}
            {{-- AKHIR BAGIAN YANG DIPERBAIKI --}}
            {{-- ====================================================== --}}


            {{-- 7. STATUS --}}
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
                         class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-semibold text-sm transition shadow-md">
                    Simpan Perubahan
                </button>
                <a href="{{ route('events.show', $event) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition ease-in-out duration-150">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
