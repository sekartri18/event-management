<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-gray-800 leading-tight">
            {{ __('Daftar Peserta Event: ' . $event->nama_event) }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 flex space-x-4 items-center">
                <a href="{{ route('events.show', $event) }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 font-semibold text-sm transition">
                    &larr; Kembali ke Detail Event
                </a>
                <a href="{{ route('events.checkin.scanner', $event) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-semibold text-sm transition shadow-md">
                    Scan Tiket Sekarang &rarr;
                </a>
                
                {{-- Statistik Sederhana --}}
                <div class="flex-1 text-right text-gray-600 text-sm flex items-center justify-end space-x-4">
                    <span>Total Tiket Lunas: <span class="font-bold">{{ $tickets->total() }}</span></span>
                    <span>Sudah Check-In: <span class="font-bold text-green-600">{{ $tickets->where('statusCheckIn', 'checked-in')->count() }}</span></span>
                    <span>Belum Check-In: <span class="font-bold text-red-600">{{ $tickets->where('statusCheckIn', 'pending')->count() }}</span></span>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Daftar Tiket Peserta (Hanya Status PAID)</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pemegang Tiket</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe Tiket</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembeli (Attendee)</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Check-In</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Check-In</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">QR Code (8 Digit)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($tickets as $ticket)
                                    <tr class="@if($ticket->statusCheckIn === 'checked-in') bg-green-50 @else hover:bg-yellow-50 @endif">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $ticket->nama_pemegang_tiket }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-600">{{ $ticket->ticketType->nama_tiket }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ticket->booking->attendee->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($ticket->statusCheckIn === 'checked-in') bg-green-200 text-green-800 @else bg-red-200 text-red-800 @endif">
                                                {{ ucfirst(str_replace('-', ' ', $ticket->statusCheckIn)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $ticket->tanggalCheckIn ? \Carbon\Carbon::parse($ticket->tanggalCheckIn)->isoFormat('HH:mm, D MMM YYYY') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-400 font-mono">{{ substr($ticket->qr_code, 0, 8) }}...</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada tiket yang sudah lunas untuk event ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Paginasi --}}
                    <div class="mt-4">
                        {{ $tickets->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
