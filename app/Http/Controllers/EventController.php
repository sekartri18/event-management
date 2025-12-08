<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Booking; 
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Spatie\CalendarLinks\Link;

class EventController extends Controller
{
    /**
     * Tampilkan daftar event (Index)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Query Builder
        $query = Event::query()
            ->with('organizer') 
            
            // --- PERBAIKAN: Hitung Paid DAN Pending ---
            
            // Hitung Total Pendapatan (Gross) - Termasuk yang Pending
            ->withSum(['bookings' => function ($q) {
                $q->whereIn('status_pembayaran', ['paid', 'pending']);
            }], 'total_amount')
            
            // Hitung Sisa Kuota Tiket
            ->withSum('ticketTypes', 'kuota')
            
            // Hitung Jumlah Peserta/Transaksi (Paid + Pending)
            ->withCount(['bookings as participants_count' => function ($q) {
                $q->whereIn('status_pembayaran', ['paid', 'pending']);
            }])
            
            // Hitung Rata-rata Rating
            ->withAvg('reviews', 'rating')
            
            ->orderBy('tanggal_mulai', 'asc');

        // 2. Filter Organizer
        if ($user && $user->isOrganizer()) {
            $query->where('organizer_id', $user->id);
        }

        // 3. Filter Pencarian
        if ($search = $request->query('search')) {
            $query->where('nama_event', 'LIKE', '%' . $search . '%');
        }

        // 4. Filter Status
        if ($status = $request->query('status')) {
            if (in_array(strtolower($status), ['upcoming', 'ongoing', 'finished'])) {
                $query->where('status', $status);
            }
        }

        $events = $query->paginate(10);

        // 5. Hitung Total Dana Masuk (Global Header) - Termasuk Pending
        $totalDanaMasuk = 0;
        if ($user) {
            if ($user->isAdmin()) {
                // Admin: Hitung semua booking di sistem (Paid + Pending)
                $totalDanaMasuk = Booking::whereIn('status_pembayaran', ['paid', 'pending'])
                    ->sum('total_amount');
            } elseif ($user->isOrganizer()) {
                // Organizer: Hitung booking event miliknya (Paid + Pending)
                $totalDanaMasuk = Booking::whereIn('status_pembayaran', ['paid', 'pending'])
                    ->whereHas('event', function ($q) use ($user) {
                        $q->where('organizer_id', $user->id);
                    })
                    ->sum('total_amount');
            }
        }

        // 6. Pilih View
        $viewName = ($user && $user->isAdmin()) ? 'events.index-admin' : 'events.index';
        
        return view($viewName, compact('events', 'totalDanaMasuk'));
    }

    /**
     * Form untuk buat event
     */
    public function create()
    {
        Gate::authorize('create', Event::class);
        return view('events.create');
    }

    /**
     * Simpan event baru
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Event::class);

        $validated = $request->validate([
            'nama_event' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
            'status' => 'required|in:upcoming,ongoing,finished',
            'deskripsi' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
        }

        Event::create([
            'organizer_id' => Auth::id(),
            'nama_event' => $validated['nama_event'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'lokasi' => $validated['lokasi'],
            'status' => $validated['status'],
            'deskripsi' => $validated['deskripsi'],
            'gambar' => $imagePath, 
        ]);

        return redirect()->route('events.index')->with('success', 'Event berhasil dibuat!');
    }

    /**
     * Tampilkan detail event
     */
    public function show(Event $event)
    {
        Gate::authorize('view', $event); 

        $event->load('ticketTypes', 'organizer', 'reviews.attendee');
        
        // Load booking statistics untuk admin
        $event->loadSum('bookings', 'total_amount');
        $event->loadCount('bookings');

        // Calendar Links
        try {
            $from = new \DateTime($event->tanggal_mulai);
            $to   = new \DateTime($event->tanggal_selesai);

            $link = Link::create($event->nama_event, $from, $to)
                ->description($event->deskripsi ?? '-')
                ->address($event->lokasi ?? '-');

            $calendarLinks = ['google' => $link->google(), 'ics' => $link->ics()];
        } catch (\Exception $e) {
            $calendarLinks = ['google' => '#', 'ics' => '#'];
        }

        // Rating
        $averageRating = $event->reviews->count() > 0 ? $event->reviews->avg('rating') : 0;

        // Logic Review Permission
        $user = Auth::user();
        $canReview = false; 
        $reviewError = null;

        if ($user && $user->isAttendee()) {
            $hasPaidBooking = $user->bookings()
                ->where('event_id', $event->id)
                ->where('status_pembayaran', 'paid')
                ->exists();
            
            $hasAlreadyReviewed = $event->reviews->where('attendee_id', $user->id)->isNotEmpty();

            // Gunakan strtolower agar 'Finished' terdeteksi
            if (strtolower($event->status) == 'finished') {
                if ($hasPaidBooking && !$hasAlreadyReviewed) {
                    $canReview = true;
                } elseif ($hasAlreadyReviewed) {
                    $reviewError = 'Anda sudah memberikan review untuk event ini.';
                } elseif (!$hasPaidBooking) {
                    $reviewError = 'Hanya peserta yang sudah membayar yang bisa memberi review.';
                }
            } else {
                 $reviewError = 'Anda baru bisa memberi review setelah event selesai.';
            }
        }

        if ($reviewError) {
            session()->flash('review_error', $reviewError);
        }

        $viewName = ($user && $user->isAdmin()) ? 'events.show-admin' : 'events.show';

        return view($viewName, compact('event', 'calendarLinks', 'averageRating', 'canReview'));
    }

