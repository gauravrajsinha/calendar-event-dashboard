/**
 * Calendar Sidebar Toggle Functionality
 * Handles showing and hiding the event sidebar for the calendar
 */

// Execute when DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get sidebar elements
    const toggleSidebarButton = document.getElementById('toggle-sidebar');
    const showSidebarButton = document.getElementById('show-sidebar');
    const eventSidebar = document.querySelector('.event-sidebar');
    const calendarContainer = document.querySelector('.calendar-container');
    const calendarMainGrid = document.querySelector('.main-calendar-grid');

    // Function to hide sidebar
    function hideSidebar() {
        console.log('Hiding sidebar');
        eventSidebar.classList.add('hidden');
        calendarMainGrid.classList.add('full-width-calendar');
        showSidebarButton.classList.remove('hidden');
        document.documentElement.style.setProperty('--sidebar-width', '0px');

        // Force calendar to re-render
        if (window.calendar) {
            setTimeout(() => {
                window.calendar.updateSize();
            }, 100);
        }
    }

    // Function to show sidebar
    function showSidebar() {
        console.log('Showing sidebar');
        eventSidebar.classList.remove('hidden');
        calendarMainGrid.classList.remove('full-width-calendar');
        showSidebarButton.classList.add('hidden');
        document.documentElement.style.setProperty('--sidebar-width', '16rem');

        // Force calendar to re-render
        if (window.calendar) {
            setTimeout(() => {
                window.calendar.updateSize();
            }, 100);
        }
    }

    // Add event listeners to buttons
    if (toggleSidebarButton) {
        toggleSidebarButton.addEventListener('click', function(e) {
            e.preventDefault();
            hideSidebar();
        });
    }

    if (showSidebarButton) {
        showSidebarButton.addEventListener('click', function(e) {
            e.preventDefault();
            showSidebar();
        });
    }

    // Initialize sidebar visibility
    function initSidebar() {
        // On page load, make sure sidebar is visible on desktop
        if (window.innerWidth >= 1024) {
            eventSidebar.classList.remove('hidden');
            showSidebarButton.classList.add('hidden');
            calendarMainGrid.classList.remove('full-width-calendar');
        } else {
            // On mobile, start with sidebar hidden
            hideSidebar();
        }
    }

    // Run initialization after a brief delay
    setTimeout(initSidebar, 200);

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth < 1024) {
            if (!eventSidebar.classList.contains('hidden')) {
                hideSidebar();
            }
        }
    });
});
