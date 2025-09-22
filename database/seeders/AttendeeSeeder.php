<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attendee;

class AttendeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Attendee::create([
            'nama' => 'Sadam Moreo',
            'email' => 'sadam@example.com',
            'password' => bcrypt('password'),
            'no_telepon' => '081222333444',
            'tanggal_daftar' => now(),
        ]);

        Attendee::create([
            'nama' => 'Risa Arunika',
            'email' => 'risa@example.com',
            'password' => bcrypt('risa123'),
            'no_telepon' => '081555666777',
            'tanggal_daftar' => now(),
        ]);
    }
}
