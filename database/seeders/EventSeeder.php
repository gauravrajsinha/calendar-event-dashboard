<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get event type IDs
        $classType = EventType::where('name', 'Class')->first()->id;
        $meetingType = EventType::where('name', 'Meeting')->first()->id;
        $labType = EventType::where('name', 'Lab')->first()->id;
        $assignmentType = EventType::where('name', 'Assignment')->first()->id;
        $activityType = EventType::where('name', 'Activity')->first()->id;

        // Get user IDs
        $scienceTeacher = User::where('email', 'emily@example.com')->first()->id;
        $mathTeacher = User::where('email', 'michael@example.com')->first()->id;
        $englishTeacher = User::where('email', 'sarah@example.com')->first()->id;

        // Current date reference (April 25, 2025 as in the mockup)
        $today = Carbon::create(2025, 4, 25);

        // Create events
        $events = [
            // Staff meeting
            [
                'title' => 'Staff Meeting',
                'event_type_id' => $meetingType,
                'user_id' => $scienceTeacher,
                'description' => 'Weekly staff meeting to discuss upcoming events and curriculum updates',
                'location' => 'Conference Room 103',
                'start_time' => $today->copy()->setTime(9, 0),
                'end_time' => $today->copy()->setTime(10, 0),
                'all_day' => false,
                'is_recurring' => true,
                'recurrence_pattern' => 'weekly',
            ],

            // Science class
            [
                'title' => 'Science 101',
                'event_type_id' => $classType,
                'user_id' => $scienceTeacher,
                'description' => 'Introduction to Ecosystems',
                'location' => 'Room 205',
                'start_time' => $today->copy()->setTime(13, 30),
                'end_time' => $today->copy()->setTime(15, 0),
                'all_day' => false,
                'is_recurring' => true,
                'recurrence_pattern' => 'weekly',
            ],

            // Lab experiment
            [
                'title' => 'Lab Experiment: Plant Cells',
                'event_type_id' => $labType,
                'user_id' => $scienceTeacher,
                'description' => 'Laboratory session to examine plant cell structures under the microscope',
                'location' => 'Science Lab',
                'start_time' => $today->copy()->addDays(3)->setTime(13, 30),
                'end_time' => $today->copy()->addDays(3)->setTime(15, 0),
                'all_day' => false,
                'is_recurring' => false,
                'recurrence_pattern' => null,
            ],

            // Science project
            [
                'title' => 'Science Project Presentations',
                'event_type_id' => $activityType,
                'user_id' => $scienceTeacher,
                'description' => 'Students will present their science projects to the class',
                'location' => 'Auditorium',
                'start_time' => $today->copy()->addDays(7)->setTime(10, 0),
                'end_time' => $today->copy()->addDays(7)->setTime(12, 0),
                'all_day' => false,
                'is_recurring' => false,
                'recurrence_pattern' => null,
            ],

            // Parent-teacher conferences
            [
                'title' => 'Parent-Teacher Conferences',
                'event_type_id' => $meetingType,
                'user_id' => $scienceTeacher,
                'description' => 'End of semester parent-teacher conferences',
                'location' => 'Classroom 205',
                'start_time' => $today->copy()->addDays(10)->setTime(15, 30),
                'end_time' => $today->copy()->addDays(10)->setTime(19, 0),
                'all_day' => false,
                'is_recurring' => false,
                'recurrence_pattern' => null,
            ],

            // Second day of parent-teacher conferences
            [
                'title' => 'Parent-Teacher Conferences',
                'event_type_id' => $meetingType,
                'user_id' => $scienceTeacher,
                'description' => 'End of semester parent-teacher conferences',
                'location' => 'Classroom 205',
                'start_time' => $today->copy()->addDays(11)->setTime(15, 30),
                'end_time' => $today->copy()->addDays(11)->setTime(19, 0),
                'all_day' => false,
                'is_recurring' => false,
                'recurrence_pattern' => null,
            ],

            // Unit test
            [
                'title' => 'End of Unit Test: Ecosystems',
                'event_type_id' => $assignmentType,
                'user_id' => $scienceTeacher,
                'description' => 'End of unit assessment on ecosystems',
                'location' => 'Room 205',
                'start_time' => $today->copy()->addDays(15)->setTime(9, 0),
                'end_time' => $today->copy()->addDays(15)->setTime(10, 30),
                'all_day' => false,
                'is_recurring' => false,
                'recurrence_pattern' => null,
            ],

            // Math class
            [
                'title' => 'Algebra 101',
                'event_type_id' => $classType,
                'user_id' => $mathTeacher,
                'description' => 'Introduction to algebraic equations',
                'location' => 'Room 302',
                'start_time' => $today->copy()->addDays(1)->setTime(10, 0),
                'end_time' => $today->copy()->addDays(1)->setTime(11, 30),
                'all_day' => false,
                'is_recurring' => true,
                'recurrence_pattern' => 'weekly',
            ],

            // English class
            [
                'title' => 'English Literature',
                'event_type_id' => $classType,
                'user_id' => $englishTeacher,
                'description' => 'Study of classic American literature',
                'location' => 'Room 104',
                'start_time' => $today->copy()->addDays(2)->setTime(14, 0),
                'end_time' => $today->copy()->addDays(2)->setTime(15, 30),
                'all_day' => false,
                'is_recurring' => true,
                'recurrence_pattern' => 'weekly',
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