    /**
     * Form untuk edit event
     */
    public function edit(Event $event)
    {
        Gate::authorize('update', $event);
        $event->load('ticketTypes');
        
        $viewName = (auth()->user() && auth()->user()->isAdmin()) ? 'events.edit-admin' : 'events.edit';
        return view($viewName, compact('event'));
    }

    /**
     * Update event
     */
    public function update(Request $request, Event $event)
    {
        Gate::authorize('update', $event);

        $validated = $request->validate([
            'nama_event' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
            'status' => 'required|in:upcoming,ongoing,finished',
            'deskripsi' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        $dataToUpdate = $request->except(['image', '_token', '_method']);

        if ($request->hasFile('image')) {
            if ($event->gambar) {
                Storage::disk('public')->delete($event->gambar);
            }
            $dataToUpdate['gambar'] = $request->file('image')->store('events', 'public');
        } 

        $event->update($dataToUpdate);

        return redirect()->route('events.index')->with('success', 'Event berhasil diperbarui!');
    }

    /**
     * Hapus event
     */
    public function destroy(Event $event)
    {
        Gate::authorize('delete', $event);

        if ($event->gambar) {
            Storage::disk('public')->delete($event->gambar);
        }

        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event berhasil dihapus!');
    }

    /**
     * Show Attendees List (Organizer)
     */
    public function showAttendees(Event $event)
    {
        Gate::authorize('update', $event); 

        $tickets = Ticket::with(['booking.user', 'ticketType'])
            ->whereHas('booking', function ($query) use ($event) {
                // Tampilkan peserta yang sudah paid
                $query->where('event_id', $event->id)
                      ->where('status_pembayaran', 'paid');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15); 

        return view('events.attendees', compact('event', 'tickets'));
    }

    /**
     * Show Check-In Scanner
     */
    public function showCheckInScanner(Event $event)
    {
        Gate::authorize('update', $event);
        return view('checkin.scanner', compact('event'));
    }

    /**
     * Process Check-In Logic
     */
    public function processCheckIn(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
            'event_id' => 'required|exists:events,id',
        ]);

        $qrCode = $request->input('qr_code');
        $eventId = $request->input('event_id');

        $event = Event::findOrFail($eventId);
        Gate::authorize('update', $event);

        $ticket = Ticket::with('booking', 'ticketType')
                        ->where('qr_code', $qrCode)
                        ->first();

        if (!$ticket) {
            return response()->json([
                'status' => 'error',
                'message' => 'TIKET TIDAK VALID. Kode QR tidak terdaftar.'
            ], 404);
        }

        if ($ticket->booking->event_id != $eventId) {
            return response()->json([
                'status' => 'error',
                'message' => 'TIKET SALAH EVENT. Tiket ini valid, tapi bukan untuk event ini.'
            ], 400);
        }

        if ($ticket->statusCheckIn == 'checked-in') {
            return response()->json([
                'status' => 'warning',
                'message' => 'TIKET SUDAH DIGUNAKAN. Tiket ini sudah di-scan pada ' . ($ticket->tanggalCheckIn ? $ticket->tanggalCheckIn->format('d M Y H:i') : 'sebelumnya'),
                'ticket' => $ticket, 
            ], 409);
        }

        if ($ticket->booking->status_pembayaran != 'paid') {
             return response()->json([
                'status' => 'error',
                'message' => 'TIKET BELUM LUNAS. Pembayaran untuk booking ini masih pending.'
            ], 402);
        }

        $ticket->statusCheckIn = 'checked-in';
        $ticket->tanggalCheckIn = now();
        $ticket->save();

        return response()->json([
            'status' => 'success',
            'message' => 'CHECK-IN BERHASIL: ' . $ticket->nama_pemegang_tiket,
            'ticket' => $ticket,
        ], 200);
    }
}