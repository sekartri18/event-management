<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,

            EventSeeder::class,
            TicketTypeSeeder::class,
            BookingSeeder::class,
            TicketSeeder::class,
            ReviewSeeder::class,
            CalendarIntegrationSeeder::class,
        ]);
    }
}
