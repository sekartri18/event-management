<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'organizer',
                'display_name' => 'Event Organizer',
                'description' => 'User who can create and manage events',
                'is_active' => true,
            ],
            [
                'name' => 'attendee',
                'display_name' => 'Event Attendee',
                'description' => 'User who can view and join events',
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
