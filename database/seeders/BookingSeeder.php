<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Booking::create([
            'attendee_id' => 1,
            'status_pembayaran' => 'paid',
            'jumlah_tiket' => 2,
            'event_id' => 1,
            'tanggal_booking' => now(),
        ]);

        Booking::create([
            'attendee_id' => 2,
            'status_pembayaran' => 'pending',
            'jumlah_tiket' => 1,
            'event_id' => 2,
            'tanggal_booking' => now(),
        ]);
    }
}
