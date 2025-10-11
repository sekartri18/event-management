<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'booking_id',
        'ticket_type_id',
        'qr_code',
        'statusCheckIn',
        'nama_pemegang_tiket', 
    ];

    public function ticketType() {
        return $this->belongsTo(TicketType::class, 'ticket_type_id');
    }
    
    public function booking() {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
