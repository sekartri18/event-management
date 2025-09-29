<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Tambahkan ini jika belum ada
use App\Models\Role; // Tambahkan ini jika belum ada

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'tanggal_daftar',
        'role_id',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'tanggal_daftar' => 'date', // Pastikan tanggal_daftar dicast ke 'date' jika perlu
        ];
    }

    /** ===================== RELASI ROLE ===================== */
    
    // Gunakan type hinting BelongsTo
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Memeriksa apakah pengguna memiliki permission tertentu.
     * Admin selalu true. Role lain dicek melalui relasi.
     */
    public function hasPermission(string $permission): bool
    {
        // Jika pengguna adalah Admin, langsung berikan akses (bypass check)
        if ($this->isAdmin()) {
            return true; 
        }

        // Cek permission melalui Role (menggunakan method hasPermission di Model Role)
        return $this->role?->hasPermission($permission) ?? false;
    }

    public function getRoleName(): string
    {
        return $this->role?->name ?? 'No Role';
    }

    /** ===================== HELPER UNTUK ROLE ===================== */

    // Tambahkan Helper untuk Admin
    public function isAdmin(): bool
    {
        return $this->getRoleName() === 'admin';
    }

    public function isOrganizer(): bool
    {
        return $this->getRoleName() === 'organizer';
    }

    public function isAttendee(): bool
    {
        return $this->getRoleName() === 'attendee';
    }

    /** ===================== RELASI EVENT MANAGEMENT ===================== */
    public function events()
    {
        // hanya berlaku jika user ini Organizer
        return $this->hasMany(Event::class, 'id_organizer');
    }

    public function bookings()
    {
        // hanya berlaku jika user ini Attendee
        return $this->hasMany(Booking::class, 'id_attendee');
    }

    public function reviews()
    {
        // hanya berlaku jika user ini Attendee
        return $this->hasMany(Review::class, 'id_attendee');
    }
}
