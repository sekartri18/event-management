<x-admin-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Pengguna (Admin)') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- SESSION STATUS/ALERT (Untuk menampilkan pesan setelah aksi Edit/Hapus) --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @elseif (session('error'))
                 <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-4">Daftar Semua Pengguna Sistem</h3>

                    {{-- Tabel Daftar Pengguna --}}
                    <div class="overflow-x-auto mt-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                // Logika penentuan warna berdasarkan role (sudah ada di kode Anda)
                                                $roleName = $user->role->name ?? 'attendee';
                                                $roleColor = match($roleName) {
                                                    'admin' => 'red',
                                                    'organizer' => 'indigo',
                                                    default => 'green',
                                                };
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $roleColor }}-100 text-{{ $roleColor }}-800">
                                                {{ $user->role->display_name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        
                                        {{-- KOLOM AKSI (EDIT & HAPUS) --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            
                                            {{-- Link Edit --}}
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 font-semibold transition">
                                                Edit
                                            </a>
                                            
                                            {{-- Form Hapus (Hanya tampil jika bukan Admin yang sedang login) --}}
                                            @if ($user->id !== Auth::id())
                                            |
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('ANDA YAKIN? Pengguna {{ $user->name }} akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan!');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold bg-transparent border-none p-0 cursor-pointer transition">
                                                    Hapus
                                                </button>
                                            </form>
                                            @else
                                            |
                                            <span class="text-gray-400 font-medium">Admin Aktif</span>
                                            @endif
                                        </td>
                                        {{-- END KOLOM AKSI --}}
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada pengguna yang terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginasi --}}
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-admin-app-layout>