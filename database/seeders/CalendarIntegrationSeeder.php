<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CalendarIntegration;

class CalendarIntegrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CalendarIntegration::create([
            'event_id' => 1,
            'calendar_id' => 'CAL-123-TECHCONF',
        ]);

        CalendarIntegration::create([
            'event_id' => 2,
            'calendar_id' => 'CAL-456-MUSICFEST',
        ]);
    }
}
