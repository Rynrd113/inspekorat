import './bootstrap';

// Smooth scroll functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling to all anchor links
    const smoothScrollLinks = document.querySelectorAll('a[href^="#"]');
    
    smoothScrollLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                // Calculate offset for fixed header
                const headerHeight = document.querySelector('header')?.offsetHeight || 0;
                const targetPosition = targetElement.offsetTop - headerHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Add smooth scroll behavior to window
    if (CSS.supports('scroll-behavior', 'smooth')) {
        document.documentElement.style.scrollBehavior = 'smooth';
    }
    
    // Navbar scroll effect
    const header = document.querySelector('header');
    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 100) {
                header.classList.add('bg-white/95', 'backdrop-blur-sm', 'shadow-md');
            } else {
                header.classList.remove('bg-white/95', 'backdrop-blur-sm', 'shadow-md');
            }
        });
    }
});
