<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarIntegration extends Model
{
    public function event() {
        return $this->belongsTo(Event::class, 'id_event');
    }

}
