<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Event Management
            ['name' => 'create_event', 'display_name' => 'Create Event', 'group' => 'event'],
            ['name' => 'edit_event', 'display_name' => 'Edit Event', 'group' => 'event'],
            ['name' => 'delete_event', 'display_name' => 'Delete Event', 'group' => 'event'],
            ['name' => 'view_event', 'display_name' => 'View Event', 'group' => 'event'],

            // Attendee Actions
            ['name' => 'join_event', 'display_name' => 'Join Event', 'group' => 'attendee'],
            ['name' => 'review_event', 'display_name' => 'Review Event', 'group' => 'attendee'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
