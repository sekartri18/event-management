<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Spatie\CalendarLinks\Link; // <-- Ini sudah benar

class EventController extends Controller
{
    /**
     * Tampilkan daftar event... (Method index Anda sudah benar)
     */
    public function index(Request $request)
    {
        // 1. Ambil user yang sedang login
        $user = Auth::user();

        // 2. Mulai Query Builder
        $query = Event::query()->orderBy('tanggal_mulai', 'asc');

        // 3. Logika pemfilteran event berdasarkan Role (Organizer hanya lihat miliknya)
        if ($user) {
            if ($user->isOrganizer()) {
                // Organizer hanya melihat event yang dia buat
                $query->where('organizer_id', $user->id);
            }
            // Admin dan Attendee melihat semua event
        }

        // ==============================================
        // LOGIKA PENAMBAHAN FILTER (UNTUK SEARCH & FILTER ATTENDEE)
        // ==============================================

        // Filter 1: Pencarian berdasarkan Nama Event (Search Bar)
        if ($search = $request->query('search')) {
            $query->where('nama_event', 'LIKE', '%' . $search . '%');
        }

        // Filter 2: Filter berdasarkan Status
        if ($status = $request->query('status')) {
            // Pastikan status yang dicari valid
            if (in_array($status, ['upcoming', 'ongoing', 'finished'])) {
                $query->where('status', $status);
            }
        }

        // Filter 3: Filter berdasarkan Lokasi
        if ($location = $request->query('location')) {
            $query->where('lokasi', 'LIKE', '%' . $location . '%');
        }

        // 4. Eksekusi query
        $events = $query->paginate(10); // Gunakan paginate jika ingin pagination

        // 5. Kirim variabel $events ke view
        return view('events.index', compact('events'));
    }

    /**
     * Form untuk buat event... (Method create Anda sudah benar)
     */
    public function create()
    {
        Gate::authorize('create', Event::class);
        return view('events.create');
    }

    /**
     * Simpan event baru... (Method store Anda sudah benar)
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Event::class);

        // 1. Validasi Input
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

        // 2. Handle File Upload Gambar
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
        }

        // 3. Simpan Event ke Database
        $organizerId = Auth::id();
        Event::create([
            'organizer_id' => $organizerId,
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
     *
     * INI ADALAH METHOD YANG DIPERBAIKI
     */
    public function show(Event $event)
    {
        // 1. Eager load relasi yang dibutuhkan
        //    PERBAIKAN: Tambahkan 'reviews.attendee'
        $event->load('ticketTypes', 'organizer', 'reviews.attendee');

        // 2. Buat Link Kalender (Kode Anda sudah benar)
        $link = Link::create(
            $event->nama_event,
            $event->tanggal_mulai, // Ini akan menjadi objek Carbon karena $casts
            $event->tanggal_selesai
        )->description($event->deskripsi)
         ->address($event->lokasi);

        $calendarLinks = [
            'google' => $link->google(),
            'ics' => $link->ics(), // Untuk Outlook, Apple Calendar, dll.
        ];

        // 3. TAMBAHAN: Hitung rata-rata rating
        //    PERBAIKAN: Cek count() > 0 untuk menghindari Division by Zero
        $averageRating = $event->reviews->count() > 0 ? $event->reviews->avg('rating') : 0;

        // 4. TAMBAHAN: Tentukan apakah user saat ini bisa memberi review
        $user = Auth::user();
        $canReview = false; 
        $reviewError = null; // Variabel untuk menyimpan pesan error

        if ($user && $user->isAttendee()) {
            // Cek apakah dia punya tiket lunas (sesuai Model User & Booking)
            $hasPaidBooking = $user->bookings()
                                     ->where('event_id', $event->id)
                                     ->where('status_pembayaran', 'paid')
                                     ->exists();
            
            // Cek apakah dia sudah pernah review (sesuai Model User & Review)
            // PERBAIKAN: Gunakan 'attendee_id'
            $hasAlreadyReviewed = $event->reviews->where('attendee_id', $user->id)->isNotEmpty();

            if ($event->status == 'finished') {
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

        // 5. TAMBAHAN: Kirim pesan error (jika ada) ke session agar bisa ditampilkan di view
        if ($reviewError) {
            session()->flash('review_error', $reviewError);
        }

        // 6. PERBAIKAN: Kirim semua data ke view
        return view('events.show', compact(
            'event', 
            'calendarLinks', 
            'averageRating', 
            'canReview'
        ));
    }


    /**
     * Form untuk edit event... (Method edit Anda sudah benar)
     */
    public function edit(Event $event)
    {
        Gate::authorize('update', $event);
        $event->load('ticketTypes');
        return view('events.edit', compact('event'));
    }

    /**
     * Update event... (Method update Anda sudah benar)
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
     * Hapus event... (Method destroy Anda sudah benar)
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
     * Method showAttendees... (Method showAttendees Anda sudah benar)
     */
    public function showAttendees(Event $event)
    {
        Gate::authorize('update', $event); 

        $tickets = Ticket::with(['booking.user', 'ticketType'])
            ->whereHas('booking', function ($query) use ($event) {
                $query->where('event_id', $event->id)
                      ->where('status_pembayaran', 'paid');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15); 

        return view('events.attendees', compact('event', 'tickets'));
    }

    /**
     * Menampilkan halaman scanner... (Method showCheckInScanner Anda sudah benar)
     */
    public function showCheckInScanner(Event $event)
    {
        Gate::authorize('update', $event);
        return view('checkin.scanner', compact('event'));
    }

    /**
     * Memproses data QR Code... (Method processCheckIn Anda sudah benar)
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
            ], 404); // Not Found
        }

        if ($ticket->booking->event_id != $eventId) {
            return response()->json([
                'status' => 'error',
                'message' => 'TIKET SALAH EVENT. Tiket ini valid, tapi bukan untuk event ini.'
            ], 400); // Bad Request
        }

        if ($ticket->statusCheckIn == 'checked-in') {
            return response()->json([
                'status' => 'warning',
                'message' => 'TIKET SUDAH DIGUNAKAN. Tiket ini sudah di-scan pada ' . ($ticket->tanggalCheckIn ? $ticket->tanggalCheckIn->format('d M Y H:i') : 'sebelumnya'),
                'ticket' => $ticket, 
            ], 409); // Conflict
        }

        if ($ticket->booking->status_pembayaran != 'paid') {
             return response()->json([
                'status' => 'error',
                'message' => 'TIKET BELUM LUNAS. Pembayaran untuk booking ini masih pending.'
            ], 402); // Payment Required
        }

        $ticket->statusCheckIn = 'checked-in';
        $ticket->tanggalCheckIn = now();
        $ticket->save();

        return response()->json([
            'status' => 'success',
            'message' => 'CHECK-IN BERHASIL: ' . $ticket->nama_pemegang_tiket,
            'ticket' => $ticket,
        ], 200); // OK
    }
}