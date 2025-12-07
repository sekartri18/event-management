<?php

namespace App\Models;

// Ditambahkan untuk factory
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Import ini diperlukan untuk type-hinting relasi (best practice)
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    // Trait untuk factory
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'event_id',
        'attendee_id', // Sesuai dengan migrasi
        'rating',
        'komentar',    // Sesuai dengan migrasi
        'tanggal_review',
    ];

    /**
     * Cast attributes to native types.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'tanggal_review' => 'datetime',
    ];

    /**
     * Mendapatkan user (attendee) yang menulis review.
     */
    public function attendee(): BelongsTo
    {
        // Relasi ke User::class, menggunakan foreign key 'attendee_id'
        return $this->belongsTo(User::class, 'attendee_id');
    }

    /**
     * Mendapatkan event yang di-review.
     */
    public function event(): BelongsTo
    {
        // Relasi ke Event::class, menggunakan foreign key 'event_id'
        return $this->belongsTo(Event::class, 'event_id');
    }
}