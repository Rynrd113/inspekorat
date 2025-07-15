// Public Portal JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Public Portal JavaScript loaded');
    
    // Initialize all components
    initMobileMenu();
    initBackToTop();
    initHeroSlider();
    initSearchFunctionality();
    initFilterFunctionality();
    initLightbox();
    initScrollAnimations();
});

// Mobile Menu Toggle
function initMobileMenu() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            
            // Toggle icon
            const icon = this.querySelector('i');
            if (mobileMenu.classList.contains('hidden')) {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            } else {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            }
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileMenuButton.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.add('hidden');
                const icon = mobileMenuButton.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }
}

// Back to Top Button
function initBackToTop() {
    const backToTopButton = document.getElementById('backToTop');
    
    if (backToTopButton) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        });
        
        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
}

// Hero Slider Class
class HeroSlider {
    constructor() {
        this.slides = document.querySelectorAll('.slide');
        this.dots = document.querySelectorAll('.slider-dot');
        this.prevBtn = document.getElementById('prevSlide');
        this.nextBtn = document.getElementById('nextSlide');
        this.currentSlide = 0;
        this.slideInterval = null;
        
        if (!this.slides.length) return;
        
        this.init();
    }
    
    init() {
        this.showSlide(0);
        this.startAutoPlay();
        this.attachEventListeners();
    }
    
    attachEventListeners() {
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => {
                this.stopAutoPlay();
                this.prevSlide();
                this.startAutoPlay();
            });
        }
        
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => {
                this.stopAutoPlay();
                this.nextSlide();
                this.startAutoPlay();
            });
        }
        
        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                this.stopAutoPlay();
                this.goToSlide(index);
                this.startAutoPlay();
            });
        });
        
        // Pause on hover
        const sliderContainer = document.querySelector('.hero-slider');
        if (sliderContainer) {
            sliderContainer.addEventListener('mouseenter', () => this.stopAutoPlay());
            sliderContainer.addEventListener('mouseleave', () => this.startAutoPlay());
        }
    }
    
    showSlide(index) {
        if (index < 0 || index >= this.slides.length) return;
        
        this.slides.forEach(slide => slide.classList.remove('active'));
        this.dots.forEach(dot => dot.classList.remove('active'));
        
        this.slides[index].classList.add('active');
        if (this.dots[index]) {
            this.dots[index].classList.add('active');
        }
        
        this.currentSlide = index;
    }
    
    nextSlide() {
        const next = (this.currentSlide + 1) % this.slides.length;
        this.goToSlide(next);
    }
    
    prevSlide() {
        const prev = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        this.goToSlide(prev);
    }
    
    goToSlide(index) {
        this.showSlide(index);
    }
    
    startAutoPlay() {
        if (this.slides.length > 1) {
            this.slideInterval = setInterval(() => {
                this.nextSlide();
            }, 5000);
        }
    }
    
    stopAutoPlay() {
        if (this.slideInterval) {
            clearInterval(this.slideInterval);
            this.slideInterval = null;
        }
    }
}

// Initialize Hero Slider
function initHeroSlider() {
    new HeroSlider();
}

// Search Functionality
function initSearchFunctionality() {
    const searchInputs = document.querySelectorAll('[id*="search"]');
    
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const cards = document.querySelectorAll('.searchable-card');
            
            cards.forEach(card => {
                const title = card.querySelector('h3, h2, .card-title')?.textContent.toLowerCase() || '';
                const content = card.querySelector('p, .card-content')?.textContent.toLowerCase() || '';
                
                if (title.includes(searchTerm) || content.includes(searchTerm)) {
                    card.style.display = 'block';
                    card.classList.add('animate-fade-in');
                } else {
                    card.style.display = 'none';
                    card.classList.remove('animate-fade-in');
                }
            });
        });
    });
}

// Filter Functionality
function initFilterFunctionality() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            const targetContainer = this.dataset.target;
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter items
            const items = document.querySelectorAll(`${targetContainer} .filterable-item`);
            items.forEach(item => {
                if (filter === 'all' || item.dataset.category === filter) {
                    item.style.display = 'block';
                    item.classList.add('animate-fade-in');
                } else {
                    item.style.display = 'none';
                    item.classList.remove('animate-fade-in');
                }
            });
        });
    });
}

// Lightbox Functionality
function initLightbox() {
    const lightboxTriggers = document.querySelectorAll('[data-lightbox]');
    
    lightboxTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            
            const src = this.dataset.src || this.href;
            const type = this.dataset.type || 'image';
            
            openLightbox(src, type);
        });
    });
}

function openLightbox(src, type) {
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox';
    lightbox.innerHTML = `
        <button class="lightbox-close" onclick="closeLightbox(this)">
            <i class="fas fa-times"></i>
        </button>
        <div class="lightbox-content">
            ${type === 'video' ? 
                `<video controls class="lightbox-content"><source src="${src}" type="video/mp4"></video>` :
                `<img src="${src}" alt="Lightbox Image" class="lightbox-content">`
            }
        </div>
    `;
    
    document.body.appendChild(lightbox);
    
    // Close on click outside
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) {
            closeLightbox(lightbox);
        }
    });
    
    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox(lightbox);
        }
    });
}

function closeLightbox(element) {
    const lightbox = element.closest('.lightbox');
    if (lightbox) {
        lightbox.remove();
    }
}

// Scroll Animations
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
            }
        });
    }, observerOptions);
    
    // Observe all elements with animation classes
    const animatedElements = document.querySelectorAll('.animate-on-scroll');
    animatedElements.forEach(el => observer.observe(el));
}

// Form Validation
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            showFieldError(input, 'Field ini wajib diisi');
            isValid = false;
        } else {
            clearFieldError(input);
        }
    });
    
    return isValid;
}

function showFieldError(input, message) {
    clearFieldError(input);
    
    input.classList.add('is-invalid');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    
    input.parentNode.appendChild(errorDiv);
}

function clearFieldError(input) {
    input.classList.remove('is-invalid');
    
    const errorDiv = input.parentNode.querySelector('.invalid-feedback');
    if (errorDiv) {
        errorDiv.remove();
    }
}

// Utility Functions
function showAlert(type, message) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.innerHTML = `
        <div class="flex justify-between items-center">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="text-xl">&times;</button>
        </div>
    `;
    
    document.body.insertBefore(alert, document.body.firstChild);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function limitText(text, limit) {
    return text.length > limit ? text.substring(0, limit) + '...' : text;
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Lazy Loading for Images
function initLazyLoading() {
    const lazyImages = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                observer.unobserve(img);
            }
        });
    });
    
    lazyImages.forEach(img => imageObserver.observe(img));
}

// Initialize lazy loading
document.addEventListener('DOMContentLoaded', initLazyLoading);

// Global functions for backward compatibility
window.showAlert = showAlert;
window.validateForm = validateForm;
window.formatDate = formatDate;
window.limitText = limitText;
window.openLightbox = openLightbox;
window.closeLightbox = closeLightbox;
