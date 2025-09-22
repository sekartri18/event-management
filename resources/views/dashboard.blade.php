<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Management Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-3xl font-bold text-center mb-10">📊 Event Management Dashboard</h1>

    {{-- Organizers --}}
    <h2 class="text-xl font-semibold text-gray-700 mt-10 mb-3 border-b pb-2">Organizers</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead>
                <tr class="bg-green-600 text-white text-sm uppercase tracking-wider">
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">No HP</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @foreach($organizers as $o)
                <tr class="border-b hover:bg-green-50">
                    <td class="px-4 py-2 text-center">{{ $o->id }}</td>
                    <td class="px-4 py-2">{{ $o->nama }}</td>
                    <td class="px-4 py-2">{{ $o->email }}</td>
                    <td class="px-4 py-2 text-center">{{ $o->no_hp }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Attendees --}}
    <h2 class="text-xl font-semibold text-gray-700 mt-10 mb-3 border-b pb-2">Attendees</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead>
                <tr class="bg-green-600 text-white text-sm uppercase tracking-wider">
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">No Telepon</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @foreach($attendees as $a)
                <tr class="border-b hover:bg-green-50">
                    <td class="px-4 py-2 text-center">{{ $a->id }}</td>
                    <td class="px-4 py-2">{{ $a->nama }}</td>
                    <td class="px-4 py-2">{{ $a->email }}</td>
                    <td class="px-4 py-2 text-center">{{ $a->no_telepon }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Events --}}
    <h2 class="text-xl font-semibold text-gray-700 mt-10 mb-3 border-b pb-2">Events</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead>
                <tr class="bg-green-600 text-white text-sm uppercase tracking-wider">
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Organizer ID</th>
                    <th class="px-4 py-3">Nama Event</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Lokasi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @foreach($events as $e)
                <tr class="border-b hover:bg-green-50">
                    <td class="px-4 py-2 text-center">{{ $e->id }}</td>
                    <td class="px-4 py-2 text-center">{{ $e->organizer_id }}</td>
                    <td class="px-4 py-2">{{ $e->nama_event }}</td>
                    <td class="px-4 py-2 text-center">{{ $e->status }}</td>
                    <td class="px-4 py-2">{{ $e->lokasi }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Bookings --}}
    <h2 class="text-xl font-semibold text-gray-700 mt-10 mb-3 border-b pb-2">Bookings</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead>
                <tr class="bg-green-600 text-white text-sm uppercase tracking-wider">
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Attendee ID</th>
                    <th class="px-4 py-3">Event ID</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Jumlah Tiket</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @foreach($bookings as $b)
                <tr class="border-b hover:bg-green-50">
                    <td class="px-4 py-2 text-center">{{ $b->id }}</td>
                    <td class="px-4 py-2 text-center">{{ $b->attendee_id }}</td>
                    <td class="px-4 py-2 text-center">{{ $b->event_id }}</td>
                    <td class="px-4 py-2 text-center">{{ $b->status_pembayaran }}</td>
                    <td class="px-4 py-2 text-center">{{ $b->jumlah_tiket }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Ticket Types --}}
    <h2 class="text-xl font-semibold text-gray-700 mt-10 mb-3 border-b pb-2">Ticket Types</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead>
                <tr class="bg-green-600 text-white text-sm uppercase tracking-wider">
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Event ID</th>
                    <th class="px-4 py-3">Nama Tiket</th>
                    <th class="px-4 py-3">Harga</th>
                    <th class="px-4 py-3">Kuota</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @foreach($ticketTypes as $tt)
                <tr class="border-b hover:bg-green-50">
                    <td class="px-4 py-2 text-center">{{ $tt->id }}</td>
                    <td class="px-4 py-2 text-center">{{ $tt->event_id }}</td>
                    <td class="px-4 py-2">{{ $tt->nama_tiket }}</td>
                    <td class="px-4 py-2 text-center">{{ $tt->harga }}</td>
                    <td class="px-4 py-2 text-center">{{ $tt->kuota }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Tickets --}}
    <h2 class="text-xl font-semibold text-gray-700 mt-10 mb-3 border-b pb-2">Tickets</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead>
                <tr class="bg-green-600 text-white text-sm uppercase tracking-wider">
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Ticket Type ID</th>
                    <th class="px-4 py-3">Booking ID</th>
                    <th class="px-4 py-3">Pemegang</th>
                    <th class="px-4 py-3">Status CheckIn</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @foreach($tickets as $t)
                <tr class="border-b hover:bg-green-50">
                    <td class="px-4 py-2 text-center">{{ $t->id }}</td>
                    <td class="px-4 py-2 text-center">{{ $t->ticket_type_id }}</td>
                    <td class="px-4 py-2 text-center">{{ $t->booking_id }}</td>
                    <td class="px-4 py-2">{{ $t->nama_pemegang_tiket }}</td>
                    <td class="px-4 py-2 text-center">{{ $t->statusCheckIn }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Reviews --}}
    <h2 class="text-xl font-semibold text-gray-700 mt-10 mb-3 border-b pb-2">Reviews</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead>
                <tr class="bg-green-600 text-white text-sm uppercase tracking-wider">
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Attendee ID</th>
                    <th class="px-4 py-3">Event ID</th>
                    <th class="px-4 py-3">Rating</th>
                    <th class="px-4 py-3">Komentar</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @foreach($reviews as $r)
                <tr class="border-b hover:bg-green-50">
                    <td class="px-4 py-2 text-center">{{ $r->id }}</td>
                    <td class="px-4 py-2 text-center">{{ $r->attendee_id }}</td>
                    <td class="px-4 py-2 text-center">{{ $r->event_id }}</td>
                    <td class="px-4 py-2 text-center">{{ $r->rating }}</td>
                    <td class="px-4 py-2">{{ $r->komentar }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Calendar Integrations --}}
    <h2 class="text-xl font-semibold text-gray-700 mt-10 mb-3 border-b pb-2">Calendar Integrations</h2>
    <div class="overflow-x-auto mb-10">
        <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
            <thead>
                <tr class="bg-green-600 text-white text-sm uppercase tracking-wider">
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Event ID</th>
                    <th class="px-4 py-3">Calendar ID</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @foreach($calendarIntegrations as $c)
                <tr class="border-b hover:bg-green-50">
                    <td class="px-4 py-2 text-center">{{ $c->id }}</td>
                    <td class="px-4 py-2 text-center">{{ $c->event_id }}</td>
                    <td class="px-4 py-2">{{ $c->calendar_id }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>
