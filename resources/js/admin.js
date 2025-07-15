/**
 * Admin Panel JavaScript
 * Provides consistent interaction patterns for admin interface
 */

// Admin namespace with performance optimizations
window.Admin = window.Admin || {};

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