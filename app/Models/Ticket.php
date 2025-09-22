<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public function ticketType() {
        return $this->belongsTo(TicketType::class, 'id_jenistiket');
    }
    public function booking() {
        return $this->belongsTo(Booking::class, 'id_booking');
    }

}
