// public/js/app.js

document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu functionality could be added here in the future
    
    // Dropdown toggle functionality
    const dropdownToggle = document.querySelectorAll('.dropdown');
    
    // Animation for content sections
    const contentSections = document.querySelectorAll('.content-wrap');
    
    // Check if IntersectionObserver is supported
    if ('IntersectionObserver' in window && 
        !('animation-timeline' in document.documentElement.style)) {
        
        // If View Timeline API is not supported, use IntersectionObserver as fallback
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'appear 0.8s forwards';
                }
            });
        }, {
            threshold: 0.1
        });
        
        contentSections.forEach(section => {
            observer.observe(section);
        });
    }
});