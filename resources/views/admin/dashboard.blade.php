<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard (Akses Penuh Sistem)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-2xl font-bold text-indigo-700 mb-4">Selamat Datang, Administrator!</p>
                    <p class="mb-6">Anda telah berhasil masuk sebagai Super Admin. Ini adalah area dengan hak akses penuh ke seluruh manajemen sistem (Pengguna, Event, Role, dan Permission).</p>

                    <h3 class="font-semibold text-lg mb-2">Akses Cepat Admin:</h3>
                    <ul class="list-disc list-inside space-y-1">
                        <li><span class="font-medium">Pengelolaan Pengguna:</span> Anda dapat mengedit atau menangguhkan akun pengguna (Organizer/Attendee).</li>
                        <li><span class="font-medium">Audit Event:</span> Anda dapat melihat, mengedit, atau menghapus event yang dibuat oleh Organizer mana pun.</li>
                        <li><span class="font-medium">Konfigurasi Sistem:</span> Anda memiliki akses untuk mengelola data master dan pengaturan sistem lainnya.</li>
                    </ul>

                    <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 rounded">
                        <p class="font-semibold">Catatan Penting:</p>
                        <p>Pastikan Anda hanya menggunakan hak akses ini untuk tujuan pengawasan dan pemeliharaan sistem.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
