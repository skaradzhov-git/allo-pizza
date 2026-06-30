<?php

namespace Database\Seeders;

use App\Models\WorkingHour;
use Illuminate\Database\Seeder;

class WorkingHourSeeder extends Seeder
{
    public function run(): void
    {
        $schedule = [
            1 => ['opens_at' => '11:00:00', 'closes_at' => '23:00:00'],
            2 => ['opens_at' => '11:00:00', 'closes_at' => '23:00:00'],
            3 => ['opens_at' => '11:00:00', 'closes_at' => '23:00:00'],
            4 => ['opens_at' => '11:00:00', 'closes_at' => '23:00:00'],
            5 => ['opens_at' => '11:00:00', 'closes_at' => '23:30:00'],
            6 => ['opens_at' => '11:00:00', 'closes_at' => '23:30:00'],
            7 => ['opens_at' => '12:00:00', 'closes_at' => '22:00:00'],
        ];

        foreach ($schedule as $dayOfWeek => $hours) {
            WorkingHour::query()->updateOrCreate(
                ['day_of_week' => $dayOfWeek],
                [
                    'opens_at' => $hours['opens_at'],
                    'closes_at' => $hours['closes_at'],
                    'is_closed' => false,
                ]
            );
        }
    }
}
