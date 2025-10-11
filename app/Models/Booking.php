<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ticket; 

class Booking extends Model
{
    /**
     * Kolom yang dapat diisi secara massal.
     * MENGGUNAKAN NAMA KOLOM DARI DB: attendee_id, status_pembayaran, tanggal_booking, jumlah_tiket.
     */
    protected $fillable = [
        'attendee_id',          
        'event_id',
        'status_pembayaran',    
        'tanggal_booking',      
        'jumlah_tiket',         
        'total_amount',         
        'payment_method',      
    ];

    // --- ACCESSOR/MUTATOR UNTUK KEMUDAHAN KODE ---

    // Memungkinkan penggunaan $booking->status
    public function getStatusAttribute()
    {
        return $this->attributes['status_pembayaran'] ?? null;
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status_pembayaran'] = $value;
    }

    /**
     * Relasi: Booking memiliki banyak Tiket.
     */
    public function tickets()
    {
        // FIX: Memberikan nama Model Ticket secara eksplisit.
        return $this->hasMany(Ticket::class, 'booking_id'); 
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'attendee_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
