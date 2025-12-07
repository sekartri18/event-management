<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Mengizinkan pengisian kolom yang digunakan dalam BookingController::store()
     */
    protected $fillable = [
        'attendee_id',
        'event_id',
        'total_amount',
        'admin_fee',
        'status_pembayaran',
        'tanggal_booking',
        'jumlah_tiket',
        'payment_method', // Digunakan di processPayment
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'tanggal_booking' => 'datetime',
    ];

    // Relasi ke user (attendee)
    public function attendee()
    {
        return $this->belongsTo(User::class, 'attendee_id');
    }

    // Relasi ke event
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    // Relasi ke tiket (banyak tiket dalam satu booking)
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'booking_id');
    }
}
