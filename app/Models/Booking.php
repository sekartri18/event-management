<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public function attendee() {
        return $this->belongsTo(Attendee::class, 'id_attendee');
    }
    public function event() {
        return $this->belongsTo(Event::class, 'id_event');
    }
    public function tickets() {
        return $this->hasMany(Ticket::class, 'id_booking');
    }

}
