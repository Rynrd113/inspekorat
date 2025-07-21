/**
 * Admin Panel JavaScript
 * Provides consistent interaction patterns for admin interface
 */

// Admin namespace with performance optimizations
window.Admin = window.Admin || {};

/**
 * Session Management
 * Handles session timeout and auto-logout prevention
 */
Admin.Session = {
    // Session timeout in minutes (120 minutes = 2 hours)
    timeoutMinutes: 120, 
    warningMinutes: 10, // Show warning 10 minutes before expiry
    lastActivity: Date.now(),
    timeoutTimer: null,
    warningTimer: null,
    
    // Initialize session management
    init: function() {
        this.resetTimers();
        this.bindEvents();
    },
    
    // Reset activity timers
    resetTimers: function() {
        clearTimeout(this.timeoutTimer);
        clearTimeout(this.warningTimer);
        
        this.lastActivity = Date.now();
        
        // Set warning timer (30 minutes before expiry)
        this.warningTimer = setTimeout(() => {
            this.showSessionWarning();
        }, (this.timeoutMinutes - this.warningMinutes) * 60 * 1000);
        
        // Set timeout timer (session expiry)
        this.timeoutTimer = setTimeout(() => {
            this.handleSessionExpiry();
        }, this.timeoutMinutes * 60 * 1000);
    },
    
    // Bind activity events
    bindEvents: function() {
        const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        events.forEach(event => {
            document.addEventListener(event, () => {
                this.updateActivity();
            }, { passive: true });
        });
    },
    
    // Update last activity time
    updateActivity: function() {
        const now = Date.now();
        
        // Only reset timers if it's been more than 5 minutes since last reset
        if (now - this.lastActivity > 5 * 60 * 1000) {
            this.lastActivity = now;
            this.resetTimers();
        }
    },
    
    // Show session warning modal
    showSessionWarning: function() {
        if (confirm('Sesi Anda akan berakhir dalam 10 menit. Klik OK untuk memperpanjang sesi.')) {
            this.extendSession();
        }
    },
    
    // Handle session expiry
    handleSessionExpiry: function() {
        alert('Sesi Anda telah berakhir. Anda akan dialihkan ke halaman login.');
        window.location.href = '/admin/login';
    },
    
    // Extend session via AJAX
    extendSession: function() {
        fetch('/admin/extend-session', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            }
        }).then(response => {
            if (response.ok) {
                this.resetTimers();
                console.log('Session extended successfully');
            }
        }).catch(error => {
            console.error('Failed to extend session:', error);
        });
    }
};

// Debounce utility for performance
Admin.debounce = function(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
};

/**
 * Modal Management with improved accessibility
 */
Admin.Modal = {
    activeModal: null,
    
    // Open modal with focus management
    open: function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.setAttribute('aria-hidden', 'false');
            this.activeModal = modalId;
            document.body.style.overflow = 'hidden';
        }
    },
    
    // Close modal and restore focus
    close: function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true');
            this.activeModal = null;
            document.body.style.overflow = '';
        }
    }
};

/**
 * Notification System with auto-dismiss
 */
Admin.Notification = {
    show: function(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button class="notification-close" aria-label="Close">×</button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            this.hide(notification);
        }, duration);
        
        notification.querySelector('.notification-close').addEventListener('click', () => {
            this.hide(notification);
        });
    },
    
    hide: function(notification) {
        notification.classList.add('notification-fade-out');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }
};

/**
 * Main Admin initialization
 */
Admin.init = function() {
    // Initialize session management
    if (typeof Admin.Session !== 'undefined') {
        Admin.Session.init();
    }
    
    // Setup modal triggers
    document.addEventListener('click', function(e) {
        const trigger = e.target.closest('[data-modal-open]');
        if (trigger) {
            e.preventDefault();
            const modalId = trigger.dataset.modalOpen;
            Admin.Modal.open(modalId);
        }
        
        const close = e.target.closest('[data-modal-close]');
        if (close) {
            e.preventDefault();
            const modalId = close.dataset.modalClose || close.closest('[role="dialog"]').id;
            Admin.Modal.close(modalId);
        }
    });
    
    // Setup keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModal = document.querySelector('[role="dialog"]:not(.hidden)');
            if (openModal) {
                Admin.Modal.close(openModal.id);
            }
        }
    });
    
    console.log('Admin panel initialized successfully');
};

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', Admin.init);
} else {
    Admin.init();
}

// Global functions for backward compatibility
window.openModal = Admin.Modal.open;
window.closeModal = Admin.Modal.close;
window.showNotification = Admin.Notification.show;