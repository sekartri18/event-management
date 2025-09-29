<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $organizerRole = Role::where('name', 'organizer')->first();
        $attendeeRole = Role::where('name', 'attendee')->first();

        // 1. User Admin (Full Access)
        User::factory()->create([
            'name' => 'Admin Utama',
            'email' => 'admin@app.com',
            'phone' => '081123456789',
            'role_id' => $adminRole?->id,
            'password' => Hash::make('password'), // Password default: 'password'
            'tanggal_daftar' => now(),
        ]);
        
        // 2. User Organizer (Bisa membuat dan mengelola Event)
        User::factory()->create([
            'name' => 'Organizer Contoh',
            'email' => 'organizer@app.com',
            'phone' => '081198765432',
            'role_id' => $organizerRole?->id,
            'password' => Hash::make('password'),
            'tanggal_daftar' => now(),
        ]);

        // 3. User Attendee (Pengunjung)
        User::factory()->create([
            'name' => 'Attendee Contoh',
            'email' => 'attendee@app.com',
            'phone' => '081234567890',
            'role_id' => $attendeeRole?->id,
            'password' => Hash::make('password'),
            'tanggal_daftar' => now(),
        ]);

        // Buat 10 user Attendee palsu lagi
        User::factory(10)->create([
            'role_id' => $attendeeRole?->id,
        ]);
    }
}
