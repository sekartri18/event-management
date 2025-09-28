<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Organizer
        $organizer = Role::where('name', 'organizer')->first();
        $organizerPermissions = Permission::whereIn('name', [
            'create_event',
            'edit_event',
            'delete_event',
            'view_event',
        ])->get();
        $organizer->permissions()->attach($organizerPermissions);

        // Attendee
        $attendee = Role::where('name', 'attendee')->first();
        $attendeePermissions = Permission::whereIn('name', [
            'view_event',
            'join_event',
            'review_event',
        ])->get();
        $attendee->permissions()->attach($attendeePermissions);
    }
}
