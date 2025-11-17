<?php

namespace App\Models;

// TAMBAHKAN baris 'use' ini
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    // TAMBAHKAN baris 'use' ini
    use HasFactory;

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

    /**
     * TAMBAHKAN BLOK $casts INI
     * Ini akan mengubah kolom tanggal/waktu Anda menjadi objek Carbon (PHP DateTime)
     * secara otomatis, yang sangat penting untuk library kalender.
     */
    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
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