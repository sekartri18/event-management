<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function organizer() {
        return $this->belongsTo(Organizer::class, 'id_organizer');
    }
    public function ticketTypes() {
        return $this->hasMany(TicketType::class, 'id_event');
    }
    public function bookings() {
        return $this->hasMany(Booking::class, 'id_event');
    }
    public function reviews() {
        return $this->hasMany(Review::class, 'id_event');
    }
    public function calendarIntegrations() {
        return $this->hasMany(CalendarIntegration::class, 'id_event');
    }

}
