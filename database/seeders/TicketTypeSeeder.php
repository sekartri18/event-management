<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TicketType;

class TicketTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TicketType::create([
            'event_id' => 1,
            'nama_tiket' => 'VIP',
            'harga' => 500000,
            'kuota' => 50,
        ]);

        TicketType::create([
            'event_id' => 1,
            'nama_tiket' => 'Regular',
            'harga' => 200000,
            'kuota' => 200,
        ]);

        TicketType::create([
            'event_id' => 2,
            'nama_tiket' => 'Festival Pass',
            'harga' => 300000,
            'kuota' => 150,
        ]);
    }
}
