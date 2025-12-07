<x-app-layout>
    {{-- LATAR BELAKANG VIBRANT --}}
    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- HEADER SECTION --}}
            <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4">
                <div>
                    {{-- Breadcrumb / Back Button --}}
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('events.show', $event) }}" class="inline-flex items-center text-indigo-800 hover:text-gray-800 transition font-medium mb-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali ke Admin Panel
                        </a>
                    @else
                        <a href="{{ route('events.show', $event) }}" class="inline-flex items-center text-indigo-800 hover:text-gray-800 transition font-medium mb-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali ke Detail Event
                        </a>
                    @endif

                    <h2 class="font-black text-3xl text-gray-800 leading-tight drop-shadow-md">
                        {{ __('Daftar Peserta') }}
                    </h2>
                    <p class="text-indigo-800 mt-1 text-lg">
                        Event: <strong>{{ $event->nama_event }}</strong>
                    </p>
                </div>

                <div class="flex space-x-3">
                    {{-- ========================================================= --}}
                    {{-- PERBAIKAN: Tombol Scanner Hanya Muncul untuk Organizer --}}
                    {{-- ========================================================= --}}
                    @if(Auth::user()->isOrganizer())
                        <a href="{{ route('events.checkin.scanner', $event) }}" 
                           class="inline-flex items-center px-6 py-3 bg-white text-indigo-600 border border-transparent rounded-full font-bold text-sm uppercase tracking-widest hover:bg-gray-100 shadow-lg transition transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            Buka Scanner QR
                        </a>
                    @endif
                    {{-- ========================================================= --}}
                </div>
            </div>

            {{-- STATISTIK SINGKAT (GLASS CARD) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white/90 backdrop-blur rounded-2xl p-6 shadow-lg border border-white/50">
                    <p class="text-sm font-bold text-indigo-500 uppercase">Total Peserta (Paid)</p>
                    <p class="text-3xl font-black text-gray-900">{{ $tickets->total() }}</p>
                </div>
                <div class="bg-green-100/90 backdrop-blur rounded-2xl p-6 shadow-lg border border-green-200">
                    <p class="text-sm font-bold text-green-600 uppercase">Sudah Check-in</p>
                    {{-- Hitung manual sederhana untuk tampilan --}}
                    <p class="text-3xl font-black text-green-800">
                        {{ $event->bookings()->where('status_pembayaran', 'paid')->get()->flatMap->tickets->where('statusCheckIn', 'checked-in')->count() }}
                    </p>
                </div>
                <div class="bg-yellow-100/90 backdrop-blur rounded-2xl p-6 shadow-lg border border-yellow-200">
                    <p class="text-sm font-bold text-yellow-600 uppercase">Belum Check-in</p>
                    <p class="text-3xl font-black text-yellow-800">
                        {{ $event->bookings()->where('status_pembayaran', 'paid')->get()->flatMap->tickets->where('statusCheckIn', '!=', 'checked-in')->count() }}
                    </p>
                </div>
            </div>

            {{-- TABEL PESERTA (GLASSMORPHISM) --}}
            <div class="bg-white/95 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/40">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tipe Tiket</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kode Tiket</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status Check-in</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu Masuk</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($tickets as $ticket)
                                <tr class="hover:bg-indigo-50/50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold">
                                                {{ substr($ticket->nama_pemegang_tiket, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $ticket->nama_pemegang_tiket }}</div>
                                                <div class="text-xs text-gray-500">{{ $ticket->booking->attendee->email ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            {{ $ticket->ticketType->nama_tiket }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-mono text-gray-600 bg-gray-100 px-2 py-1 rounded">
                                            {{ substr($ticket->qr_code, 0, 8) }}...
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($ticket->statusCheckIn === 'checked-in')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-700 border border-green-200">
                                                ✅ Hadir
                                            </span>
                                        @else
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-gray-100 text-gray-500 border border-gray-200">
                                                ⏳ Belum
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                        @if($ticket->tanggalCheckIn)
                                            {{ \Carbon\Carbon::parse($ticket->tanggalCheckIn)->format('H:i, d M') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">
                                        Belum ada peserta yang terdaftar (Paid).
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $tickets->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>