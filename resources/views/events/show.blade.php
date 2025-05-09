@extends('layouts.app')

@section('content')
    <div class="bg-white px-4 sm:px-6 lg:px-8 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Event Details</h1>
                <p class="text-sm text-gray-500">{{ $event->start_time->format('l, F d, Y') }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('calendar.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-button bg-white text-gray-700 hover:bg-gray-50 focus:outline-none">
                    <i class="ri-arrow-left-line mr-2"></i>
                    Back to Calendar
                </a>
                <a href="{{ route('events.edit', $event) }}"
                    class="inline-flex items-center px-4 py-2 bg-primary text-white shadow-sm text-sm font-medium rounded-button hover:bg-indigo-700 focus:outline-none">
                    <i class="ri-edit-line mr-2"></i>
                    Edit Event
                </a>
            </div>
        </div>
    </div>

    <div class="p-4 sm:p-6 lg:p-8">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="space-y-6">
                    <!-- Event Details -->
                    <div class="flex items-start">
                        <div
                            class="w-12 h-12 flex items-center justify-center bg-{{ $event->eventType->color }}-100 rounded-lg text-{{ $event->eventType->color }}-500 flex-shrink-0">
                            <i class="{{ $event->eventType->icon ?? 'ri-calendar-event-line' }} text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-xl font-semibold text-gray-900">{{ $event->title }}</h2>
                            <div class="mt-1 flex items-center text-sm text-gray-500">
                                <span
                                    class="inline-block bg-{{ $event->eventType->color }}-100 text-{{ $event->eventType->color }}-700 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    {{ $event->eventType->name }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Time and Location Info -->
                    <div class="bg-gray-50 rounded-lg p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Date & Time</h3>
                            <p class="text-sm text-gray-700">
                                @if ($event->all_day)
                                    <span class="font-medium">All Day</span> on {{ $event->start_time->format('F d, Y') }}
                                @else
                                    {{ $event->start_time->format('F d, Y') }} at
                                    {{ $event->start_time->format('g:i A') }}
                                    <br>to {{ $event->end_time->format('F d, Y') }} at
                                    {{ $event->end_time->format('g:i A') }}
                                @endif
                            </p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Location</h3>
                            <p class="text-sm text-gray-700">{{ $event->location ?: 'No location specified' }}</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Description</h3>
                        <div class="mt-2 p-4 bg-gray-50 rounded-lg prose prose-sm max-w-none">
                            {{ $event->description ?: 'No description provided' }}
                        </div>
                    </div>

                    <!-- Organizer -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Organizer</h3>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                <i class="ri-user-line text-gray-600"></i>
                            </div>
                            <span class="ml-2 text-sm text-gray-700">{{ $event->user->full_name }}</span>
                        </div>
                    </div>

                    <!-- Attendees -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">Attendees
                            ({{ $event->attendees->count() }})</h3>
                        @if ($event->attendees->count() > 0)
                            <div class="mt-2 space-y-2">
                                @foreach ($event->attendees as $attendee)
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                            <i class="ri-user-line text-gray-600"></i>
                                        </div>
                                        <div class="ml-2">
                                            <p class="text-sm font-medium text-gray-700">{{ $attendee->full_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $attendee->pivot->status }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No attendees</p>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline"
                            onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 border border-red-300 text-red-700 rounded-button text-sm font-medium hover:bg-red-50">
                                Delete Event
                            </button>
                        </form>
                        <a href="{{ route('events.edit', $event) }}"
                            class="px-4 py-2 bg-primary text-white rounded-button text-sm font-medium hover:bg-indigo-700">
                            Edit Event
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
