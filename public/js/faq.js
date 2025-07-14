/**
 * FAQ JavaScript Module
 * Enhanced functionality for FAQ page with search, filtering, and animations
 */

class FAQManager {
    constructor() {
        this.searchInput = document.getElementById('searchFaq');
        this.searchLoading = document.getElementById('search-loading');
        this.categoryButtons = document.querySelectorAll('.category-filter');
        this.faqItems = document.querySelectorAll('.faq-item');
        this.noResults = document.getElementById('no-results');
        this.backToTop = document.getElementById('backToTop');

        this.searchTimeout = null;
        this.isAnimating = false;

        this.init();
    }

    init() {
        this.bindEvents();
        this.handleURLHash();
        this.initializeAnimations();
        this.setupAccessibility();
    }

    bindEvents() {
        // Search functionality
        this.searchInput?.addEventListener('input', this.handleSearch.bind(this));
        this.searchInput?.addEventListener('focus', this.handleSearchFocus.bind(this));
        this.searchInput?.addEventListener('blur', this.handleSearchBlur.bind(this));

        // Category filters
        this.categoryButtons.forEach(button => {
            button.addEventListener('click', this.handleCategoryFilter.bind(this));
        });

        // Back to top functionality
        window.addEventListener('scroll', this.handleScroll.bind(this));
        this.backToTop?.addEventListener('click', this.scrollToTop.bind(this));

        // Keyboard navigation
        document.addEventListener('keydown', this.handleKeyboard.bind(this));

        // Window resize handler
        window.addEventListener('resize', this.handleResize.bind(this));
    }

    handleSearch() {
        clearTimeout(this.searchTimeout);
        this.showSearchLoading();

        this.searchTimeout = setTimeout(() => {
            this.filterFAQs();
            this.hideSearchLoading();
        }, 300);
    }

    handleSearchFocus() {
        this.searchInput.classList.add('focused');
    }

    handleSearchBlur() {
        this.searchInput.classList.remove('focused');
    }

    handleCategoryFilter(event) {
        const category = event.target.dataset.category;

        // Update active button with smooth transition
        this.categoryButtons.forEach(btn => {
            btn.classList.remove('active', 'bg-purple-600', 'text-white');
            btn.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-300');
        });

        event.target.classList.add('active', 'bg-purple-600', 'text-white');
        event.target.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-300');

        this.filterFAQs();
    }

    filterFAQs() {
        const searchTerm = this.searchInput?.value.toLowerCase().trim() || '';
        const activeCategory = document.querySelector('.category-filter.active')?.dataset.category || '';
        let visibleCount = 0;

        this.faqItems.forEach((item, index) => {
            const question = item.querySelector('h4')?.textContent.toLowerCase() || '';
            const answerElement = item.querySelector('.faq-content');
            const answer = answerElement?.textContent.toLowerCase() || '';
            const category = item.dataset.category || '';

            const matchesSearch = !searchTerm || question.includes(searchTerm) || answer.includes(searchTerm);
            const matchesCategory = !activeCategory || category === activeCategory;

            if (matchesSearch && matchesCategory) {
                this.showFAQItem(item, index);
                visibleCount++;

                // Highlight search terms
                if (searchTerm.length > 2) {
                    this.highlightSearchTerms(item, searchTerm);
                } else {
                    this.removeHighlights(item);
                }
            } else {
                this.hideFAQItem(item);
            }
        });

        // Show/hide no results message
        this.toggleNoResults(visibleCount === 0);
    }

