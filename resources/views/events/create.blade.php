@extends('layouts.app')

@section('content')
    <div class="bg-white px-4 sm:px-6 lg:px-8 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Add New Event</h1>
                <p class="text-sm text-gray-500">Create a new event on your calendar</p>
            </div>
            <div>
                <a href="{{ route('calendar.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-button bg-white text-gray-700 hover:bg-gray-50 focus:outline-none">
                    <i class="ri-arrow-left-line mr-2"></i>
                    Back to Calendar
                </a>
            </div>
        </div>
    </div>

    <div class="p-4 sm:p-6 lg:p-8">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <form action="{{ route('events.store') }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-6">
                    <!-- Event Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Event Title <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm"
                            placeholder="Enter event title">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Event Type -->
                        <div>
                            <label for="event_type_id" class="block text-sm font-medium text-gray-700 mb-1">Event Type <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <select id="event_type_id" name="event_type_id" required
                                    class="block w-full pr-8 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm appearance-none">
                                    <option value="">Select event type</option>
                                    @foreach ($eventTypes as $eventType)
                                        <option value="{{ $eventType->id }}" data-color="{{ $eventType->color }}"
                                            {{ old('event_type_id') == $eventType->id ? 'selected' : '' }}>
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
                            @error('event_type_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <input type="text" id="location" name="location" value="{{ old('location') }}"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm"
                                placeholder="Enter location (optional)">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Start Date/Time -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date <span
                                    class="text-red-500">*</span></label>
                            <input type="date" id="start_date" name="start_date"
                                value="{{ old('start_date', request('date', now()->format('Y-m-d'))) }}" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Start Time <span
                                    class="text-red-500">*</span></label>
                            <input type="time" id="start_time" name="start_time"
                                value="{{ old('start_time', '09:00') }}" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                            @error('start_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- End Date/Time -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date <span
                                    class="text-red-500">*</span></label>
                            <input type="date" id="end_date" name="end_date"
                                value="{{ old('end_date', request('date', now()->format('Y-m-d'))) }}" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">End Time <span
                                    class="text-red-500">*</span></label>
                            <input type="time" id="end_time" name="end_time" value="{{ old('end_time', '10:00') }}"
                                required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                            @error('end_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- All Day Event -->
                        <div class="flex items-center">
                            <input type="checkbox" id="all_day" name="all_day" value="1"
                                {{ old('all_day') ? 'checked' : '' }} class="mr-2">
                            <label for="all_day" class="text-sm text-gray-700">All day event</label>
                        </div>

                        <!-- Recurring Event -->
                        <div class="flex items-center">
                            <input type="checkbox" id="is_recurring" name="is_recurring" value="1"
                                {{ old('is_recurring') ? 'checked' : '' }} class="mr-2">
                            <label for="is_recurring" class="text-sm text-gray-700">Recurring event</label>
                        </div>
                    </div>

                    <!-- Recurrence Pattern (hidden by default, shown when recurring is checked) -->
                    <div id="recurrence_options" class="{{ old('is_recurring') ? '' : 'hidden' }}">
                        <label for="recurrence_pattern" class="block text-sm font-medium text-gray-700 mb-1">Recurrence
                            Pattern</label>
                        <div class="relative">
                            <select id="recurrence_pattern" name="recurrence_pattern"
                                class="block w-full pr-8 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm appearance-none">
                                <option value="daily" {{ old('recurrence_pattern') == 'daily' ? 'selected' : '' }}>Daily
                                </option>
                                <option value="weekly" {{ old('recurrence_pattern') == 'weekly' ? 'selected' : '' }}>
                                    Weekly</option>
                                <option value="biweekly" {{ old('recurrence_pattern') == 'biweekly' ? 'selected' : '' }}>
                                    Bi-weekly</option>
                                <option value="monthly" {{ old('recurrence_pattern') == 'monthly' ? 'selected' : '' }}>
                                    Monthly</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <div class="w-4 h-4 flex items-center justify-center text-gray-400">
                                    <i class="ri-arrow-down-s-line"></i>
                                </div>
                            </div>
                        </div>
                        @error('recurrence_pattern')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="3"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm"
                            placeholder="Enter event description (optional)">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Attendees (multi-select) -->
                    <div>
                        <label for="attendees" class="block text-sm font-medium text-gray-700 mb-1">Attendees</label>
                        <div class="relative">
                            <select id="attendees" name="attendees[]" multiple
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm"
                                size="4">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ in_array($user->id, old('attendees', [])) ? 'selected' : '' }}>
                                        {{ $user->full_name }} ({{ $user->role->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Hold Ctrl (or Cmd) to select multiple attendees</p>
                        @error('attendees')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Buttons -->
                <div class="mt-8 flex items-center justify-end space-x-3">
                    <a href="{{ route('calendar.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded-button text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none whitespace-nowrap">Cancel</a>
                    <button type="submit"
                        class="px-4 py-2 bg-primary text-white rounded-button text-sm font-medium hover:bg-indigo-700 focus:outline-none whitespace-nowrap">Save
                        Event</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show/hide recurrence options when the recurring checkbox is clicked
            const isRecurringCheckbox = document.getElementById('is_recurring');
            const recurrenceOptions = document.getElementById('recurrence_options');

            isRecurringCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    recurrenceOptions.classList.remove('hidden');
                } else {
                    recurrenceOptions.classList.add('hidden');
                }
            });

            // Handle all day event checkbox
            const allDayCheckbox = document.getElementById('all_day');
            const startTimeInput = document.getElementById('start_time');
            const endTimeInput = document.getElementById('end_time');

            allDayCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    startTimeInput.value = '00:00';
                    endTimeInput.value = '23:59';
                    startTimeInput.disabled = true;
                    endTimeInput.disabled = true;
                } else {
                    startTimeInput.value = '09:00';
                    endTimeInput.value = '10:00';
                    startTimeInput.disabled = false;
                    endTimeInput.disabled = false;
                }
            });

            // Make sure end date is not before start date
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            startDateInput.addEventListener('change', function() {
                if (endDateInput.value < startDateInput.value) {
                    endDateInput.value = startDateInput.value;
                }
            });

            // Visual cue for event type selection
            const eventTypeSelect = document.getElementById('event_type_id');

            eventTypeSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const color = selectedOption.getAttribute('data-color');

                if (color) {
                    this.style.borderColor = color;
                    this.style.borderWidth = '2px';
                } else {
                    this.style.borderColor = '';
                    this.style.borderWidth = '';
                }
            });

            // Trigger initial styling if a value is pre-selected
            if (eventTypeSelect.value) {
                eventTypeSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endpush
