<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventReviewController extends Controller
{
    public function store(Request $request, Event $event)
    {
        // 1. Validasi Input
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:500'],
        ]);

        $user = Auth::user();
        
        // Cek apakah user adalah attendee event ini (Opsional, tapi disarankan)
        // Kita asumsikan Booking model menyimpan tiket user.
        $isAttendee = $user->bookings()
                           ->whereHas('tickets.ticketType', function($query) use ($event) {
                               $query->where('event_id', $event->id);
                           })
                           ->exists();

        // Jika Anda ingin hanya attendee yang sudah check-in yang bisa review, 
        // Anda perlu memeriksa status booking/tiket. Untuk sementara, kita pakai attendee saja.
        if (!$isAttendee) {
             return redirect()->back()->with('error', 'Anda hanya dapat mengulas event yang telah Anda hadiri.');
        }

        // Cek apakah user sudah pernah memberikan review untuk event ini
        $existingReview = Review::where('user_id', $user->id)
                                ->where('event_id', $event->id)
                                ->exists();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk event ini.');
        }
        
        // 2. Simpan Review
        Review::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Ulasan Anda berhasil ditambahkan!');
    }
}