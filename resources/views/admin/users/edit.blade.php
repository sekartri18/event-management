<x-admin-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pengguna: ' . $user->name) }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Menampilkan pesan sukses/error --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @elseif (session('error'))
                 <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                
                <h3 class="text-2xl font-bold text-indigo-700 mb-6 border-b pb-2">Perbarui Detail Pengguna</h3>

                {{-- Form ditujukan ke route update dengan method PATCH --}}
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PATCH') 

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Nama')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="phone" :value="__('Nomor Telepon')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $user->phone)" placeholder="Opsional" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="role_id" :value="__('Role Pengguna')" />
                            {{-- Dropdown untuk memilih Role --}}
                            <select id="role_id" name="role_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" 
                                            {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ $role->display_name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
                        </div>
                    </div>
                    
                    <h4 class="text-xl font-bold text-gray-700 mb-4 mt-6 border-t pt-4">Ganti Password (Opsional)</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-4">
                            <x-input-label for="password" :value="__('Password Baru')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" placeholder="Kosongkan jika tidak ingin diubah" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition ease-in-out duration-150 mr-3">
                            {{ __('Batal') }}
                        </a>
                        <x-primary-button class="ms-4 bg-indigo-600 hover:bg-indigo-700">
                            {{ __('Simpan Perubahan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-app-layout>