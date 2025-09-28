<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        ];
    }

    /** ===================== RELASI ROLE ===================== */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasPermission(string $permission): bool
    {
        return $this->role?->hasPermission($permission) ?? false;
    }

    public function getRoleName(): string
    {
        return $this->role?->name ?? 'No Role';
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
