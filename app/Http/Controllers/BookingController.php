<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            // ✅ PERBAIKAN BOOLEAN CASTING (Sangat Penting)
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = filter_var(env('MIDTRANS_IS_PRODUCTION', false), FILTER_VALIDATE_BOOLEAN);
            
            Config::$isSanitized = true;
            Config::$is3ds = true;
            Config::$overrideNotifUrl = 'https://longanamous-arturo-enterally.ngrok-free.dev/midtrans/notification';
                
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
    // CREATE BOOKING (FEE 5%)
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

            // 1. Buat Booking Awal (Total & Fee 0 dulu)
            $booking = Booking::create([
                'attendee_id' => Auth::id(),
                'event_id' => $event->id,
                'total_amount' => 0, 
                'admin_fee' => 0, 
                'status_pembayaran' => 'pending',
                'tanggal_booking' => now(),
                'jumlah_tiket' => count($validated['holders']),
            ]);

            $subTotal = 0; // Total murni harga tiket

            // 2. Proses Tiket & Hitung Subtotal
            foreach ($validated['holders'] as $holder) {
                $ticketType = TicketType::find($holder['type_id']);

                if (!$ticketType || $ticketType->available_quantity <= 0) {
                    DB::rollBack();
                    return back()->with('error', 'Stok tiket tidak tersedia.');
                }

                $subTotal += $ticketType->price;

                $booking->tickets()->create([
                    'ticket_type_id' => $ticketType->id,
                    'nama_pemegang_tiket' => $holder['name'],
                    'statusCheckIn' => 'pending',
                    'qr_code' => (string) Str::uuid(),
                ]);

                $ticketType->decrement('kuota');
            }

            // 3. HITUNG FEE BERDASARKAN PERSENTASE (5%)
            // Rumus: Total Harga Tiket x 5%
            $platformFee = $subTotal * 0.05; 

            // 4. Update Booking dengan Total Akhir
            $grandTotal = $subTotal + $platformFee;
            
            $booking->update([
                'total_amount' => $grandTotal,
                'admin_fee' => $platformFee
            ]);

            DB::commit();

            return redirect()->route('bookings.checkout', $booking)
                             ->with('success', 'Pemesanan berhasil. Biaya admin 5% telah ditambahkan.');

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
        // ✅ PERBAIKAN BOOLEAN CASTING (Sangat Penting)
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = filter_var(env('MIDTRANS_IS_PRODUCTION', false), FILTER_VALIDATE_BOOLEAN);

        try {
            // Gunakan library Midtrans untuk memproses data mentah POST
            $notif = new Notification();
        } catch (\Exception $e) {
            // Jika ada error inisiasi Midtrans (misal karena casting boolean salah), catat
            Log::error("Webhook Init Error: " . $e->getMessage());
            return response()->json(['message' => 'Notification handler init failed'], 500);
        }

        $transactionStatus = $notif->transaction_status;
        $orderId = $notif->order_id;
        $fraudStatus = $notif->fraud_status;
        $paymentType = $notif->payment_type;

        $parts = explode('-', $orderId);
        $bookingId = $parts[1] ?? null;

        // ✅ Perbaikan: Pastikan bookingId adalah ID numerik
        if (!is_numeric($bookingId)) {
            Log::warning("Webhook: Order ID format tidak valid: " . $orderId);
            return response()->json(['message' => 'Order ID format invalid']);
        }
        
        $booking = Booking::find($bookingId);

        if (!$booking) {
            Log::warning("Webhook: Booking ID $bookingId tidak ditemukan.");
            return response()->json(['message' => 'Booking ID tidak ditemukan']);
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

        if ($newStatus && $booking->status_pembayaran !== $newStatus) { // Tambahkan cek status untuk mencegah update ganda
            DB::beginTransaction();
            try {
                $booking->status_pembayaran = $newStatus;
                $booking->payment_method = $paymentType;
                $booking->save();

                if ($newStatus === 'paid') {
                    // Hanya buat QR code jika status berubah menjadi paid
                    $booking->load('tickets');
                    foreach ($booking->tickets as $ticket) {
                        // Pastikan QR code hanya di-generate sekali
                        if (Str::isUuid($ticket->qr_code) || $ticket->qr_code === null) {
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