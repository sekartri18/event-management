<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    // Menggunakan kolom 'harga' dan 'kuota' dari database
    
    // Perbaikan Relasi: Pastikan foreign key di sini sesuai dengan skema Anda
    public function event() 
    {
        // Diasumsikan foreign key di tabel ticket_types adalah 'event_id'
        // Jika di DB Anda menggunakan 'id_event', ganti menjadi 'id_event'
        return $this->belongsTo(Event::class, 'event_id');
    }
    
    public function tickets() 
    {
        // Diasumsikan foreign key di tabel tickets adalah 'ticket_type_id'
        // Jika di DB Anda menggunakan 'id_jenistiket', ganti menjadi 'id_jenistiket'
        return $this->hasMany(Ticket::class, 'ticket_type_id');
    }

    /**
     * Magic getter untuk mengarahkan properti yang umum (price, quantity)
     * ke nama kolom aktual di database (harga, kuota).
     */
    public function __get($key)
    {
        if ($key === 'price') {
            return $this->attributes['harga'] ?? 0;
        }

        if ($key === 'available_quantity') {
            return $this->attributes['kuota'] ?? 0;
        }

        return parent::__get($key);
    }

    /**
     * Magic setter untuk mengarahkan properti saat disimpan.
     */
    public function __set($key, $value)
    {
        if ($key === 'price') {
            $this->attributes['harga'] = $value;
        } elseif ($key === 'available_quantity') {
            $this->attributes['kuota'] = $value;
        } else {
            parent::__set($key, $value);
        }
    }
}
