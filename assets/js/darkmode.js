document.addEventListener('DOMContentLoaded', function() {
    const darkModeToggle = document.getElementById('darkModeToggle');
    const htmlElement = document.documentElement;
    const toggleIcon = darkModeToggle.querySelector('i');
    const toggleText = darkModeToggle.querySelector('span');
    
    // Check for saved theme in local storage
    const savedTheme = localStorage.getItem('theme');
    const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
    
    if (savedTheme === 'dark' || (!savedTheme && prefersDarkScheme.matches)) {
        enableDarkMode();
    } else {
        enableLightMode();
    }
    
    darkModeToggle.addEventListener('click', () => {
        if (htmlElement.getAttribute('data-theme') === 'light') {
            enableDarkMode();
        } else {
            enableLightMode();
        }
    });
    
    function enableDarkMode() {
        htmlElement.setAttribute('data-theme', 'dark');
        toggleIcon.classList.remove('fa-sun');
        toggleIcon.classList.add('fa-moon');
        toggleText.textContent = 'Dark Mode';
        localStorage.setItem('theme', 'dark');
        
        addTransitionClass();
    }
    
    function enableLightMode() {
        htmlElement.setAttribute('data-theme', 'light');
        toggleIcon.classList.remove('fa-moon');
        toggleIcon.classList.add('fa-sun');
        toggleText.textContent = 'Light Mode';
        localStorage.setItem('theme', 'light');
        
        addTransitionClass();
    }
    
    function addTransitionClass() {
        const elements = document.querySelectorAll('body, .card, .table, .modal, .navbar, .form-control, .form-select');
        elements.forEach(el => {
            el.classList.add('theme-transition');
        });
    }
    
    // Listen Device theme changes
    prefersDarkScheme.addEventListener('change', (e) => {
        if (!localStorage.getItem('theme')) {
            if (e.matches) {
                enableDarkMode();
            } else {
                enableLightMode();
            }
        }
    });
}); 