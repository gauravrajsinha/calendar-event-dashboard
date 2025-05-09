<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventAttendeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get user IDs for students and teachers
        $scienceTeacher = User::where('email', 'emily@example.com')->first();
        $mathTeacher = User::where('email', 'michael@example.com')->first();
        $englishTeacher = User::where('email', 'sarah@example.com')->first();

        $students = User::whereHas('role', function($query) {
            $query->where('slug', 'student');
        })->get();

        // Get event IDs
        $staffMeeting = Event::where('title', 'Staff Meeting')->first();
        $scienceClass = Event::where('title', 'Science 101')->first();
        $labExperiment = Event::where('title', 'Lab Experiment: Plant Cells')->first();
        $scienceProject = Event::where('title', 'Science Project Presentations')->first();
        $parentConferences = Event::where('title', 'Parent-Teacher Conferences')->first();

        // Add attendees to staff meeting
        $staffMeetingAttendees = [
            $scienceTeacher->id,
            $mathTeacher->id,
            $englishTeacher->id,
        ];

        foreach ($staffMeetingAttendees as $attendee) {
            EventAttendee::create([
                'event_id' => $staffMeeting->id,
                'user_id' => $attendee,
                'status' => 'accepted',
            ]);
        }

        // Add attendees to science class
        foreach ($students as $index => $student) {
            // Add all students to science class
            EventAttendee::create([
                'event_id' => $scienceClass->id,
                'user_id' => $student->id,
                'status' => 'accepted',
            ]);

            // Add some students to lab experiment
            if ($index < 2) {
                EventAttendee::create([
                    'event_id' => $labExperiment->id,
                    'user_id' => $student->id,
                    'status' => 'accepted',
                ]);
            }

            // Add all students to science project with different statuses
            EventAttendee::create([
                'event_id' => $scienceProject->id,
                'user_id' => $student->id,
                'status' => $index % 3 == 0 ? 'pending' : ($index % 2 == 0 ? 'accepted' : 'declined'),
            ]);
        }

        // Add teachers to parent conferences
        EventAttendee::create([
            'event_id' => $parentConferences->id,
            'user_id' => $scienceTeacher->id,
            'status' => 'accepted',
        ]);

        EventAttendee::create([
            'event_id' => $parentConferences->id,
            'user_id' => $mathTeacher->id,
            'status' => 'accepted',
        ]);
    }
}
