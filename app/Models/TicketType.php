<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    public function event() {
        return $this->belongsTo(Event::class, 'id_event');
    }
    public function tickets() {
        return $this->hasMany(Ticket::class, 'id_jenistiket');
    }

}
