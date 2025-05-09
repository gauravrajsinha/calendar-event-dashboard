/**
 * Calendar Sidebar Toggle Functionality
 * Handles showing and hiding the event sidebar for the calendar
 */

// Execute when DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Calendar sidebar script loaded');

    // Get sidebar elements
    const toggleSidebarButton = document.getElementById('toggle-sidebar');
    const eventSidebar = document.querySelector('.event-sidebar');
    const calendarContainer = document.querySelector('.calendar-flex-container');
    const calendarMainGrid = document.querySelector('.main-calendar-grid');

    console.log('Toggle button found:', !!toggleSidebarButton);
    console.log('Sidebar found:', !!eventSidebar);
    console.log('Calendar container found:', !!calendarContainer);
    console.log('Calendar grid found:', !!calendarMainGrid);

    // Function to toggle sidebar
    function toggleSidebar(e) {
        if (e) e.preventDefault();

        if (!eventSidebar || !toggleSidebarButton) {
            console.error('Required elements not found for toggling sidebar');
            return;
        }

        const isSidebarHidden = eventSidebar.classList.contains('hidden');
        console.log('Toggle sidebar - current state:', isSidebarHidden ? 'hidden' : 'visible');

        if (isSidebarHidden) {
            // Show sidebar
            eventSidebar.classList.remove('hidden');
            calendarMainGrid.classList.remove('sidebar-hidden');
            document.documentElement.style.setProperty('--sidebar-width', '16rem');
        } else {
            // Hide sidebar
            eventSidebar.classList.add('hidden');
            calendarMainGrid.classList.add('sidebar-hidden');
            document.documentElement.style.setProperty('--sidebar-width', '0px');
        }

        // Force calendar to re-render
        if (window.calendar) {
            setTimeout(() => {
                window.calendar.updateSize();
            }, 100);
        }
    }

    // Add event listener to toggle button
    if (toggleSidebarButton) {
        console.log('Adding click event to toggle sidebar button');
        toggleSidebarButton.addEventListener('click', toggleSidebar);
    }

    // Initialize sidebar visibility
    function initSidebar() {
        console.log('Initializing sidebar, window width:', window.innerWidth);
        // On page load, make sure sidebar is visible on desktop
        if (window.innerWidth >= 1024) {
            if (eventSidebar) eventSidebar.classList.remove('hidden');
            if (calendarMainGrid) calendarMainGrid.classList.remove('sidebar-hidden');
            document.documentElement.style.setProperty('--sidebar-width', '16rem');
        } else {
            // On mobile, start with sidebar hidden
            if (eventSidebar) eventSidebar.classList.add('hidden');
            if (calendarMainGrid) calendarMainGrid.classList.add('sidebar-hidden');
            document.documentElement.style.setProperty('--sidebar-width', '0px');
        }

        // Force calendar to re-render
        if (window.calendar) {
            setTimeout(() => {
                window.calendar.updateSize();
            }, 200);
        }
    }

    // Run initialization after a brief delay
    setTimeout(initSidebar, 200);

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth < 1024) {
            if (eventSidebar && !eventSidebar.classList.contains('hidden')) {
                if (eventSidebar) eventSidebar.classList.add('hidden');
                if (calendarMainGrid) calendarMainGrid.classList.add('sidebar-hidden');
                document.documentElement.style.setProperty('--sidebar-width', '0px');

                // Force calendar to re-render
                if (window.calendar) {
                    setTimeout(() => {
                        window.calendar.updateSize();
                    }, 100);
                }
            }
        }
    });

    // Add simple direct click handler as a backup
    document.addEventListener('click', function(e) {
        if (e.target && (e.target.id === 'toggle-sidebar' || e.target.closest('#toggle-sidebar'))) {
            console.log('Direct click on toggle-sidebar detected');
            toggleSidebar(e);
        }
    });
});
