/**
 * DARK MODE CONTROLLER
 * Handles dark mode toggle and persistence
 */

// Initialize dark mode on page load
document.addEventListener('DOMContentLoaded', function() {
    // Check localStorage for saved preference
    const darkMode = localStorage.getItem('darkMode');
    const bodyHasClass = document.body.classList.contains('dark-mode');
    
    // LOGIC FIX: Explicitly handle 'disabled' to prevent falling back to 'enabled'
    if (darkMode === 'enabled') {
        enableDarkMode();
    } else if (darkMode === 'disabled') {
        // User explicitly turned it off, so force remove class even if server added it
        disableDarkMode();
    } else if (darkMode === null && bodyHasClass) {
        // FIRST VISIT or No Preference: If server sent it, sync it to 'enabled'
        localStorage.setItem('darkMode', 'enabled');
        updateToggleIcon(true);
    } else {
        // Fallback
        disableDarkMode();
    }
    
    // Add event listener to toggle button
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function() {
            // Re-read current state from storage to be sure
            const currentMode = localStorage.getItem('darkMode');
            
            if (currentMode !== 'enabled') {
                enableDarkMode();
            } else {
                disableDarkMode();
            }
        });
    }
});

// Enable dark mode
function enableDarkMode() {
    document.body.classList.add('dark-mode');
    localStorage.setItem('darkMode', 'enabled');
    
    // Update toggle icon
    updateToggleIcon(true);
}

// Disable dark mode
function disableDarkMode() {
    document.body.classList.remove('dark-mode');
    localStorage.setItem('darkMode', 'disabled'); // SET EXPLICIT 'disabled'
    
    // Update toggle icon
    updateToggleIcon(false);
}

// Update toggle button icon
function updateToggleIcon(isDark) {
    const toggleIcon = document.querySelector('#darkModeToggle i');
    if (toggleIcon) {
        if (isDark) {
            toggleIcon.classList.remove('fa-moon');
            toggleIcon.classList.add('fa-sun');
        } else {
            toggleIcon.classList.remove('fa-sun');
            toggleIcon.classList.add('fa-moon');
        }
    }
}
