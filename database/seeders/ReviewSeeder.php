<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Review::create([
            'attendee_id' => 1,
            'event_id' => 1,
            'rating' => 5,
            'komentar' => 'Event sangat bermanfaat!',
            'tanggal_review' => now(),
        ]);

        Review::create([
            'attendee_id' => 2,
            'event_id' => 2,
            'rating' => 4,
            'komentar' => 'Seru banget acaranya!',
            'tanggal_review' => now(),
        ]);
    }
}