    showFAQItem(item, index) {
        item.style.display = 'block';
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';

        setTimeout(() => {
            item.style.transition = 'all 0.3s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, index * 50);
    }

    hideFAQItem(item) {
        item.style.display = 'none';
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
    }

    highlightSearchTerms(item, searchTerm) {
        const button = item.querySelector('.faq-toggle h4');
        const body = item.querySelector('.faq-content');

        if (button && body) {
            const regex = new RegExp(`(${this.escapeRegExp(searchTerm)})`, 'gi');

            // Remove existing highlights
            this.removeHighlights(item);

            // Add new highlights
            button.innerHTML = button.innerHTML.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
            body.innerHTML = body.innerHTML.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
        }
    }

    removeHighlights(item) {
        const button = item.querySelector('.faq-toggle h4');
        const body = item.querySelector('.faq-content');

        if (button) {
            button.innerHTML = button.innerHTML.replace(/<mark[^>]*>([^<]+)<\/mark>/gi, '$1');
        }
        if (body) {
            body.innerHTML = body.innerHTML.replace(/<mark[^>]*>([^<]+)<\/mark>/gi, '$1');
        }
    }

    escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    toggleNoResults(show) {
        if (this.noResults) {
            if (show) {
                this.noResults.classList.remove('hidden');
                this.noResults.style.animation = 'fade-in-up 0.5s ease-out';
            } else {
                this.noResults.classList.add('hidden');
            }
        }
    }

    showSearchLoading() {
        this.searchLoading?.classList.remove('hidden');
    }

    hideSearchLoading() {
        this.searchLoading?.classList.add('hidden');
    }

    handleScroll() {
        if (window.pageYOffset > 300) {
            this.backToTop?.classList.remove('hidden');
        } else {
            this.backToTop?.classList.add('hidden');
        }
    }

    scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    handleKeyboard(event) {
        switch (event.key) {
            case 'Escape':
                this.closeAllFAQs();
                break;
            case '/':
                if (event.ctrlKey || event.metaKey) {
                    event.preventDefault();
                    this.searchInput?.focus();
                }
                break;
        }
    }

    handleResize() {
        // Debounce resize handler
        clearTimeout(this.resizeTimeout);
        this.resizeTimeout = setTimeout(() => {
            this.adjustLayoutForScreenSize();
        }, 250);
    }

    adjustLayoutForScreenSize() {
        const isMobile = window.innerWidth < 768;

        this.categoryButtons.forEach(button => {
            if (isMobile) {
                button.classList.add('mobile-category');
            } else {
                button.classList.remove('mobile-category');
            }
        });
    }

    closeAllFAQs() {
        this.faqItems.forEach(item => {
            const content = item.querySelector('.faq-content');
            const button = item.querySelector('.faq-toggle');
            const icon = item.querySelector('.faq-toggle i');

            if (content && !content.classList.contains('hidden')) {
                content.classList.add('hidden');
                button?.setAttribute('aria-expanded', 'false');
                if (icon) {
                    icon.style.transform = 'rotate(0deg)';
                }
            }
        });
    }

    clearSearch() {
        if (this.searchInput) {
            this.searchInput.value = '';
            this.searchInput.focus();
        }

        // Reset category filter
        const defaultCategory = document.querySelector('.category-filter[data-category=""]');
        if (defaultCategory) {
            defaultCategory.click();
        }

        this.filterFAQs();
    }

    handleURLHash() {
        if (window.location.hash) {
            const target = document.querySelector(window.location.hash);
            if (target && target.classList.contains('faq-item')) {
                setTimeout(() => {
                    target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    const button = target.querySelector('.faq-toggle');
                    if (button) {
                        this.toggleFAQ(button);
                    }
                }, 500);
            }
        }
    }

    initializeAnimations() {
        // Staggered animation for FAQ items on page load
        setTimeout(() => {
            this.faqItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    item.style.transition = 'all 0.3s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }, 100);
    }

    setupAccessibility() {
        // Add ARIA labels and roles
        this.faqItems.forEach((item, index) => {
            const button = item.querySelector('.faq-toggle');
            const content = item.querySelector('.faq-content');

            if (button && content) {
                const id = `faq-content-${index}`;
                content.id = id;
                button.setAttribute('aria-controls', id);
                button.setAttribute('aria-expanded', 'false');
                content.setAttribute('role', 'region');
            }
        });

        // Add search input accessibility
        if (this.searchInput) {
            this.searchInput.setAttribute('aria-label', 'Cari pertanyaan FAQ');
            this.searchInput.setAttribute('role', 'searchbox');
        }
    }

    toggleFAQ(button) {
        if (this.isAnimating) return;

        this.isAnimating = true;

        const faqItem = button.closest('.faq-item');
        const content = faqItem.querySelector('.faq-content');
        const icon = button.querySelector('i');
        const isExpanded = button.getAttribute('aria-expanded') === 'true';

        // Close all other FAQs first
        this.faqItems.forEach(item => {
            if (item !== faqItem) {
                const otherContent = item.querySelector('.faq-content');
                const otherIcon = item.querySelector('.faq-toggle i');
                const otherButton = item.querySelector('.faq-toggle');

                if (otherContent && !otherContent.classList.contains('hidden')) {
                    otherContent.classList.add('hidden');
                    otherButton?.setAttribute('aria-expanded', 'false');
                    if (otherIcon) {
                        otherIcon.style.transform = 'rotate(0deg)';
                    }
                }
            }
        });

        // Toggle current FAQ
        if (isExpanded) {
            content.classList.add('hidden');
            button.setAttribute('aria-expanded', 'false');
            if (icon) {
                icon.style.transform = 'rotate(0deg)';
            }
        } else {
            content.classList.remove('hidden');
            button.setAttribute('aria-expanded', 'true');
            if (icon) {
                icon.style.transform = 'rotate(180deg)';
            }

            // Smooth scroll to FAQ
            setTimeout(() => {
                faqItem.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                    inline: 'nearest'
                });
            }, 100);
        }

        // Reset animation flag
        setTimeout(() => {
            this.isAnimating = false;
        }, 300);
    }

    // Analytics tracking
    trackEvent(action, category = 'FAQ', label = '') {
        if (typeof gtag !== 'undefined') {
            gtag('event', action, {
                event_category: category,
                event_label: label
            });
        }
    }

    // Public methods
    destroy() {
        // Clean up event listeners
        this.searchInput?.removeEventListener('input', this.handleSearch);
        this.categoryButtons.forEach(button => {
            button.removeEventListener('click', this.handleCategoryFilter);
        });
        window.removeEventListener('scroll', this.handleScroll);
        window.removeEventListener('resize', this.handleResize);
        document.removeEventListener('keydown', this.handleKeyboard);

        // Clear timeouts
        clearTimeout(this.searchTimeout);
        clearTimeout(this.resizeTimeout);
    }
}

// Global FAQ toggle function (called from HTML)
window.toggleFaq = function(button) {
    if (window.faqManager) {
        window.faqManager.toggleFAQ(button);
    }
};

// Global clear search function
window.clearSearch = function() {
    if (window.faqManager) {
        window.faqManager.clearSearch();
    }
};

// Initialize FAQ Manager when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.faqManager = new FAQManager();
});

// Handle page visibility changes
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        // Page is hidden, pause animations
        document.body.classList.add('page-hidden');
    } else {
        // Page is visible, resume animations
        document.body.classList.remove('page-hidden');
    }
});

// Service Worker registration for offline support
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js')
            .then(function(registration) {
                console.log('SW registered: ', registration);
            })
            .catch(function(registrationError) {
                console.log('SW registration failed: ', registrationError);
            });
    });
}

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = FAQManager;
}
