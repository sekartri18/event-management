<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    // ===============================================
    // PERBAIKAN: Tambahkan kolom yang diizinkan diisi massal
    // ===============================================
    protected $fillable = [
        'event_id',
        'nama_tiket', // <-- Diperlukan untuk Mass Assignment
        'harga',      // <-- Diperlukan untuk Mass Assignment
        'kuota',      // <-- Diperlukan untuk Mass Assignment
    ];

    
    public function event() 
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
    
    public function tickets() 
    {
        return $this->hasMany(Ticket::class, 'ticket_type_id');
    }

    /**
     * Magic getter untuk mengarahkan properti yang umum (price, quantity)
     * ke nama kolom aktual di database (harga, kuota).
     * Saya juga menambahkan 'name' untuk 'nama_tiket'.
     */
    public function __get($key)
    {
        if ($key === 'name') { // Untuk blade view di form pembelian tiket
            return $this->attributes['nama_tiket'] ?? null;
        }

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
     * Tidak terlalu diperlukan jika menggunakan fillable, tapi dipertahankan jika ada kode lama yang memanggil $ticket->price = 10000;
     */
    public function __set($key, $value)
    {
        if ($key === 'name') {
             $this->attributes['nama_tiket'] = $value;
        } elseif ($key === 'price') {
            $this->attributes['harga'] = $value;
        } elseif ($key === 'available_quantity') {
            $this->attributes['kuota'] = $value;
        } else {
            parent::__set($key, $value);
        }
    }
}