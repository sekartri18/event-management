<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckInController extends Controller
{
    /**
     * Tampilkan daftar peserta (tiket berstatus 'paid') untuk event tertentu.
     */
    public function index(Event $event)
    {
        // Otorisasi: Pastikan hanya organizer yang memiliki event ini yang bisa mengakses
        if (Auth::user()->id !== $event->organizer_id && !Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki hak akses untuk event ini.');
        }

        // Ambil semua tiket yang terkait dengan event ini yang status booking-nya 'paid'
        $tickets = Ticket::whereHas('booking', function ($query) use ($event) {
                            $query->where('event_id', $event->id)
                                  ->where('status_pembayaran', 'paid');
                        })
                        ->with(['booking.attendee', 'ticketType'])
                        ->orderBy('statusCheckIn', 'asc') // Tampilkan yang belum check-in dulu
                        ->paginate(20);

        return view('events.checkin.index', compact('event', 'tickets'));
    }

    /**
     * Tampilkan halaman scanner (untuk memasukkan QR Code).
     */
    public function showScanner(Event $event)
    {
        // Otorisasi seperti di method index
        if (Auth::user()->id !== $event->organizer_id && !Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki hak akses untuk event ini.');
        }

        return view('events.checkin.scanner', compact('event'));
    }
    
    /**
     * Proses check-in menggunakan QR Code/Barcode.
     */
    public function processCheckIn(Request $request, Event $event)
    {
        // Otorisasi seperti di method index
        if (Auth::user()->id !== $event->organizer_id && !Auth::user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Akses Ditolak.'], 403);
        }

        $request->validate([
            'qr_code' => 'required|string|max:255',
        ]);

        $qrCode = $request->input('qr_code');

        try {
            // 1. Cari tiket berdasarkan QR Code
            $ticket = Ticket::where('qr_code', $qrCode)
                            ->whereHas('booking', function ($query) use ($event) {
                                $query->where('event_id', $event->id);
                            })
                            ->first();

            if (!$ticket) {
                return response()->json(['success' => false, 'message' => 'Tiket tidak ditemukan untuk event ini.'], 404);
            }
            
            // 2. Cek status pembayaran booking (Pastikan sudah PAID)
            if ($ticket->booking->status_pembayaran !== 'paid') {
                return response()->json(['success' => false, 'message' => 'Pembayaran tiket ini belum lunas (Status: ' . $ticket->booking->status_pembayaran . ').'], 400);
            }

            // 3. Cek status check-in saat ini
            if ($ticket->statusCheckIn === 'checked-in') {
                $checkInTime = Carbon::parse($ticket->tanggalCheckIn)->isoFormat('D MMM YYYY, HH:mm');
                return response()->json([
                    'success' => false,
                    'message' => 'Tiket sudah check-in sebelumnya pada ' . $checkInTime,
                    'ticket' => $ticket
                ], 400);
            }

            // 4. Proses Check-In
            $ticket->update([
                'statusCheckIn' => 'checked-in',
                'tanggalCheckIn' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Check-in berhasil! Selamat datang ' . $ticket->nama_pemegang_tiket,
                'ticket' => $ticket,
                'attendeeName' => $ticket->booking->attendee->name,
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}