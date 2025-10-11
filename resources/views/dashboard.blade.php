<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Attendee (Selamat Datang!)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border-l-4 border-blue-500">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold text-blue-700 mb-4">Temukan Event Menarik di Sekitar Anda!</h3>
                    <p class="mb-6 text-lg">
                        Sebagai Attendee, Anda dapat menjelajahi, mendaftar, dan mengelola tiket untuk semua event yang tersedia.
                    </p>

                    <div class="flex space-x-4">
                        <a href="{{ route('events.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg">
                            Jelajahi Semua Event &rarr;
                        </a>
                        <a href="{{ route('bookings.index') }}"
                           class="inline-flex items-center px-6 py-3 bg-gray-200 border border-gray-300 rounded-lg font-semibold text-sm text-gray-800 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Lihat Tiket Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>