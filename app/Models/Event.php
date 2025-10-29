<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    // Tambahkan field yang boleh diisi secara massal (mass assignable)
    protected $fillable = [
        'organizer_id', 
        'nama_event',
        'tanggal_mulai',
        'tanggal_selesai',
        'gambar',
        'lokasi',
        'status',
        'deskripsi',
    ];

    // Pastikan relasi menggunakan nama kolom yang benar (organizer_id/event_id)
    public function organizer() {
        return $this->belongsTo(User::class, 'organizer_id');
    }
    public function ticketTypes() {
        return $this->hasMany(TicketType::class, 'event_id');
    }
    public function bookings() {
        return $this->hasMany(Booking::class, 'event_id');
    }
    public function reviews() {
        return $this->hasMany(Review::class, 'event_id');
    }
    public function calendarIntegrations() {
        return $this->hasMany(CalendarIntegration::class, 'event_id');
    }
}