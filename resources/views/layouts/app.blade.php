<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HeritageConnect Calendar') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4F46E5',
                        secondary: '#10B981',
                        golden: '#D4AF37',
                        /* Golden color for Heritage branding */
                        gray: {
                            50: '#F9FAFB',
                            100: '#F3F4F6',
                            200: '#E5E7EB',
                            300: '#D1D5DB',
                            400: '#9CA3AF',
                            500: '#6B7280',
                            600: '#4B5563',
                            700: '#374151',
                            800: '#1F2937',
                            900: '#111827'
                        },
                        blue: {
                            50: '#EFF6FF',
                            100: '#DBEAFE',
                            500: '#3B82F6',
                            700: '#1D4ED8'
                        },
                        indigo: {
                            50: '#EEF2FF',
                            100: '#E0E7FF',
                            500: '#6366F1',
                            700: '#4338CA',
                            900: '#312E81'
                        },
                        red: {
                            50: '#FEF2F2',
                            100: '#FEE2E2',
                            500: '#EF4444',
                            700: '#B91C1C'
                        },
                        green: {
                            50: '#ECFDF5',
                            100: '#D1FAE5',
                            500: '#10B981',
                            700: '#047857'
                        },
                        yellow: {
                            50: '#FFFBEB',
                            100: '#FEF3C7',
                            500: '#F59E0B',
                            700: '#B45309'
                        },
                        purple: {
                            50: '#F5F3FF',
                            100: '#EDE9FE',
                            500: '#8B5CF6',
                            700: '#6D28D9'
                        },
                        pink: {
                            50: '#FDF2F8',
                            100: '#FCE7F3',
                            500: '#EC4899',
                            700: '#BE185D'
                        }
                    },
                    borderRadius: {
                        'none': '0px',
                        'sm': '4px',
                        DEFAULT: '8px',
                        'md': '12px',
                        'lg': '16px',
                        'xl': '20px',
                        '2xl': '24px',
                        '3xl': '32px',
                        'full': '9999px',
                        'button': '8px',
                    }
                },
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                }
            }
        }
    </script>

    <!-- Styles -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F9FAFB;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .sidebar-link:hover {
            background-color: rgba(79, 70, 229, 0.05);
        }

        .sidebar-link.active {
            background-color: rgba(79, 70, 229, 0.1);
            color: #4F46E5;
            font-weight: 500;
        }

        .fc-daygrid-day {
            transition: background-color 0.2s;
        }

        .fc-daygrid-day:hover {
            background-color: rgba(79, 70, 229, 0.05);
            cursor: pointer;
        }

        .fc-daygrid-day.fc-day-today {
            background-color: rgba(79, 70, 229, 0.1) !important;
        }

        .fc-event {
            cursor: pointer;
            border-radius: 8px;
            border: none;
            padding: 4px 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .fc-event:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .view-option {
            cursor: pointer;
        }

        .view-option.active {
            background-color: #4F46E5;
            color: white;
        }

        #calendar .fc-toolbar-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        #calendar .fc-button {
            border-radius: 0.5rem;
            box-shadow: none;
            border: 1px solid #E5E7EB;
            background-color: white;
            color: #374151;
            transition: all 0.2s ease;
        }

        #calendar .fc-button:hover {
            background-color: #F9FAFB;
            color: #111827;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        #calendar .fc-button-primary:not(:disabled).fc-button-active,
        #calendar .fc-button-primary:not(:disabled):active {
            background-color: #4F46E5;
            border-color: #4F46E5;
            color: white;
            box-shadow: 0 2px 4px rgba(79, 70, 229, 0.3);
        }

        /* Custom calendar styling */
        .fc-theme-standard td,
        .fc-theme-standard th {
            border-color: rgba(203, 213, 225, 0.4);
        }

        .fc-col-header-cell {
            background-color: #f8fafc;
            font-weight: 600;
            padding: 8px 0;
        }

        .fc-scrollgrid {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid rgba(203, 213, 225, 0.5) !important;
        }

        /* Hide scrollbars for calendar */
        .fc-scroller {
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE and Edge */
        }

        .fc-scroller::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari, Opera */
        }

        /* Toast customization */
        .toast-success {
            background-color: #10B981;
        }

        .toast-error {
            background-color: #EF4444;
        }
    </style>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<body class="antialiased min-h-screen flex flex-col">
    <div class="flex flex-1 flex-col md:flex-row">
        <!-- Sidebar (for tablet and up) -->
        <div class="hidden md:flex md:w-64 md:flex-col bg-white border-r border-gray-200">
            <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                <div class="flex items-center px-6">
                    @include('components.app-logo')
                </div>
                <nav class="mt-6 flex-1 px-4 space-y-1">
                    <a href="{{ route('dashboard') }}"
                        class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <div class="w-6 h-6 flex items-center justify-center mr-3">
                            <i class="ri-dashboard-line"></i>
                        </div>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('calendar.index') }}"
                        class="sidebar-link {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                        <div class="w-6 h-6 flex items-center justify-center mr-3">
                            <i class="ri-calendar-line"></i>
                        </div>
                        <span>Calendar</span>
                    </a>
                    <a href="{{ route('events.create') }}"
                        class="sidebar-link {{ request()->routeIs('events.create') ? 'active' : '' }}">
                        <div class="w-6 h-6 flex items-center justify-center mr-3">
                            <i class="ri-add-circle-line"></i>
                        </div>
                        <span>Create Event</span>
                    </a>
                </nav>
                <div class="px-4 pt-6 pb-4 border-t border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full"
                                src="https://ui-avatars.com/api/?name=Reshma+Roychudhari&background=4F46E5&color=fff"
                                alt="User avatar">
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Ms. Reshma Roychoudhari </p>
                            <p class="text-xs text-gray-500">Lecturer (CSE)</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('logout') }}"
                            class="text-sm text-gray-600 hover:text-primary flex items-center">
                            <div class="w-5 h-5 flex items-center justify-center mr-2">
                                <i class="ri-logout-box-line"></i>
                            </div>
                            Log out
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile header -->
        <div class="md:hidden bg-white border-b border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    @include('components.app-logo')
                </div>
                <button type="button" id="mobile-menu-button" class="text-gray-400 hover:text-gray-500">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <i class="ri-menu-line"></i>
                    </div>
                </button>
            </div>

            <!-- Mobile menu (hidden by default) -->
            <div id="mobile-menu" class="hidden mt-4 space-y-2">
                <a href="{{ route('dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <div class="w-6 h-6 flex items-center justify-center mr-3">
                        <i class="ri-dashboard-line"></i>
                    </div>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('calendar.index') }}"
                    class="sidebar-link {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                    <div class="w-6 h-6 flex items-center justify-center mr-3">
                        <i class="ri-calendar-line"></i>
                    </div>
                    <span>Calendar</span>
                </a>
                <a href="{{ route('events.create') }}"
                    class="sidebar-link {{ request()->routeIs('events.create') ? 'active' : '' }}">
                    <div class="w-6 h-6 flex items-center justify-center mr-3">
                        <i class="ri-add-circle-line"></i>
                    </div>
                    <span>Create Event</span>
                </a>
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img class="h-8 w-8 rounded-full"
                                src="https://ui-avatars.com/api/?name=Reshma+Roychudhari&background=4F46E5&color=fff"
                                alt="User avatar">
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Ms. Reshma Roychoudhari </p>
                            <p class="text-xs text-gray-500">Lecturer (CSE)</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('logout') }}"
                            class="text-sm text-gray-600 hover:text-primary flex items-center">
                            <div class="w-5 h-5 flex items-center justify-center mr-2">
                                <i class="ri-logout-box-line"></i>
                            </div>
                            Log out
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- <main class="flex-1 flex flex-col overflow-hidden"> -->
            <main class="flex-1 flex flex-col">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Toastr configuration
            toastr.options = {
                "closeButton": true,
                "positionClass": "toast-top-right",
                "progressBar": true,
                "timeOut": "3000",
            };
        });
    </script>

    @stack('scripts')
</body>

</html>
