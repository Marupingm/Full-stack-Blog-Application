// Dark mode functionality
document.addEventListener('DOMContentLoaded', function() {
    const darkModeToggle = document.getElementById('checkbox');
    const body = document.body;
    
    // Check for saved dark mode preference
    const darkMode = localStorage.getItem('darkMode');
    
    // Apply dark mode if previously saved
    if (darkMode === 'enabled') {
        body.classList.add('dark-theme');
        darkModeToggle.checked = true;
    }
    
    // Toggle dark mode
    darkModeToggle.addEventListener('change', function() {
        if (this.checked) {
            body.classList.add('dark-theme');
            localStorage.setItem('darkMode', 'enabled');
        } else {
            body.classList.remove('dark-theme');
            localStorage.setItem('darkMode', null);
        }
    });
    
    // Add smooth transition for all elements when switching modes
    const style = document.createElement('style');
    style.textContent = `
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease !important;
        }
    `;
    document.head.appendChild(style);
}); //  
