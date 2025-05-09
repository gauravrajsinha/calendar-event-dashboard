<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventType;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Display the calendar view.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all event types for filtering
        $eventTypes = EventType::all();

        // Get all users for attendees
        $users = User::all();

        // Initialize the calendar with current date using IST timezone
        $currentDate = Carbon::now('Asia/Kolkata');
        $currentMonth = $currentDate->format('F Y');

        // Return the calendar view
        return view('calendar.index', compact('eventTypes', 'currentDate', 'currentMonth', 'users'));
    }

    /**
     * Get events for a specific month.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getEvents(Request $request)
    {
        // Get the start and end dates from the request
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);

        // Get events between the start and end dates
        $events = Event::whereBetween('start_time', [$start, $end])
            ->orWhereBetween('end_time', [$start, $end])
            ->with('eventType', 'user', 'attendees')
            ->get();

        // Format events for FullCalendar
        $formattedEvents = [];

        foreach ($events as $event) {
            $formattedEvents[] = [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_time->toIso8601String(),
                'end' => $event->end_time->toIso8601String(),
                'allDay' => $event->all_day,
                'color' => $event->eventType->color,
                'description' => $event->description,
                'location' => $event->location,
                'creator' => $event->user->full_name,
                'eventType' => $event->eventType->name,
                'eventTypeId' => $event->event_type_id,
                'attendees' => $event->attendees->count(),
            ];
        }

        return response()->json($formattedEvents);
    }
}
