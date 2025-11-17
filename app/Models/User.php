<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- IMPORT DITAMBAHKAN
use App\Models\Role;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'tanggal_daftar',
        'role_id',
        'password',
    ];

    /**
     * Atribut yang harus disembunyikan saat serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Mendapatkan cast atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'tanggal_daftar' => 'date',
        ];
    }

    /** ===================== RELASI ROLE ===================== */
    
    /**
     * Mendapatkan role yang dimiliki user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Memeriksa apakah pengguna memiliki permission tertentu.
     */
    public function hasPermission(string $permission): bool
    {
        // Admin selalu memiliki semua permission
        if ($this->isAdmin()) {
            return true; 
        }

        // Cek permission melalui Role
        return $this->role?->hasPermission($permission) ?? false;
    }

    /**
     * Mendapatkan nama role user.
     */
    public function getRoleName(): string
    {
        return $this->role?->name ?? 'No Role';
    }

    /** ===================== HELPER UNTUK ROLE ===================== */

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

    /** ===================== RELASI EVENT MANAGEMENT (DIPERBAIKI) ===================== */
    
    /**
     * Mendapatkan events yang dibuat oleh user (jika organizer).
     */
    public function events(): HasMany // <-- TYPE-HINT DITAMBAHKAN
    {
        // Menggunakan foreign key 'organizer_id'
        return $this->hasMany(Event::class, 'organizer_id');
    }

    /**
     * Mendapatkan bookings yang dibuat oleh user (jika attendee).
     */
    public function bookings(): HasMany // <-- TYPE-HINT DITAMBAHKAN
    {
        // Menggunakan foreign key 'attendee_id'
        return $this->hasMany(Booking::class, 'attendee_id');
    }

    /**
     * Mendapatkan reviews yang ditulis oleh user (jika attendee).
     */
    public function reviews(): HasMany // <-- TYPE-HINT DITAMBAHKAN
    {
        // Menggunakan foreign key 'attendee_id'
        return $this->hasMany(Review::class, 'attendee_id');
    }
}