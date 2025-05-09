@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --sidebar-width: 16rem;
            --transition-speed: 0.3s;
        }

        /* Custom scrolling for event lists */
        #upcoming-events,
        #today-events {
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE and Edge */
        }

        #upcoming-events::-webkit-scrollbar,
        #today-events::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari, Opera */
        }

        /* Toggle buttons styling */
        #toggle-sidebar {
            cursor: pointer;
            width: 32px;
            height: 32px;
            background-color: white;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 999;
            transition: all 0.2s ease;
        }

        #toggle-sidebar {
            left: -16px;
        }

        #toggle-sidebar:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            background-color: #f9fafb;
        }

        /* Rotate icon when sidebar is hidden */
        .sidebar-hidden .toggle-icon {
            transform: rotate(180deg);
        }

        @media (max-width: 1023px) {
            #toggle-sidebar {
                display: none;
            }
        }

        /* Ensure height calculation works correctly */
        .event-sidebar>div {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .event-sidebar .overflow-y-auto {
            height: 100%;
            /* or a specific height */
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Main calendar container layout */
        .calendar-container {
            position: relative;
            width: 100%;
            height: 100%;
            transition: all var(--transition-speed) ease;
        }

        /* Main calendar grid layout */
        .main-calendar-grid {
            display: flex;
            height: calc(100vh - 13rem);
            /* Fixed height */
            min-height: 600px;
            /* Minimum height */
            position: relative;
            transition: all var(--transition-speed) ease;
        }

        /* Event sidebar styling */
        .event-sidebar {
            background-color: #ffffff;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.05);
            transition: all var(--transition-speed) ease;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            min-width: var(--sidebar-width);
            width: var(--sidebar-width);
            flex: 0 0 var(--sidebar-width);
            position: relative;
            border-left: 1px solid #e5e7eb;
        }

        /* Calendar flex container */
        .calendar-flex-container {
            flex: 1;
            background-color: #f8fafc;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all var(--transition-speed) ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            padding: 1rem;
        }

        /* Fixed FC Calendar container */
        #calendar {
            height: 100% !important;
            flex-grow: 1;
        }

        .fc-view-harness {
            height: 100% !important;
        }

        .fc .fc-scrollgrid-liquid {
            height: 100%;
        }

        /* Fix sidebar content overflow */
        .event-sidebar>div {
            overflow-y: hidden;
            display: flex;
            flex-direction: column;
        }

        #today-events {
            max-height: 30%;
            flex-grow: 0;
            flex-shrink: 0;
        }

        #upcoming-events {
            flex-grow: 1;
            overflow-y: auto;
        }

        /* When sidebar is hidden */
        .event-sidebar.hidden {
            width: 0;
            min-width: 0;
            flex: 0 0 0;
            padding: 0;
            margin: 0;
            opacity: 0;
            pointer-events: none;
        }

        /* When sidebar is hidden, expand calendar container */
        .sidebar-hidden .calendar-flex-container {
            flex: 1;
            width: 100%;
        }

        /* Calendar day styling */
        .fc-daygrid-day {
            transition: all 0.2s ease;
        }

        .fc-daygrid-day:hover {
            background-color: rgba(79, 70, 229, 0.08) !important;
            transform: scale(1.02);
        }

        .fc-daygrid-day.fc-day-today {
            background-color: rgba(79, 70, 229, 0.15) !important;
        }

        /* Calendar header and tools styling */
        .calendar-header {
            background: linear-gradient(to right, #f0f4ff, #f8fafc);
            border-bottom: 1px solid rgba(203, 213, 225, 0.5);
        }

        .view-option.active {
            background-color: #4F46E5;
            color: white;
            box-shadow: 0 2px 4px rgba(79, 70, 229, 0.3);
        }

        /* Add smooth transitions */
        .view-option,
        button,
        .event-card {
            transition: all 0.2s ease;
        }

        /* Event card styling */
        .event-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease;
            border-left: 4px solid;
        }

        .event-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Add subtle background patterns */
        .bg-subtle-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%234F46E5' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
            background-position: center center;
        }
    </style>
    <!-- Role selector -->
    <div class="bg-white px-4 sm:px-6 lg:px-8 py-4 border-b border-gray-200 calendar-header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Calendar</h1>
                <p class="text-sm text-gray-500">{{ $currentDate->format('l, F d, Y') }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">View as:</span>
                <div class="relative">
                    <button type="button" id="role-dropdown-button"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-button bg-white text-gray-700 hover:bg-gray-50 focus:outline-none whitespace-nowrap">
                        <span id="selected-role">Teacher</span>
                        <div class="w-4 h-4 ml-2 flex items-center justify-center">
                            <i class="ri-arrow-down-s-line"></i>
                        </div>
                    </button>
                    <div id="role-dropdown-menu"
                        class="hidden absolute right-0 mt-1 w-48 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-1">
                            <button
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer"
                                data-role="Teacher">Teacher</button>
                            <button
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer"
                                data-role="Student">Student</button>
                            <button
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer"
                                data-role="Administrator">Administrator</button>
                            <button
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer"
                                data-role="Parent">Parent</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Tools -->
    <div class="bg-white px-4 sm:px-6 lg:px-8 py-4 border-b border-gray-200 calendar-header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
            <div class="flex items-center space-x-4">
                <button class="text-gray-500 hover:text-primary focus:outline-none" id="prev-month">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <i class="ri-arrow-left-s-line"></i>
                    </div>
                </button>
                <h2 class="text-lg font-semibold text-gray-900" id="current-month">{{ $currentMonth }}</h2>
                <button class="text-gray-500 hover:text-primary focus:outline-none" id="next-month">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <i class="ri-arrow-right-s-line"></i>
                    </div>
                </button>
                <button class="text-sm text-primary hover:text-indigo-700 font-medium whitespace-nowrap"
                    id="today-button">Today</button>
            </div>
            <div class="flex items-center space-x-2">
                <div class="relative">
                    <button type="button" id="filter-dropdown-button"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-button bg-white text-gray-700 hover:bg-gray-50 focus:outline-none whitespace-nowrap">
                        <div class="w-4 h-4 mr-2 flex items-center justify-center">
                            <i class="ri-filter-3-line"></i>
                        </div>
                        <span>Filter</span>
                    </button>
                    <div id="filter-dropdown-menu"
                        class="hidden absolute left-0 mt-1 w-48 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-1" id="event-type-filters">
                            @foreach ($eventTypes as $eventType)
                                <div class="px-4 py-2 flex items-center">
                                    <input type="checkbox" id="filter-{{ $eventType->id }}" class="mr-2 event-type-filter"
                                        value="{{ $eventType->id }}" checked>
                                    <label for="filter-{{ $eventType->id }}"
                                        class="text-sm text-gray-700 flex items-center">
                                        <span class="w-3 h-3 rounded-full bg-{{ $eventType->color }} mr-2"></span>
                                        {{ $eventType->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="bg-gray-100 rounded-lg p-1 flex">
                    <button class="view-option active px-3 py-1 rounded-lg text-sm font-medium whitespace-nowrap"
                        data-view="month">Month</button>
                    <button class="view-option px-3 py-1 rounded-lg text-sm font-medium whitespace-nowrap"
                        data-view="week">Week</button>
                    <button class="view-option px-3 py-1 rounded-lg text-sm font-medium whitespace-nowrap"
                        data-view="day">Day</button>
                    <button class="view-option px-3 py-1 rounded-lg text-sm font-medium whitespace-nowrap"
                        data-view="list">List</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main calendar area -->
    <div class="main-calendar-grid bg-subtle-pattern">
        <!-- Calendar Grid -->
        <div class="calendar-flex-container">
            <div id="calendar" class="h-full"></div>
        </div>

        <!-- Event Details Sidebar (shows on larger screens) -->
        <div class="event-sidebar border-gray-200 bg-white relative overflow-hidden">
            <!-- Toggle button for sidebar -->
            <button id="toggle-sidebar" type="button"
                class="absolute -left-4 top-1/2 transform -translate-y-1/2 bg-white border border-gray-200 rounded-full p-3 shadow-md lg:block z-50 hover:bg-gray-50"
                title="Toggle sidebar">
                <i class="ri-arrow-right-s-line toggle-icon"></i>
            </button>

            <div class="p-6 h-full flex flex-col overflow-hidden">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Today</h3>
                    <p class="text-sm text-gray-500">{{ $currentDate->format('l, F d, Y') }}</p>
                </div>

                <div id="today-events" class="space-y-4 overflow-y-auto pr-2">
                    <!-- Today's events will be loaded here via JavaScript -->
                    <div class="flex items-center justify-center h-32 text-gray-400">
                        <p>Loading today's events...</p>
                    </div>
                </div>

                <div class="mt-8 flex flex-col flex-grow min-h-0">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Upcoming Events (2 weeks)</h3>

                    <div id="upcoming-events" class="space-y-4 overflow-y-auto flex-grow pr-2 pb-16">
                        <!-- Upcoming events will be loaded here via JavaScript -->
                        <div class="flex items-center justify-center h-32 text-gray-400">
                            <p>Loading upcoming events...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Details Modal -->
    <div id="event-details-modal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900" id="event-title">Event Details</h3>
                <button id="close-details-modal" class="text-gray-400 hover:text-gray-500">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <i class="ri-close-line"></i>
                    </div>
                </button>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div id="event-info">
                        <!-- Event details will be loaded here -->
                    </div>

                    <div class="mt-6 flex items-center justify-end space-x-3">
                        <form id="delete-event-form" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 border border-red-300 text-red-700 rounded-button text-sm font-medium hover:bg-red-50 focus:outline-none whitespace-nowrap">Delete</button>
                        </form>
                        <a href="#" id="edit-event-link"
                            class="px-4 py-2 bg-primary text-white rounded-button text-sm font-medium hover:bg-indigo-700 focus:outline-none whitespace-nowrap">Edit
                            Event</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Event Modal -->
    <div id="create-event-modal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 modal">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
                <h3 class="text-lg font-semibold text-gray-900">Create New Event</h3>
                <button class="text-gray-400 hover:text-gray-500 close-modal">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <i class="ri-close-line"></i>
                    </div>
                </button>
            </div>
            <form id="create-event-form" action="{{ route('events.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <!-- Event Title -->
                    <div>
                        <label for="create-title" class="block text-sm font-medium text-gray-700 mb-1">Event Title <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="create-title" name="title" required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm"
                            placeholder="Enter event title">
                    </div>

                    <!-- Event Type -->
                    <div>
                        <label for="create-event-type-id" class="block text-sm font-medium text-gray-700 mb-1">Event Type
                            <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select id="create-event-type-id" name="event_type_id" required
                                class="block w-full pr-8 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm appearance-none">
                                <option value="">Select event type</option>
                                @foreach ($eventTypes as $eventType)
                                    <option value="{{ $eventType->id }}" data-color="{{ $eventType->color }}">
                                        {{ $eventType->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <div class="w-4 h-4 flex items-center justify-center text-gray-400">
                                    <i class="ri-arrow-down-s-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="create-location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" id="create-location" name="location"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm"
                            placeholder="Enter location (optional)">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Start Date -->
                        <div>
                            <label for="create-start-date" class="block text-sm font-medium text-gray-700 mb-1">Start Date
                                <span class="text-red-500">*</span></label>
                            <input type="date" id="create-start-date" name="start_date" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                        </div>

                        <!-- Start Time -->
                        <div>
                            <label for="create-start-time" class="block text-sm font-medium text-gray-700 mb-1">Start Time
                                <span class="text-red-500">*</span></label>
                            <input type="time" id="create-start-time" name="start_time" value="09:00" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- End Date -->
                        <div>
                            <label for="create-end-date" class="block text-sm font-medium text-gray-700 mb-1">End Date
                                <span class="text-red-500">*</span></label>
                            <input type="date" id="create-end-date" name="end_date" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                        </div>

                        <!-- End Time -->
                        <div>
                            <label for="create-end-time" class="block text-sm font-medium text-gray-700 mb-1">End Time
                                <span class="text-red-500">*</span></label>
                            <input type="time" id="create-end-time" name="end_time" value="10:00" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                        </div>
                    </div>

                    <!-- All Day Event -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" id="create-all-day" name="all_day"
                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="create-all-day" class="font-medium text-gray-700">All Day Event</label>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="create-description"
                            class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="create-description" name="description" rows="3"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm"
                            placeholder="Enter event description (optional)"></textarea>
                    </div>

                    <!-- Attendees (hidden field with all users selected) -->
                    <div class="hidden">
                        <select id="create-attendees" name="attendees[]" multiple>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" selected>{{ $user->first_name }}
                                    {{ $user->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end p-6 border-t border-gray-200 space-x-3">
                    <button type="button"
                        class="px-4 py-2 border border-gray-300 rounded-button text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none whitespace-nowrap close-modal">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-primary text-white rounded-button text-sm font-medium hover:bg-indigo-700 focus:outline-none whitespace-nowrap">Create
                        Event</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Event Modal -->
    <div id="edit-event-modal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 modal">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
                <h3 class="text-lg font-semibold text-gray-900">Edit Event</h3>
                <button class="text-gray-400 hover:text-gray-500 close-modal">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <i class="ri-close-line"></i>
                    </div>
                </button>
            </div>
            <form id="edit-event-form" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-event-id" name="event_id">
                <div class="p-6 space-y-4">
                    <!-- Event Title -->
                    <div>
                        <label for="edit-title" class="block text-sm font-medium text-gray-700 mb-1">Event Title <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="edit-title" name="title" required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm"
                            placeholder="Enter event title">
                    </div>

                    <!-- Event Type -->
                    <div>
                        <label for="edit-event-type-id" class="block text-sm font-medium text-gray-700 mb-1">Event Type
                            <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select id="edit-event-type-id" name="event_type_id" required
                                class="block w-full pr-8 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm appearance-none">
                                <option value="">Select event type</option>
                                @foreach ($eventTypes as $eventType)
                                    <option value="{{ $eventType->id }}" data-color="{{ $eventType->color }}">
                                        {{ $eventType->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <div class="w-4 h-4 flex items-center justify-center text-gray-400">
                                    <i class="ri-arrow-down-s-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="edit-location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" id="edit-location" name="location"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm"
                            placeholder="Enter location (optional)">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Start Date -->
                        <div>
                            <label for="edit-start-date" class="block text-sm font-medium text-gray-700 mb-1">Start Date
                                <span class="text-red-500">*</span></label>
                            <input type="date" id="edit-start-date" name="start_date" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                        </div>

                        <!-- Start Time -->
                        <div>
                            <label for="edit-start-time" class="block text-sm font-medium text-gray-700 mb-1">Start Time
                                <span class="text-red-500">*</span></label>
                            <input type="time" id="edit-start-time" name="start_time" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- End Date -->
                        <div>
                            <label for="edit-end-date" class="block text-sm font-medium text-gray-700 mb-1">End Date <span
                                    class="text-red-500">*</span></label>
                            <input type="date" id="edit-end-date" name="end_date" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                        </div>

                        <!-- End Time -->
                        <div>
                            <label for="edit-end-time" class="block text-sm font-medium text-gray-700 mb-1">End Time <span
                                    class="text-red-500">*</span></label>
                            <input type="time" id="edit-end-time" name="end_time" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                        </div>
                    </div>

                    <!-- All Day Event -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" id="edit-all-day" name="all_day"
                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="edit-all-day" class="font-medium text-gray-700">All Day Event</label>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="edit-description"
                            class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="edit-description" name="description" rows="3"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm"
                            placeholder="Enter event description (optional)"></textarea>
                    </div>

                    <!-- Attendees (hidden field with all users selected) -->
                    <div class="hidden">
                        <select id="edit-attendees" name="attendees[]" multiple>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" selected>{{ $user->first_name }}
                                    {{ $user->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div
                    class="flex items-center justify-end p-6 border-t border-gray-200 space-x-3 sticky bottom-0 bg-white z-10">
                    <button type="button"
                        class="px-4 py-2 border border-gray-300 rounded-button text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none whitespace-nowrap close-modal">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-primary text-white rounded-button text-sm font-medium hover:bg-indigo-700 focus:outline-none whitespace-nowrap">Update
                        Event</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize FullCalendar
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                initialDate: '{{ $currentDate->format('Y-m-d') }}',
                headerToolbar: false, // We're using our custom header
                dayMaxEvents: 3,
                events: '{{ route('calendar.events') }}',
                height: '100%',
                eventTimeFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    meridiem: 'short'
                },
                eventClick: function(info) {
                    // Open the edit event popup instead of redirecting
                    showEditEventPopup(info.event);
                },
                dateClick: function(info) {
                    // Open a quick popup to add event instead of redirecting
                    showCreateEventPopup(info.dateStr);
                }
            });

            // Make calendar globally available
            window.calendar = calendar;

            calendar.render();

            // Function to update calendar layout
            window.updateCalendarLayout = function() {
                calendar.updateSize();
                const viewHarness = document.querySelector('.fc-view-harness');
                if (viewHarness) {
                    viewHarness.style.height = '100%';
                }
            };

            // Force calendar height after initial render
            setTimeout(() => {
                updateCalendarLayout();
            }, 200);

            // Resize calendar when window size changes
            window.addEventListener('resize', function() {
                setTimeout(updateCalendarLayout, 100);
            });

            // Resize calendar when view changes
            document.querySelectorAll('.view-option').forEach(option => {
                option.addEventListener('click', function() {
                    setTimeout(updateCalendarLayout, 100);
                });
            });

            // Role dropdown functionality
            const roleDropdownButton = document.getElementById('role-dropdown-button');
            const roleDropdownMenu = document.getElementById('role-dropdown-menu');
            const selectedRole = document.getElementById('selected-role');

            function toggleRoleDropdown() {
                roleDropdownMenu.classList.toggle('hidden');
            }

            function closeRoleDropdown() {
                roleDropdownMenu.classList.add('hidden');
            }

            roleDropdownButton.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleRoleDropdown();
            });

            document.addEventListener('click', (e) => {
                if (!roleDropdownButton.contains(e.target)) {
                    closeRoleDropdown();
                }
            });

            roleDropdownMenu.addEventListener('click', (e) => {
                if (e.target.hasAttribute('data-role')) {
                    const role = e.target.getAttribute('data-role');
                    selectedRole.textContent = role;
                    closeRoleDropdown();

                    // In a real app, this would trigger a role change and reload events
                    toastr.info(`View changed to ${role} role`);
                }
            });

            // Filter dropdown functionality
            const filterDropdownButton = document.getElementById('filter-dropdown-button');
            const filterDropdownMenu = document.getElementById('filter-dropdown-menu');

            function toggleFilterDropdown() {
                filterDropdownMenu.classList.toggle('hidden');
            }

            function closeFilterDropdown() {
                filterDropdownMenu.classList.add('hidden');
            }

            filterDropdownButton.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleFilterDropdown();
            });

            document.addEventListener('click', (e) => {
                if (!filterDropdownButton.contains(e.target) && !filterDropdownMenu.contains(e.target)) {
                    closeFilterDropdown();
                }
            });

            // Event type filtering
            const eventTypeFilters = document.querySelectorAll('.event-type-filter');

            eventTypeFilters.forEach(filter => {
                filter.addEventListener('change', () => {
                    // Get all checked event types
                    const checkedTypes = Array.from(eventTypeFilters)
                        .filter(checkbox => checkbox.checked)
                        .map(checkbox => parseInt(checkbox.value));

                    // Filter events
                    calendar.getEvents().forEach(event => {
                        if (checkedTypes.includes(event.extendedProps.eventTypeId)) {
                            event.setProp('display', 'auto');
                        } else {
                            event.setProp('display', 'none');
                        }
                    });
                });
            });

            // Calendar view options
            const viewOptions = document.querySelectorAll('.view-option');

            viewOptions.forEach(option => {
                option.addEventListener('click', () => {
                    // Remove active class from all options
                    viewOptions.forEach(opt => opt.classList.remove('active'));
                    // Add active class to clicked option
                    option.classList.add('active');

                    // Change calendar view
                    const viewType = option.getAttribute('data-view');
                    switch (viewType) {
                        case 'month':
                            calendar.changeView('dayGridMonth');
                            break;
                        case 'week':
                            calendar.changeView('timeGridWeek');
                            break;
                        case 'day':
                            calendar.changeView('timeGridDay');
                            break;
                        case 'list':
                            calendar.changeView('listWeek');
                            break;
                    }
                });
            });

            // Calendar navigation
            const prevMonthButton = document.getElementById('prev-month');
            const nextMonthButton = document.getElementById('next-month');
            const currentMonthDisplay = document.getElementById('current-month');
            const todayButton = document.getElementById('today-button');

            prevMonthButton.addEventListener('click', () => {
                calendar.prev();
                updateMonthDisplay();
            });

            nextMonthButton.addEventListener('click', () => {
                calendar.next();
                updateMonthDisplay();
            });

            todayButton.addEventListener('click', () => {
                calendar.today();
                updateMonthDisplay();
            });

            function updateMonthDisplay() {
                const date = calendar.getDate();
                currentMonthDisplay.textContent = moment(date).format('MMMM YYYY');

                // Also update today's events
                loadTodayEvents();
                loadUpcomingEvents();
            }

            // Event details modal functionality
            const eventDetailsModal = document.getElementById('event-details-modal');
            const closeDetailsModalButton = document.getElementById('close-details-modal');
            const eventTitleElement = document.getElementById('event-title');
            const eventInfoElement = document.getElementById('event-info');
            const deleteEventForm = document.getElementById('delete-event-form');
            const editEventLink = document.getElementById('edit-event-link');

            function showEventDetails(event) {
                // Update modal content
                eventTitleElement.textContent = event.title;

                // Format the event information
                const startTime = moment(event.start).format('h:mm A');
                const endTime = event.end ? moment(event.end).format('h:mm A') : '';
                const date = moment(event.start).format('dddd, MMMM D, YYYY');

                let eventHtml = `
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="w-10 h-10 flex items-center justify-center bg-${event.backgroundColor.replace('500', '100')} rounded-lg text-${event.backgroundColor} flex-shrink-0">
                            <i class="${event.extendedProps.icon || 'ri-calendar-event-line'}"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">${event.title}</p>
                            <p class="text-xs text-gray-500 mt-1">${date}</p>
                            <p class="text-xs text-gray-500 mt-1">${startTime} - ${endTime}</p>
                            <p class="text-xs text-gray-500 mt-1">${event.extendedProps.location || 'No location'}</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Description</h4>
                        <p class="text-sm text-gray-700">${event.extendedProps.description || 'No description'}</p>
                    </div>

                    <div class="mt-4">
                        <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Organizer</h4>
                        <p class="text-sm text-gray-700">${event.extendedProps.creator || 'Unknown'}</p>
                    </div>

                    <div class="mt-4">
                        <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Attendees</h4>
                        <p class="text-sm text-gray-700">${event.extendedProps.attendees} ${parseInt(event.extendedProps.attendees) === 1 ? 'person' : 'people'}</p>
                    </div>
                </div>
            `;

                eventInfoElement.innerHTML = eventHtml;

                // Set up the edit and delete links
                editEventLink.href = `/events/${event.id}/edit`;
                deleteEventForm.action = `/events/${event.id}`;

                // Show the modal
                eventDetailsModal.classList.remove('hidden');
            }

            // Close modal when clicking X button
            closeDetailsModalButton.addEventListener('click', () => {
                eventDetailsModal.classList.add('hidden');
            });

            // Close modal when clicking outside
            eventDetailsModal.addEventListener('click', (e) => {
                if (e.target === eventDetailsModal) {
                    eventDetailsModal.classList.add('hidden');
                }
            });

            // Load today's events for the sidebar
            function loadTodayEvents() {
                const today = calendar.getDate();
                const todayStart = moment(today).startOf('day').toISOString();
                const todayEnd = moment(today).endOf('day').toISOString();

                fetch(`{{ route('calendar.events') }}?start=${todayStart}&end=${todayEnd}`)
                    .then(response => response.json())
                    .then(events => {
                        const todayEventsContainer = document.getElementById('today-events');

                        if (events.length === 0) {
                            todayEventsContainer.innerHTML = `
                            <div class="flex items-center justify-center h-32">
                                <p class="text-gray-400">No events scheduled for today</p>
                            </div>
                        `;
                            return;
                        }

                        // Sort events by time
                        events.sort((a, b) => new Date(a.start) - new Date(b.start));

                        let eventsHtml = '';

                        events.forEach(event => {
                            const time = moment(event.start).format('h:mm A');

                            eventsHtml += `
                            <div class="event-card p-4 border-l-${event.color}" style="border-color: var(--${event.color.replace('500', '700')}, #4F46E5);">
                                <div class="flex items-start">
                                    <div class="w-10 h-10 flex items-center justify-center bg-${event.color.replace('500', '100')} rounded-lg text-${event.color} flex-shrink-0">
                                        <i class="${event.icon || 'ri-calendar-event-line'}"></i>
                                    </div>
                                    <div class="ml-3 flex-grow">
                                        <p class="text-sm font-medium text-gray-900">${event.title}</p>
                                        <p class="text-xs text-gray-500 mt-1">${time} • ${event.location || 'No location'}</p>
                                        <div class="mt-3 flex space-x-2">
                                            <a href="/events/${event.id}/edit" class="text-xs text-gray-600 hover:text-primary flex items-center">
                                                <div class="w-3 h-3 mr-1 flex items-center justify-center">
                                                    <i class="ri-edit-line"></i>
                                                </div>
                                                Edit
                                            </a>
                                            <a href="/events/${event.id}" class="text-xs text-gray-600 hover:text-primary flex items-center">
                                                <div class="w-3 h-3 mr-1 flex items-center justify-center">
                                                    <i class="ri-information-line"></i>
                                                </div>
                                                Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        });

                        todayEventsContainer.innerHTML = eventsHtml;

                        // Add event listeners to detail buttons
                        document.querySelectorAll('.event-details-btn').forEach(btn => {
                            btn.addEventListener('click', () => {
                                const eventId = btn.getAttribute('data-event-id');
                                const event = calendar.getEventById(eventId);
                                if (event) {
                                    showEventDetails(event);
                                }
                            });
                        });
                    })
                    .catch(error => {
                        console.error('Error loading today\'s events:', error);
                        document.getElementById('today-events').innerHTML = `
                        <div class="flex items-center justify-center h-32">
                            <p class="text-red-500">Error loading events</p>
                        </div>
                    `;
                    });
            }

            // Load upcoming events for the sidebar
            function loadUpcomingEvents() {
                const tomorrow = moment().add(1, 'day').startOf('day').toISOString();
                const twoWeeksLater = moment().add(14, 'days').endOf('day').toISOString();

                fetch(`{{ route('calendar.events') }}?start=${tomorrow}&end=${twoWeeksLater}`)
                    .then(response => response.json())
                    .then(events => {
                        const upcomingEventsContainer = document.getElementById('upcoming-events');

                        if (events.length === 0) {
                            upcomingEventsContainer.innerHTML = `
                            <div class="flex items-center justify-center h-32">
                                <p class="text-gray-400">No upcoming events in the next two weeks</p>
                            </div>
                        `;
                            return;
                        }

                        // Sort events by date
                        events.sort((a, b) => new Date(a.start) - new Date(b.start));

                        // Take only the first 15 events to avoid overwhelming the list
                        const upcomingEvents = events.slice(0, 15);

                        let eventsHtml = '';

                        upcomingEvents.forEach(event => {
                            const date = moment(event.start).format('dddd, MMMM D');
                            const time = moment(event.start).format('h:mm A');

                            eventsHtml += `
                            <div class="event-card p-4 border-l-${event.color}" style="border-color: var(--${event.color.replace('500', '700')}, #4F46E5);">
                                <div class="flex items-start">
                                    <div class="w-10 h-10 flex items-center justify-center bg-${event.color.replace('500', '100')} rounded-lg text-${event.color} flex-shrink-0">
                                        <i class="${event.icon || 'ri-calendar-event-line'}"></i>
                                    </div>
                                    <div class="ml-3 flex-grow">
                                        <p class="text-sm font-medium text-gray-900">${event.title}</p>
                                        <p class="text-xs text-gray-500 mt-1">${date} • ${time}</p>
                                        <p class="text-xs text-gray-500 mt-1">${event.location || ''}</p>
                                        <div class="mt-3 flex space-x-2">
                                            <a href="/events/${event.id}" class="text-xs text-primary font-medium hover:text-indigo-700">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        });

                        upcomingEventsContainer.innerHTML = eventsHtml + '<div class="h-10"></div>';
                    })
                    .catch(error => {
                        console.error('Error loading upcoming events:', error);
                        document.getElementById('upcoming-events').innerHTML = `
                        <div class="flex items-center justify-center h-32">
                            <p class="text-red-500">Error loading events</p>
                        </div>
                    `;
                    });
            }

            // Initial load of today's and upcoming events
            loadTodayEvents();
            loadUpcomingEvents();

            // Delete event confirmation
            deleteEventForm.addEventListener('submit', function(e) {
                e.preventDefault();

                if (confirm('Are you sure you want to delete this event? This action cannot be undone.')) {
                    this.submit();
                }
            });

            // Handle event creation success message
            @if (session('success'))
                toastr.success('{{ session('success') }}');
            @endif

            // Create Event Popup Functions
            function showCreateEventPopup(dateStr) {
                // Set the date in the create event form
                document.getElementById('create-start-date').value = dateStr;
                document.getElementById('create-end-date').value = dateStr;

                // Handle form submission
                const createForm = document.getElementById('create-event-form');
                createForm.onsubmit = function(e) {
                    e.preventDefault();

                    // Create FormData object
                    const formData = new FormData(createForm);

                    // Send AJAX request
                    fetch(createForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Close the modal
                                document.getElementById('create-event-modal').classList.add('hidden');

                                // Refresh the calendar events
                                calendar.refetchEvents();

                                // Also refresh the sidebar event lists
                                loadTodayEvents();
                                loadUpcomingEvents();

                                // Show success message
                                toastr.success('Event created successfully!');

                                // Clear the form
                                createForm.reset();
                            }
                        })
                        .catch(error => {
                            console.error('Error creating event:', error);
                            toastr.error('Failed to create event. Please try again.');
                        });
                };

                // Show the create event modal
                document.getElementById('create-event-modal').classList.remove('hidden');
            }

            // Show Edit Event Popup
            function showEditEventPopup(event) {
                // Set the event details in the edit form
                document.getElementById('edit-event-id').value = event.id;
                document.getElementById('edit-title').value = event.title;
                document.getElementById('edit-event-type-id').value = event.extendedProps.eventTypeId || '';
                document.getElementById('edit-location').value = event.extendedProps.location || '';
                document.getElementById('edit-description').value = event.extendedProps.description || '';
                document.getElementById('edit-all-day').checked = event.allDay;

                // Format dates and times
                const startDate = moment(event.start).format('YYYY-MM-DD');
                const startTime = moment(event.start).format('HH:mm');
                const endDate = event.end ? moment(event.end).format('YYYY-MM-DD') : startDate;
                const endTime = event.end ? moment(event.end).format('HH:mm') : moment(event.start).add(1, 'hour')
                    .format('HH:mm');

                document.getElementById('edit-start-date').value = startDate;
                document.getElementById('edit-start-time').value = startTime;
                document.getElementById('edit-end-date').value = endDate;
                document.getElementById('edit-end-time').value = endTime;

                // Set the form action to the correct URL
                const editForm = document.getElementById('edit-event-form');
                editForm.action = `/events/${event.id}`;

                // Handle form submission
                editForm.onsubmit = function(e) {
                    e.preventDefault();

                    // Create FormData object
                    const formData = new FormData(editForm);

                    // Send AJAX request
                    fetch(editForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Close the modal
                                document.getElementById('edit-event-modal').classList.add('hidden');

                                // Refresh the calendar events
                                calendar.refetchEvents();

                                // Also refresh the sidebar event lists
                                loadTodayEvents();
                                loadUpcomingEvents();

                                // Show success message
                                toastr.success('Event updated successfully!');
                            }
                        })
                        .catch(error => {
                            console.error('Error updating event:', error);
                            toastr.error('Failed to update event. Please try again.');
                        });
                };

                // Show the edit event modal
                document.getElementById('edit-event-modal').classList.remove('hidden');
            }

            // Add event listeners to close quick popup modals
            document.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', function() {
                    this.closest('.modal').classList.add('hidden');
                });
            });

            // Close modals when clicking outside
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.classList.add('hidden');
                    }
                });
            });
        });
    </script>

    <!-- Separate sidebar toggle functionality -->
    <script src="{{ asset('js/calendar-sidebar.js') }}"></script>
@endpush
