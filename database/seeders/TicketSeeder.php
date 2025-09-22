<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ticket;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ticket::create([
            'ticket_type_id' => 1, // VIP
            'statusCheckIn' => 'pending',
            'tanggalCheckIn' => null,
            'nama_pemegang_tiket' => 'Sadam Moreo',
            'booking_id' => 1,
            'qr_code' => 'QR123VIPSADAM',
        ]);

        Ticket::create([
            'ticket_type_id' => 2, // Regular
            'statusCheckIn' => 'pending',
            'tanggalCheckIn' => null,
            'nama_pemegang_tiket' => 'Risa Arunika',
            'booking_id' => 2,
            'qr_code' => 'QR456REGRISA',
        ]);
    }
}
