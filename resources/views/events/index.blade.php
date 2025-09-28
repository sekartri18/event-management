<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Event') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="text-green-600 mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <ul class="list-disc ml-6">
                    @forelse($events as $event)
                        <li>
                            <strong>{{ $event->nama_event }}</strong> 
                            ({{ $event->tanggal_mulai }} - {{ $event->tanggal_selesai }})
                        </li>
                    @empty
                        <li class="text-gray-600">Belum ada event.</li>
                    @endforelse
                </ul>

                <div class="mt-4">
                    <a href="{{ route('events.create') }}" 
                       class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Buat Event Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
