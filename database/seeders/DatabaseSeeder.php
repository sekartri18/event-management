<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            OrganizerSeeder::class,
            AttendeeSeeder::class,
            EventSeeder::class,
            TicketTypeSeeder::class,
            BookingSeeder::class,
            TicketSeeder::class,
            ReviewSeeder::class,
            CalendarIntegrationSeeder::class,
        ]);
    }
}
