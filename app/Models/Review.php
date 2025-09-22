<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public function attendee() {
        return $this->belongsTo(Attendee::class, 'id_attendee');
    }
    public function event() {
        return $this->belongsTo(Event::class, 'id_event');
    }

}
