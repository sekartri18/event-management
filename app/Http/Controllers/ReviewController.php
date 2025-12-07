<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review; // <-- Pastikan Model Review di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Menampilkan semua reviews untuk admin (kelola ulasan).
     */
    public function index(Request $request)
    {
        // Cek apakah user adalah admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        // Get all reviews with relationships
        $query = Review::with(['event', 'attendee'])
                       ->orderBy('tanggal_review', 'desc');

        // Filter berdasarkan event search jika ada parameter
        if ($request->has('event_search') && $request->event_search) {
            $eventSearch = $request->event_search;
            $query->whereHas('event', function ($q) use ($eventSearch) {
                $q->where('nama_event', 'like', '%' . $eventSearch . '%');
            });
        }

        // Filter berdasarkan rating jika ada parameter
        if ($request->has('rating') && $request->rating) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->paginate(15);
        $events = Event::where('status', 'finished')->orderBy('nama_event', 'asc')->get();

        return view('admin.reviews.index', [
            'reviews' => $reviews,
            'events' => $events,
        ]);
    }

    /**
     * Store a newly created review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Event $event)
    {
        // =================================================================
        // !! PERBAIKAN 1: Validasi 'komentar' (bukan 'comment') !!
        // =================================================================
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000', // Diubah dari 'comment'
        ]);

        $user = Auth::user();

        // Logika Pengecekan:
        // 1. User harus 'Attendee'
        if (!$user->isAttendee()) {
            return redirect()->route('events.show', $event)->with('error', 'Hanya peserta yang dapat memberikan review.');
        }

        // 2. Event harus sudah selesai
        if ($event->status != 'finished') {
            return redirect()->route('events.show', $event)->with('error', 'Anda baru bisa memberi review setelah event selesai.');
        }

        // 3. User harus memiliki booking yang sudah lunas untuk event ini
        // (Query ini sudah benar karena Model User sudah diperbaiki)
        $hasPaidBooking = $user->bookings()
                                 ->where('event_id', $event->id)
                                 ->where('status_pembayaran', 'paid')
                                 ->exists();

        if (!$hasPaidBooking) {
            return redirect()->route('events.show', $event)->with('error', 'Anda harus membeli tiket untuk event ini sebelum bisa memberi review.');
        }

        // =================================================================
        // !! PERBAIKAN 2: Gunakan 'attendee_id' (bukan 'user_id') !!
        // Ini adalah penyebab error di screenshot Anda.
        // =================================================================
        $hasAlreadyReviewed = $event->reviews()->where('attendee_id', $user->id)->exists();

        if ($hasAlreadyReviewed) {
            return redirect()->route('events.show', $event)->with('error', 'Anda sudah pernah memberikan review untuk event ini.');
        }

        // =================================================================
        // !! PERBAIKAN 3 & 4: Simpan 'attendee_id' dan 'komentar' !!
        // =================================================================
        $event->reviews()->create([
            'attendee_id' => $user->id,         // Diubah dari 'user_id'
            'rating' => $request->rating,
            'komentar' => $request->komentar, // Diubah dari 'comment'
            'tanggal_review' => now()        // Ditambahkan agar konsisten
        ]);

        return redirect()->route('events.show', $event)->with('success', 'Terima kasih! Review Anda telah disimpan.');
    }
}