<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; 
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BookingController extends Controller
{
    // Middleware di routes/web.php.

    public function index()
    {
        $bookings = Booking::where('attendee_id', Auth::id())
                           ->with(['event', 'tickets'])
                           ->orderBy('tanggal_booking', 'desc') 
                           ->get();

        return view('bookings.index', compact('bookings'));
    }

    // Perhatikan nama method ini: showConfirmation
    public function showConfirmation(Booking $booking)
    {
        if (Auth::id() !== $booking->attendee_id || $booking->status_pembayaran !== 'paid') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak atau pesanan belum lunas.');
        }

        // Load relasi yang dibutuhkan
        $booking->load(['tickets.ticketType', 'event']);

        // Generate QR Code SVG untuk setiap tiket di booking ini
        foreach ($booking->tickets as $ticket) {
            $ticket->qr_svg = QrCode::size(200)->generate($ticket->qr_code);
        }

        return view('bookings.confirmation', compact('booking'));
    }

    public function showCheckout(Booking $booking)
    {
        if (Auth::id() !== $booking->attendee_id || $booking->status_pembayaran !== 'pending') {
            return redirect()->route('dashboard')->with('error', 'Pesanan tidak valid atau sudah lunas.');
        }

        $booking->load('event'); 
        
        $paymentMethods = [
            'BCA Virtual Account', 
            'Mandiri Transfer', 
            'Gopay', 
            'OVO', 
            'Credit Card'
        ];

        return view('bookings.checkout', compact('booking', 'paymentMethods'));
    }

    public function processPayment(Request $request, Booking $booking)
    {
        if (Auth::id() !== $booking->attendee_id) {
            return back()->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'payment_method' => ['required', 'string'],
        ]);

        if ($booking->status_pembayaran === 'pending') {
            DB::beginTransaction();

            try {
                // 1. Update status booking menjadi paid
                $booking->status_pembayaran = 'paid';
                $booking->payment_method = $request->payment_method; 
                $booking->save();
                
                // 2. Generate QR Codes
                foreach ($booking->tickets as $ticket) {
                    if (empty($ticket->qr_code) || Str::isUuid($ticket->qr_code)) {
                        $qrCodeString = $ticket->id . '-' . Str::random(10); 
                        $ticket->qr_code = $qrCodeString;
                        $ticket->save();
                    }
                }

                DB::commit();

                // REDIRECT KE RUTE CONFIRMATION YANG MENGGUNAKAN METHOD showConfirmation()
                return redirect()->route('bookings.confirmation', $booking)
                                 ->with('success', 'Pembayaran berhasil! Tiket Anda siap dicetak.');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
            }
        }

        return back()->with('error', 'Pesanan ini tidak dapat diproses karena status tidak valid.');
    }

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
        
        $totalTicketsCount = count($validated['holders']); 

        try {
            DB::beginTransaction();

            // A. Buat Booking Header
            $booking = Booking::create([
                'attendee_id' => Auth::id(),                     
                'event_id' => $event->id,
                'total_amount' => 0,                            
                'status_pembayaran' => 'pending',                
                'tanggal_booking' => now(),                      
                'jumlah_tiket' => $totalTicketsCount,           
            ]);

            $totalAmount = 0;

            // B. Buat Tiket Individual
            foreach ($validated['holders'] as $holder) {
                $ticketType = TicketType::find($holder['type_id']);
                
                if ($ticketType && $ticketType->price > 0 && $ticketType->available_quantity > 0) {
                    
                    $totalAmount += $ticketType->price; 
                    
                    // Generate placeholder unik
                    $placeholderQrCode = (string) Str::uuid(); 
                    
                    $booking->tickets()->create([ 
                        'ticket_type_id' => $holder['type_id'],
                        'nama_pemegang_tiket' => $holder['name'], 
                        'statusCheckIn' => 'pending', 
                        'qr_code' => $placeholderQrCode, 
                    ]);
                    
                    $ticketType->decrement('kuota'); 

                } else {
                    DB::rollBack();
                    return back()->with('error', 'Kuantitas atau harga tiket ' . ($ticketType->name ?? 'yang diminta') . ' tidak valid.');
                }
            }
            
            // C. Update Total Amount Booking Header
            $booking->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('bookings.checkout', $booking)
                             ->with('success', 'Pemesanan berhasil. Silakan lanjutkan ke pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses pemesanan: ' . $e->getMessage());
        }
    }
}
