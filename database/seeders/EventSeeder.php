<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::create([
            'organizer_id' => 1,
            'status' => 'upcoming',
            'deskripsi' => 'Tech Conference 2025',
            'lokasi' => 'Jakarta',
            'nama_event' => 'Tech Conference',
            'tanggal_mulai' => '2025-11-01',
            'tanggal_selesai' => '2025-11-03',
        ]);

        Event::create([
            'organizer_id' => 1,
            'status' => 'ongoing',
            'deskripsi' => 'Music Festival Tahunan',
            'lokasi' => 'Bandung',
            'nama_event' => 'Indo Music Fest',
            'tanggal_mulai' => '2025-09-25',
            'tanggal_selesai' => '2025-09-27',
        ]);
    }
}
