<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $organizerRole = Role::where('name', 'organizer')->first();
        $attendeeRole = Role::where('name', 'attendee')->first();

        // Ambil semua permission yang ada
        $allPermissions = Permission::pluck('id');
        $eventManagementPermissions = Permission::whereIn('name', ['create_event', 'edit_event', 'delete_event', 'view_event'])->pluck('id');
        $attendeePermissions = Permission::whereIn('name', ['view_event', 'join_event', 'review_event'])->pluck('id');

        // 1. Admin mendapat SEMUA permission
        if ($adminRole) {
            $adminRole->permissions()->sync($allPermissions);
        }

        // 2. Organizer mendapat permission manajemen event dan attendee
        if ($organizerRole) {
            $organizerRole->permissions()->sync($eventManagementPermissions->merge($attendeePermissions)->unique());
        }

        // 3. Attendee mendapat permission sebagai pengunjung
        if ($attendeeRole) {
            $attendeeRole->permissions()->sync($attendeePermissions);
        }
    }
}
