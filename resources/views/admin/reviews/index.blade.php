<x-admin-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Ulasan Event') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header Card -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg shadow-lg p-6 mb-8">
                <h3 class="text-2xl font-bold mb-2 text-black">Pantau Ulasan Pengguna</h3>
                <p class="text-gray-700">Kelola dan monitor semua ulasan yang diberikan oleh peserta event</p>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h4 class="font-bold text-gray-700 mb-4">Filter Ulasan</h4>
                <form method="GET" action="{{ route('admin.reviews.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Filter Event -->
                    <div>
                        <label for="event_search" class="block text-sm font-medium text-gray-700 mb-2">Cari Event</label>
                        <input type="text" name="event_search" id="event_search" placeholder="Ketik nama event..." value="{{ request('event_search') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <!-- Filter Rating -->
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                        <select name="rating" id="rating" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">-- Semua Rating --</option>
                            <option value="5" @selected(request('rating') == '5')>⭐⭐⭐⭐⭐ (5 Bintang)</option>
                            <option value="4" @selected(request('rating') == '4')>⭐⭐⭐⭐ (4 Bintang)</option>
                            <option value="3" @selected(request('rating') == '3')>⭐⭐⭐ (3 Bintang)</option>
                            <option value="2" @selected(request('rating') == '2')>⭐⭐ (2 Bintang)</option>
                            <option value="1" @selected(request('rating') == '1')>⭐ (1 Bintang)</option>
                        </select>
                    </div>

                    <!-- Button -->
                    <div class="flex items-end space-x-2 justify-end">
                        <a href="{{ route('admin.reviews.index') }}" class="bg-white text-gray-800 px-6 py-2 rounded-md hover:bg-gray-100 text-center transition font-semibold border border-gray-400 shadow-sm whitespace-nowrap">Reset</a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition font-semibold border border-blue-600 shadow-md whitespace-nowrap">Cari</button>
                    </div>
                </form>
            </div>

            <!-- Reviews Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b border-gray-300">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Peserta</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Event</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Rating</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Komentar</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @if($reviews->isEmpty())
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="font-semibold">Tidak ada ulasan</p>
                            <p class="text-sm text-gray-400">Belum ada ulasan yang sesuai dengan filter</p>
                        </div>
                    </td>
                </tr>
            @else
                @foreach($reviews as $review)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <!-- Nama Peserta -->
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white font-bold text-sm mr-3">
                                    {{ substr($review->attendee->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $review->attendee->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $review->attendee->email }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Nama Event -->
                        <td class="px-6 py-4">
                            <a href="{{ route('events.show', $review->event) }}" class="text-purple-600 hover:text-purple-800 font-medium hover:underline">
                                {{ $review->event->nama_event }}
                            </a>
                            <p class="text-xs text-gray-500 mt-1">{{ $review->event->lokasi }}</p>
                        </td>

                        <!-- Rating -->
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center">
                                <span class="text-lg">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <span class="text-yellow-400">★</span>
                                        @else
                                            <span class="text-gray-300">★</span>
                                        @endif
                                    @endfor
                                </span>
                            </div>
                            <p class="text-sm font-bold text-gray-700 mt-1">{{ $review->rating }}/5</p>
                        </td>

                        <!-- Komentar -->
                        <td class="px-6 py-4">
                            <div class="max-w-xs">
                                <p class="text-sm text-gray-700 line-clamp-2">
                                    {{ $review->komentar ?? 'Tidak ada komentar' }}
                                </p>
                            </div>
                        </td>

                        <!-- Tanggal -->
                        <td class="px-6 py-4 text-center text-sm text-gray-600">
                            <div>
                                <p class="font-medium">{{ $review->tanggal_review->format('d/m/Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $review->tanggal_review->format('H:i') }}</p>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($reviews->hasPages())
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        {{ $reviews->links() }}
                    </div>
                @endif
            </div>

            <!-- Statistics Card -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-8">
                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
                    <p class="text-sm text-gray-600 uppercase font-semibold">Total Ulasan</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $reviews->total() }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
                    <p class="text-sm text-gray-600 uppercase font-semibold">Rating Rata-rata</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">
                        {{ number_format($reviews->isEmpty() ? 0 : $reviews->avg('rating'), 1) }}
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
                    <p class="text-sm text-gray-600 uppercase font-semibold">Rating 5 Bintang</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">
                        {{ $reviews->isEmpty() ? 0 : $reviews->where('rating', 5)->count() }}
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-red-500">
                    <p class="text-sm text-gray-600 uppercase font-semibold">Rating Terendah</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">
                        @php
                            $lowestRating = $reviews->isEmpty() ? 0 : $reviews->min('rating');
                        @endphp
                        {{ $lowestRating ?? 0 }}
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-admin-app-layout>
