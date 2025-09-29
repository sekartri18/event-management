<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'System Administrator',
                'description' => 'User with full access to manage system settings and content.',
                'is_active' => 1
            ]
        );
        
        Role::firstOrCreate(
            ['name' => 'organizer'],
            [
                'display_name' => 'Event Organizer',
                'description' => 'User who can create and manage events',
                'is_active' => 1
            ]
        );

        Role::firstOrCreate(
            ['name' => 'attendee'],
            [
                'display_name' => 'Event Attendee',
                'description' => 'User who can join/book events and leave reviews',
                'is_active' => 1
            ]
        );
    }
}
