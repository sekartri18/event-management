<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organizer;

class OrganizerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Organizer::create([
            'nama' => 'PT Eventindo',
            'password' => bcrypt('password123'),
            'no_hp' => '081234567890',
            'email' => 'organizer@eventindo.com',
            'tanggal_daftar' => now(),
        ]);

    }
}
