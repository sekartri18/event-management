<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminParticipantController extends Controller
{
    /**
     * Menampilkan daftar semua peserta dari semua event.
     */
    public function index(Request $request): View
    {
        // 1. Mulai Query Ticket (karena 1 tiket = 1 peserta)
        // Kita load relasi booking (user & event) dan ticketType agar performa cepat
        $query = Ticket::query()
            ->with(['booking.attendee', 'booking.event', 'ticketType'])
            ->latest(); // Urutkan dari yang terbaru

        // 2. Filter Pencarian (Nama Peserta, Email, atau Order ID)
        if ($search = $request->query('search')) {
            $query->where(function($q) use ($search) {
                $q->where('nama_pemegang_tiket', 'LIKE', "%{$search}%")
                  ->orWhere('qr_code', 'LIKE', "%{$search}%")
                  ->orWhereHas('booking.attendee', function($subQ) use ($search) {
                      $subQ->where('name', 'LIKE', "%{$search}%")
                           ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // 3. Filter Berdasarkan Event
        if ($event = $request->query('event_id')) {
            $query->whereHas('booking', function($q) use ($event) {
                $q->where('event_id', $event);
            });
        }

        // 4. Filter Status Pembayaran (Default: Paid only, tapi bisa diubah jika mau lihat pending)
        $status = $request->query('status', 'paid');
        if ($status !== 'all') {
            $query->whereHas('booking', function($q) use ($status) {
                $q->where('status_pembayaran', $status);
            });
        }

        // 5. Eksekusi Pagination
        $participants = $query->paginate(15)->withQueryString();

        // Ambil list event untuk dropdown filter
        $events = \App\Models\Event::pluck('nama_event', 'id');

        return view('admin.participants.index', compact('participants', 'events'));
    }
}