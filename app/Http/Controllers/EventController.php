<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Tampilkan daftar event, dengan dukungan pencarian dan filter.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
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

    // Form untuk buat event (hanya organizer)
    public function create()
    {
        Gate::authorize('create', Event::class);
        return view('events.create');
    }

    // Simpan event baru ke database
    public function store(Request $request)
    {
        Gate::authorize('create', Event::class);

        // 1. Validasi Input
        // !! PERBAIKAN: Mengubah 'gambar' menjadi 'image' agar sesuai form !!
        $validated = $request->validate([
            'nama_event' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
            'status' => 'required|in:upcoming,ongoing,finished',
            'deskripsi' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Diubah dari 'gambar'
        ]);

        $imagePath = null;

        // 2. Handle File Upload Gambar
        // !! PERBAIKAN: Mengecek 'image' dari request !!
        if ($request->hasFile('image')) {
            // !! PERBAIKAN: Mengambil file 'image' !!
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
            'gambar' => $imagePath, // Kolom DB tetap 'gambar', tapi isinya dari file 'image'
        ]);

        return redirect()->route('events.index')->with('success', 'Event berhasil dibuat!');
    }

    // Tampilkan detail event
    public function show(Event $event)
    {
        $event->load('ticketTypes', 'organizer');
        return view('events.show', compact('event'));
    }

    // Form untuk edit event
    public function edit(Event $event)
    {
        Gate::authorize('update', $event);
        $event->load('ticketTypes');
        return view('events.edit', compact('event'));
    }

    // Update event
    public function update(Request $request, Event $event)
    {
        Gate::authorize('update', $event);

        // 1. Validasi Input
        // !! PERBAIKAN: Mengubah 'gambar' menjadi 'image' agar sesuai form !!
        $validated = $request->validate([
            'nama_event' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
            'status' => 'required|in:upcoming,ongoing,finished',
            'deskripsi' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Diubah dari 'gambar'
        ]);

        // Hapus 'image' dari $validated karena kita akan menanganinya secara manual
        // dan kita tidak ingin mencoba mengupdate kolom 'image' (yang tidak ada)
        $dataToUpdate = $request->except(['image', '_token', '_method']);


        // 2. Handle File Upload Gambar (Jika ada file baru)
        // !! PERBAIKAN: Mengecek 'image' dari request !!
        if ($request->hasFile('image')) {
            
            // Hapus gambar lama jika ada (dari kolom 'gambar')
            if ($event->gambar) {
                Storage::disk('public')->delete($event->gambar);
            }
            
            // !! PERBAIKAN: Simpan file 'image' dan set path-nya ke kolom 'gambar' !!
            $dataToUpdate['gambar'] = $request->file('image')->store('events', 'public');
        
        } // Jika tidak ada file baru, 'gambar' lama akan tetap dipertahankan

        // 3. Update Event
        $event->update($dataToUpdate);

        // Redirect kembali ke halaman index atau show
        return redirect()->route('events.index')->with('success', 'Event berhasil diperbarui!');
    }

    // Hapus event
    public function destroy(Event $event)
    {
        Gate::authorize('delete', $event);

        // Hapus file gambar dari storage sebelum menghapus record (kolom 'gambar')
        if ($event->gambar) {
            Storage::disk('public')->delete($event->gambar);
        }

        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event berhasil dihapus!');
    }

    // ========================================================
    // == METHOD BARU UNTUK ATTENDEE MANAGEMENT ==
    // ========================================================
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

    // ========================================================
    // == METHOD BARU UNTUK CHECK-IN SCANNER ==
    // ========================================================

    /**
     * Menampilkan halaman scanner QR Code.
     */
    public function showCheckInScanner(Event $event)
    {
        Gate::authorize('update', $event);
        return view('checkin.scanner', compact('event'));
    }

    /**
     * Memproses data QR Code yang di-scan (via AJAX/Fetch).
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

        // 1. Cari tiket berdasarkan QR Code
        $ticket = Ticket::with('booking', 'ticketType')
                            ->where('qr_code', $qrCode)
                            ->first();

        // 2. Jika Tiket TIDAK DITEMUKAN
        if (!$ticket) {
            return response()->json([
                'status' => 'error',
                'message' => 'TIKET TIDAK VALID. Kode QR tidak terdaftar.'
            ], 404); // Not Found
        }

        // 3. Cek apakah tiket ini milik event yang benar
        if ($ticket->booking->event_id != $eventId) {
            return response()->json([
                'status' => 'error',
                'message' => 'TIKET SALAH EVENT. Tiket ini valid, tapi bukan untuk event ini.'
            ], 400); // Bad Request
        }

        // 4. Cek apakah tiket sudah di-check-in
        if ($ticket->statusCheckIn == 'checked-in') {
            return response()->json([
                'status' => 'warning',
                'message' => 'TIKET SUDAH DIGUNAKAN. Tiket ini sudah di-scan pada ' . ($ticket->tanggalCheckIn ? $ticket->tanggalCheckIn->format('d M Y H:i') : 'sebelumnya'),
                'ticket' => $ticket, 
            ], 409); // Conflict
        }

        // 5. Cek apakah pembayaran tiket lunas
        if ($ticket->booking->status_pembayaran != 'paid') {
             return response()->json([
                'status' => 'error',
                'message' => 'TIKET BELUM LUNAS. Pembayaran untuk booking ini masih pending.'
            ], 402); // Payment Required
        }

        // 6. SUKSES: Update status tiket
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
