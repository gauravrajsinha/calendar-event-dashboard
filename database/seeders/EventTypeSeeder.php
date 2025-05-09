<?php

namespace Database\Seeders;

use App\Models\EventType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eventTypes = [
            [
                'name' => 'Class',
                'color' => 'blue-500',
                'icon' => 'ri-book-open-line',
                'description' => 'Regular class or lesson',
            ],
            [
                'name' => 'Meeting',
                'color' => 'purple-500',
                'icon' => 'ri-team-line',
                'description' => 'Staff or parent meetings',
            ],
            [
                'name' => 'Lab',
                'color' => 'green-500',
                'icon' => 'ri-test-tube-line',
                'description' => 'Laboratory sessions or experiments',
            ],
            [
                'name' => 'Assignment',
                'color' => 'yellow-500',
                'icon' => 'ri-file-list-line',
                'description' => 'Assignment or test due dates',
            ],
            [
                'name' => 'Activity',
                'color' => 'red-500',
                'icon' => 'ri-calendar-event-line',
                'description' => 'School activities or events',
            ],
        ];

        foreach ($eventTypes as $eventType) {
            EventType::create($eventType);
        }
    }
}
