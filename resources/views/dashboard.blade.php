@extends('layouts.app')

@section('content')
    <div class="bg-white px-4 sm:px-6 lg:px-8 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>
                <p class="text-sm text-gray-500">Welcome to EduConnect Calendar</p>
            </div>
            <div>
                <a href="{{ route('calendar.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary text-white shadow-sm rounded-button text-sm font-medium hover:bg-indigo-700 focus:outline-none whitespace-nowrap">
                    <div class="w-4 h-4 mr-2 flex items-center justify-center">
                        <i class="ri-calendar-line"></i>
                    </div>
                    Go to Calendar
                </a>
            </div>
        </div>
    </div>

    <div class="p-4 sm:p-6 lg:p-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Today's Events Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Today's Events</h3>
                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center text-primary">
                            <i class="ri-calendar-check-line"></i>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="space-y-4" id="today-events-dashboard">
                        <div class="flex items-center justify-center h-60 text-gray-400">
                            <p>Loading today's events...</p>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-4 sm:px-6 bg-gray-50 border-t border-gray-200">
                    <a href="{{ route('calendar.index') }}" class="text-sm font-medium text-primary hover:text-indigo-800">
                        View all events →
                    </a>
                </div>
            </div>

            <!-- Upcoming Events Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Upcoming Events</h3>
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                            <i class="ri-calendar-todo-line"></i>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div class="space-y-4" id="upcoming-events-dashboard">
                        <div class="flex items-center justify-center h-60 text-gray-400">
                            <p>Loading upcoming events...</p>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-4 sm:px-6 bg-gray-50 border-t border-gray-200">
                    <a href="{{ route('calendar.index') }}" class="text-sm font-medium text-primary hover:text-indigo-800">
                        View all events →
                    </a>
                </div>
            </div>

            <!-- Quick Links Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Quick Links</h3>
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-600">
                            <i class="ri-links-line"></i>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <ul class="divide-y divide-gray-200">
                        <li class="py-3 flex items-center">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                <i class="ri-add-circle-line"></i>
                            </div>
                            <div class="ml-3">
                                <a href="{{ route('events.create') }}"
                                    class="text-sm font-medium text-gray-900 hover:text-primary">Create New Event</a>
                            </div>
                        </li>
                        <li class="py-3 flex items-center">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center text-purple-600">
                                <i class="ri-team-line"></i>
                            </div>
                            <div class="ml-3">
                                <a href="#" class="text-sm font-medium text-gray-900 hover:text-primary">Manage
                                    Students</a>
                            </div>
                        </li>
                        <li class="py-3 flex items-center">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                                <i class="ri-file-list-line"></i>
                            </div>
                            <div class="ml-3">
                                <a href="#" class="text-sm font-medium text-gray-900 hover:text-primary">Manage
                                    Assignments</a>
                            </div>
                        </li>
                        <li class="py-3 flex items-center">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-red-600">
                                <i class="ri-settings-line"></i>
                            </div>
                            <div class="ml-3">
                                <a href="#" class="text-sm font-medium text-gray-900 hover:text-primary">Account
                                    Settings</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load today's events for the dashboard
            function loadTodayEvents() {
                const today = new Date();
                const todayStart = moment(today).startOf('day').toISOString();
                const todayEnd = moment(today).endOf('day').toISOString();

                fetch(`{{ route('calendar.events') }}?start=${todayStart}&end=${todayEnd}`)
                    .then(response => response.json())
                    .then(events => {
                        const todayEventsContainer = document.getElementById('today-events-dashboard');

                        if (events.length === 0) {
                            todayEventsContainer.innerHTML = `
                            <div class="flex items-center justify-center h-60">
                                <p class="text-gray-400">No events scheduled for today</p>
                            </div>
                        `;
                            return;
                        }

                        let eventsHtml = '';

                        events.forEach(event => {
                            const startTime = moment(event.start).format('h:mm A');
                            const endTime = moment(event.end).format('h:mm A');

                            eventsHtml += `
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <div class="w-2 h-2 rounded-full bg-${event.color} mr-3"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">${event.title}</p>
                                    <p class="text-xs text-gray-500">${startTime} - ${endTime}</p>
                                </div>
                                <a href="/events/${event.id}" class="ml-2 flex-shrink-0 text-gray-400 hover:text-gray-500">
                                    <i class="ri-arrow-right-s-line"></i>
                                </a>
                            </div>
                        `;
                        });

                        todayEventsContainer.innerHTML = eventsHtml;
                    })
                    .catch(error => {
                        console.error('Error loading today\'s events:', error);
                        document.getElementById('today-events-dashboard').innerHTML = `
                        <div class="flex items-center justify-center h-60">
                            <p class="text-red-500">Error loading events</p>
                        </div>
                    `;
                    });
            }

            // Load upcoming events for the dashboard
            function loadUpcomingEvents() {
                const startDate = moment().add(1, 'day').startOf('day').toISOString();
                const endDate = moment().add(7, 'days').endOf('day').toISOString();

                fetch(`{{ route('calendar.events') }}?start=${startDate}&end=${endDate}`)
                    .then(response => response.json())
                    .then(events => {
                        const upcomingEventsContainer = document.getElementById('upcoming-events-dashboard');

                        if (events.length === 0) {
                            upcomingEventsContainer.innerHTML = `
                            <div class="flex items-center justify-center h-60">
                                <p class="text-gray-400">No upcoming events in the next 7 days</p>
                            </div>
                        `;
                            return;
                        }

                        // Sort events by date
                        events.sort((a, b) => new Date(a.start) - new Date(b.start));

                        // Take only the first 5 events
                        const upcomingEvents = events.slice(0, 5);

                        let eventsHtml = '';

                        upcomingEvents.forEach(event => {
                            const date = moment(event.start).format('ddd, MMM D');
                            const time = moment(event.start).format('h:mm A');

                            eventsHtml += `
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <div class="w-2 h-2 rounded-full bg-${event.color} mr-3"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">${event.title}</p>
                                    <p class="text-xs text-gray-500">${date} • ${time}</p>
                                </div>
                                <a href="/events/${event.id}" class="ml-2 flex-shrink-0 text-gray-400 hover:text-gray-500">
                                    <i class="ri-arrow-right-s-line"></i>
                                </a>
                            </div>
                        `;
                        });

                        upcomingEventsContainer.innerHTML = eventsHtml;
                    })
                    .catch(error => {
                        console.error('Error loading upcoming events:', error);
                        document.getElementById('upcoming-events-dashboard').innerHTML = `
                        <div class="flex items-center justify-center h-60">
                            <p class="text-red-500">Error loading events</p>
                        </div>
                    `;
                    });
            }

            // Initial load of today's and upcoming events
            loadTodayEvents();
            loadUpcomingEvents();
        });
    </script>
@endpush
