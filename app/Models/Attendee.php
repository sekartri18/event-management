<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    public function bookings() {
        return $this->hasMany(Booking::class, 'id_attendee');
    }
    public function reviews() {
        return $this->hasMany(Review::class, 'id_attendee');
    }

}
