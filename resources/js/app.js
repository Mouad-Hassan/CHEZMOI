import './bootstrap';

// Vanilla JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    // Navigation scroll effect
    const nav = document.getElementById('main-nav');
    if (nav) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 20) {
                nav.classList.add('shadow-md');
                nav.classList.remove('shadow-sm');
            } else {
                nav.classList.remove('shadow-md');
                nav.classList.add('shadow-sm');
            }
        });
    }

    // Mobile menu toggle
    const hamburger = document.getElementById('nav-hamburger');
    const mobileMenu = document.getElementById('mobile-menu');
    const hamburgerIcon = document.getElementById('hamburger-icon');
    const closeIcon = document.getElementById('close-icon');

    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            hamburgerIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        });
    }

    // Dropdown toggle
    const dropdownToggle = document.getElementById('user-menu-button');
    const dropdownMenu = document.getElementById('user-dropdown');
    const dropdownArrow = document.getElementById('dropdown-arrow');

    if (dropdownToggle && dropdownMenu) {
        dropdownToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('hidden');
            dropdownArrow.classList.toggle('rotate-180');
        });

        document.addEventListener('click', function() {
            dropdownMenu.classList.add('hidden');
            dropdownArrow.classList.remove('rotate-180');
        });
    }
});
