<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventType;
use App\Models\User;
use App\Models\EventAttendee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::with('eventType', 'user')->orderBy('start_time', 'asc')->get();
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $eventTypes = EventType::all();
        $users = User::all();
        return view('events.create', compact('eventTypes', 'users'));
    }

    /**
     * Store a newly created event in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'event_type_id' => 'required|exists:event_types,id',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_date' => 'required|date|after_or_equal:start_date',
            'end_time' => 'required',
            'all_day' => 'boolean',
            'is_recurring' => 'boolean',
            'recurrence_pattern' => 'nullable|string',
            'attendees' => 'nullable|array',
            'attendees.*' => 'exists:users,id',
        ]);

        // Format start and end times using IST timezone
        $startDateTime = Carbon::parse($request->start_date . ' ' . $request->start_time, 'Asia/Kolkata');
        $endDateTime = Carbon::parse($request->end_date . ' ' . $request->end_time, 'Asia/Kolkata');

        // Create the event
        $event = Event::create([
            'title' => $request->title,
            'event_type_id' => $request->event_type_id,
            'user_id' => auth()->id() ?? 1, // Use authenticated user, fallback to user 1 for demo
            'description' => $request->description,
            'location' => $request->location,
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'all_day' => $request->has('all_day'),
            'is_recurring' => $request->has('is_recurring'),
            'recurrence_pattern' => $request->recurrence_pattern,
        ]);

        // Add attendees if any
        if ($request->has('attendees')) {
            foreach ($request->attendees as $attendeeId) {
                EventAttendee::create([
                    'event_id' => $event->id,
                    'user_id' => $attendeeId,
                    'status' => 'pending',
                ]);
            }
        }

        // Return to calendar if this was a quick create
        if ($request->ajax()) {
            return response()->json(['success' => true, 'event' => $event]);
        }

        return redirect()->route('calendar.index')->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $event->load('eventType', 'user', 'attendees');
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $eventTypes = EventType::all();
        $users = User::all();
        $event->load('attendees');

        // Get the current attendee IDs
        $currentAttendees = $event->attendees->pluck('id')->toArray();

        return view('events.edit', compact('event', 'eventTypes', 'users', 'currentAttendees'));
    }

    /**
     * Update the specified event in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'event_type_id' => 'required|exists:event_types,id',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_date' => 'required|date|after_or_equal:start_date',
            'end_time' => 'required',
            'all_day' => 'boolean',
            'is_recurring' => 'boolean',
            'recurrence_pattern' => 'nullable|string',
            'attendees' => 'nullable|array',
            'attendees.*' => 'exists:users,id',
        ]);

        // Format start and end times using IST timezone
        $startDateTime = Carbon::parse($request->start_date . ' ' . $request->start_time, 'Asia/Kolkata');
        $endDateTime = Carbon::parse($request->end_date . ' ' . $request->end_time, 'Asia/Kolkata');

        // Update the event
        $event->update([
            'title' => $request->title,
            'event_type_id' => $request->event_type_id,
            'description' => $request->description,
            'location' => $request->location,
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'all_day' => $request->has('all_day'),
            'is_recurring' => $request->has('is_recurring'),
            'recurrence_pattern' => $request->recurrence_pattern,
        ]);

        // Update attendees
        if ($request->has('attendees')) {
            // Remove all current attendees
            $event->attendees()->detach();

            // Add new attendees
            foreach ($request->attendees as $attendeeId) {
                EventAttendee::create([
                    'event_id' => $event->id,
                    'user_id' => $attendeeId,
                    'status' => 'pending',
                ]);
            }
        }

        // Return JSON response for AJAX requests
        if ($request->ajax()) {
            return response()->json(['success' => true, 'event' => $event]);
        }

        return redirect()->route('calendar.index')->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified event from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('calendar.index')->with('success', 'Event deleted successfully!');
    }

    /**
     * Update the attendee status for an event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function updateAttendeeStatus(Request $request, Event $event)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,accepted,declined',
        ]);

        $attendee = EventAttendee::where('event_id', $event->id)
            ->where('user_id', $request->user_id)
            ->first();

        if ($attendee) {
            $attendee->update(['status' => $request->status]);
            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Attendee not found'], 404);
    }
}
