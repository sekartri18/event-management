<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // ✔ FIX: Import Log
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

// --- MIDTRANS INTEGRATION ---
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class BookingController extends Controller
{
    // ==============================
    // LIST BOOKING USER
    // ==============================
    public function index()
    {
        $bookings = Booking::where('attendee_id', Auth::id())
                           ->with(['event', 'tickets'])
                           ->orderBy('tanggal_booking', 'desc')
                           ->get();

        return view('bookings.index', compact('bookings'));
    }

    // ==============================
    // SHOW CONFIRMATION
    // ==============================
    public function showConfirmation(Booking $booking)
    {
        if (Auth::id() !== $booking->attendee_id || $booking->status_pembayaran !== 'paid') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak atau pesanan belum lunas.');
        }

        $booking->load(['tickets.ticketType', 'event']);

        foreach ($booking->tickets as $ticket) {
            $ticket->qr_svg = QrCode::size(200)->generate($ticket->qr_code);
        }

        return view('bookings.confirmation', compact('booking'));
    }

    // ==============================
    // SHOW CHECKOUT + GET SNAP TOKEN
    // ==============================
    public function showCheckout(Booking $booking)
    {
        if (Auth::id() !== $booking->attendee_id || $booking->status_pembayaran !== 'pending') {
            return redirect()->route('dashboard')->with('error', 'Pesanan tidak valid atau sudah lunas.');
        }

        $booking->load('event', 'attendee');

        try {
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $orderId = 'BOOKING-' . $booking->id . '-' . time();

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $booking->total_amount,
                ],
                'customer_details' => [
                    'first_name' => $booking->attendee->name,
                    'email' => $booking->attendee->email,
                ]
            ];

            $snapToken = Snap::getSnapToken($params);

            $booking->update(['midtrans_order_id' => $orderId]);

            return view('bookings.checkout', [
                'booking' => $booking,
                'snapToken' => $snapToken,
                'clientKey' => env('MIDTRANS_CLIENT_KEY'),
            ]);
        } catch (\Exception $e) {
            Log::error("Snap Token Error: " . $e->getMessage());
            return back()->with('error', 'Gagal membuat sesi pembayaran.');
        }
    }

    // ==============================
    // MANUAL PAYMENT (DISABLED)
    // ==============================
    public function processPayment()
    {
        return back()->with('error', 'Pembayaran manual dinonaktifkan. Gunakan Midtrans.');
    }

    // ==============================
    // CREATE BOOKING
    // ==============================
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'holders' => ['required', 'array'],
            'holders.*.type_id' => ['required', 'exists:ticket_types,id'],
            'holders.*.name' => ['required', 'string', 'max:255'],
        ]);

        $ticketTypes = $event->ticketTypes->pluck('id')->toArray();

        foreach ($validated['holders'] as $holder) {
            if (!in_array($holder['type_id'], $ticketTypes)) {
                return back()->with('error', 'Tipe tiket tidak valid untuk event ini.');
            }
        }

        try {
            DB::beginTransaction();

            $booking = Booking::create([
                'attendee_id' => Auth::id(),
                'event_id' => $event->id,
                'total_amount' => 0,
                'status_pembayaran' => 'pending',
                'tanggal_booking' => now(),
                'jumlah_tiket' => count($validated['holders']),
            ]);

            $totalAmount = 0;

            foreach ($validated['holders'] as $holder) {
                $ticketType = TicketType::find($holder['type_id']);

                if (!$ticketType || $ticketType->available_quantity <= 0) {
                    DB::rollBack();
                    return back()->with('error', 'Stok tiket tidak tersedia.');
                }

                $totalAmount += $ticketType->price;

                $booking->tickets()->create([
                    'ticket_type_id' => $ticketType->id,
                    'nama_pemegang_tiket' => $holder['name'],
                    'statusCheckIn' => 'pending',
                    'qr_code' => (string) Str::uuid(),
                ]);

                $ticketType->decrement('kuota');
            }

            $booking->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('bookings.checkout', $booking)
                             ->with('success', 'Pemesanan berhasil. Silakan lanjut ke pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Booking Error: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan dalam pemesanan.');
        }
    }

    // ==============================
    // MIDTRANS WEBHOOK HANDLER
    // ==============================
    public function notificationHandler(Request $request)
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

        try {
            $notif = new Notification();
        } catch (\Exception $e) {
            Log::error("Webhook Error: " . $e->getMessage());
            return response()->json(['message' => 'Invalid notification'], 400);
        }

        $transactionStatus = $notif->transaction_status;
        $orderId = $notif->order_id;
        $fraudStatus = $notif->fraud_status;
        $paymentType = $notif->payment_type;

        $parts = explode('-', $orderId);
        $bookingId = $parts[1] ?? null;

        $booking = Booking::find($bookingId);

        if (!$booking) {
            Log::warning("Webhook: Order ID $orderId tidak ditemukan.");
            return response()->json(['message' => 'Order ID tidak ditemukan']);
        }

        $newStatus = null;

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') $newStatus = 'paid';
        } elseif ($transactionStatus === 'settlement') {
            $newStatus = 'paid';
        } elseif ($transactionStatus === 'pending') {
            $newStatus = 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $newStatus = 'failed';
        }

        if ($newStatus) {
            DB::beginTransaction();
            try {
                $booking->status_pembayaran = $newStatus;
                $booking->payment_method = $paymentType;
                $booking->save();

                if ($newStatus === 'paid') {
                    $booking->load('tickets');
                    foreach ($booking->tickets as $ticket) {
                        if (Str::isUuid($ticket->qr_code)) {
                            $ticket->qr_code = $ticket->id . '-' . Str::random(10);
                            $ticket->save();
                        }
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Webhook Processing Error: " . $e->getMessage());
                return response()->json(['message' => 'Gagal update status'], 500);
            }
        }

        return response()->json(['message' => 'OK']);
    }
}
