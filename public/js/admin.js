/**
 * Admin Panel Common Functions
 * Portal Inspektorat Papua Tengah
 */

// Global admin functions
window.AdminPanel = {
    // Get admin token from localStorage
    getToken: function() {
        return localStorage.getItem('admin_token');
    },

    // Set admin token to localStorage
    setToken: function(token) {
        localStorage.setItem('admin_token', token);
    },

    // Clear admin token
    clearToken: function() {
        localStorage.removeItem('admin_token');
    },

    // Common AJAX headers
    getHeaders: function() {
        const token = this.getToken();
        if (!token) {
            console.error('No admin token available for API request');
            throw new Error('Authentication token required');
        }
        
        return {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        };
    },

    // Show alert notification
    showAlert: function(message, type = 'info', duration = 3000) {
        const alertColors = {
            'success': 'bg-green-500 text-white',
            'error': 'bg-red-500 text-white',
            'warning': 'bg-yellow-500 text-white',
            'info': 'bg-blue-500 text-white'
        };

        const alertDiv = document.createElement('div');
        alertDiv.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${alertColors[type]} animate-fade-in`;
        alertDiv.innerHTML = `
            <div class="flex items-center">
                <span class="mr-2">${this.getAlertIcon(type)}</span>
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, duration);
    },

    // Get alert icon based on type
    getAlertIcon: function(type) {
        const icons = {
            'success': '<i class="fas fa-check-circle"></i>',
            'error': '<i class="fas fa-exclamation-circle"></i>',
            'warning': '<i class="fas fa-exclamation-triangle"></i>',
            'info': '<i class="fas fa-info-circle"></i>'
        };
        return icons[type] || icons['info'];
    },

    // Format date to Indonesian locale
    formatDate: function(dateString, includeTime = false) {
        const date = new Date(dateString);
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        
        if (includeTime) {
            options.hour = '2-digit';
            options.minute = '2-digit';
        }
        
        return date.toLocaleDateString('id-ID', options);
    },

    // Truncate text
    truncateText: function(text, maxLength = 100) {
        if (text.length <= maxLength) return text;
        return text.substring(0, maxLength) + '...';
    },

    // Confirm dialog
    confirm: function(message, callback) {
        if (confirm(message)) {
            callback();
        }
    },

    // Loading state management
    setLoading: function(element, isLoading = true) {
        if (isLoading) {
            element.disabled = true;
            element.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memuat...';
        } else {
            element.disabled = false;
            element.innerHTML = element.getAttribute('data-original-text') || 'Submit';
        }
    },

    // Debounce function for search
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    // Handle API errors
    handleError: function(error) {
        console.error('API Error:', error);
        
        if (error.status === 401) {
            this.showAlert('Sesi telah berakhir. Silakan login kembali.', 'error');
            setTimeout(() => {
                window.location.href = '/admin/login';
            }, 2000);
        } else if (error.status === 403) {
            this.showAlert('Akses ditolak.', 'error');
        } else if (error.status === 422) {
            this.showAlert('Data tidak valid. Periksa input Anda.', 'error');
        } else if (error.status >= 500) {
            this.showAlert('Terjadi kesalahan server. Silakan coba lagi.', 'error');
        } else {
            this.showAlert('Terjadi kesalahan. Silakan coba lagi.', 'error');
        }
    }
};

// Initialize admin panel
document.addEventListener('DOMContentLoaded', function() {
    // Add fade-in animation CSS if not exists
    if (!document.querySelector('#admin-animations')) {
        const style = document.createElement('style');
        style.id = 'admin-animations';
        style.textContent = `
            .animate-fade-in {
                animation: fadeIn 0.3s ease-in;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);
    }
    
    // Store original button texts for loading states
    document.querySelectorAll('button[type="submit"]').forEach(button => {
        button.setAttribute('data-original-text', button.innerHTML);
    });
});

// Export for global use
window.showAlert = AdminPanel.showAlert.bind(AdminPanel);
window.clearAdminToken = AdminPanel.clearToken.bind(AdminPanel);
