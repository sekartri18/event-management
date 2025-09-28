<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $organizerRole = Role::where('name', 'organizer')->first();
        $attendeeRole = Role::where('name', 'attendee')->first();

        // Organizer user
        User::create([
            'name' => 'PT Eventindo',
            'email' => 'organizer@eventindo.com',
            'phone' => '081234567890',
            'tanggal_daftar' => now(),
            'role_id' => $organizerRole->id,
            'password' => Hash::make('organizer123'),
        ]);

        // Attendees user
        User::create([
            'name' => 'Sadam Moreo',
            'email' => 'sadam@example.com',
            'phone' => '081222333444',
            'tanggal_daftar' => now(),
            'role_id' => $attendeeRole->id,
            'password' => Hash::make('sadam123'),
        ]);

        User::create([
            'name' => 'Risa Arunika',
            'email' => 'risa@example.com',
            'phone' => '081555666777',
            'tanggal_daftar' => now(),
            'role_id' => $attendeeRole->id,
            'password' => Hash::make('risa123'),
        ]);
    }
}
