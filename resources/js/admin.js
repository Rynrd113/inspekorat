/**
 * Admin Panel JavaScript
 * Handles all admin-specific functionality
 */

// Admin namespace
window.Admin = {
    // Initialize admin functionality
    init: function() {
        this.setupCSRFToken();
        this.setupModals();
        this.setupTooltips();
        this.setupFilters();
        this.setupFormValidation();
        this.setupTableSorting();
        this.loadAdminToken();
    },

    // Setup CSRF token for AJAX requests
    setupCSRFToken: function() {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        }
    },

    // Setup modal functionality
    setupModals: function() {
        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-overlay')) {
                Admin.closeAllModals();
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                Admin.closeAllModals();
            }
        });
    },

    // Setup tooltips
    setupTooltips: function() {
        const tooltipElements = document.querySelectorAll('[title]');
        tooltipElements.forEach(element => {
            element.addEventListener('mouseenter', function() {
                Admin.showTooltip(this);
            });
            element.addEventListener('mouseleave', function() {
                Admin.hideTooltip();
            });
        });
    },

    // Setup filters
    setupFilters: function() {
        const filterElements = document.querySelectorAll('[id^="filter"], [id^="search"]');
        filterElements.forEach(element => {
            element.addEventListener('change', function() {
                Admin.applyFilters();
            });
            element.addEventListener('input', function() {
                Admin.debounce(Admin.applyFilters, 300)();
            });
        });
    },

    // Setup form validation
    setupFormValidation: function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!Admin.validateForm(this)) {
                    e.preventDefault();
                }
            });
        });
    },

    // Setup table sorting
    setupTableSorting: function() {
        const sortableHeaders = document.querySelectorAll('th[data-sort]');
        sortableHeaders.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function() {
                Admin.sortTable(this);
            });
        });
    },

    // Load admin token
    loadAdminToken: function() {
        const existingToken = localStorage.getItem('admin_token');
        if (existingToken) {
            console.log('Admin token loaded successfully');
        }
    },

    // Modal functions
    showModal: function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('fade-in');
            document.body.style.overflow = 'hidden';
        }
    },

    hideModal: function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('fade-in');
            document.body.style.overflow = '';
        }
    },

    closeAllModals: function() {
        const modals = document.querySelectorAll('.modal, [id$="Modal"]');
        modals.forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
                modal.classList.remove('fade-in');
            }
        });
        document.body.style.overflow = '';
    },

    // Tooltip functions
    showTooltip: function(element) {
        const tooltip = document.createElement('div');
        tooltip.className = 'absolute z-50 px-2 py-1 text-sm bg-gray-900 text-white rounded shadow-lg';
        tooltip.textContent = element.getAttribute('title');
        tooltip.id = 'admin-tooltip';
        
        const rect = element.getBoundingClientRect();
        tooltip.style.left = rect.left + 'px';
        tooltip.style.top = (rect.top - 35) + 'px';
        
        document.body.appendChild(tooltip);
        element.removeAttribute('title');
        element.setAttribute('data-original-title', tooltip.textContent);
    },

    hideTooltip: function() {
        const tooltip = document.getElementById('admin-tooltip');
        if (tooltip) {
            const element = document.querySelector('[data-original-title]');
            if (element) {
                element.setAttribute('title', element.getAttribute('data-original-title'));
                element.removeAttribute('data-original-title');
            }
            tooltip.remove();
        }
    },

    // Filter functions
    applyFilters: function() {
        const filters = {};
        const filterElements = document.querySelectorAll('[id^="filter"]');
        const searchElements = document.querySelectorAll('[id^="search"]');
        
        filterElements.forEach(element => {
            if (element.value) {
                filters[element.id] = element.value;
            }
        });
        
        searchElements.forEach(element => {
            if (element.value) {
                filters[element.id] = element.value;
            }
        });
        
        Admin.filterTable(filters);
    },

    filterTable: function(filters) {
        const table = document.querySelector('table tbody');
        if (!table) return;
        
        const rows = table.querySelectorAll('tr');
        rows.forEach(row => {
            let showRow = true;
            
            Object.keys(filters).forEach(filterId => {
                const filterValue = filters[filterId].toLowerCase();
                const rowText = row.textContent.toLowerCase();
                
                if (filterId.startsWith('search')) {
                    if (!rowText.includes(filterValue)) {
                        showRow = false;
                    }
                } else if (filterId.startsWith('filter')) {
                    const cellIndex = Admin.getFilterColumnIndex(filterId);
                    if (cellIndex >= 0) {
                        const cellText = row.cells[cellIndex].textContent.toLowerCase();
                        if (!cellText.includes(filterValue)) {
                            showRow = false;
                        }
                    }
                }
            });
            
            row.style.display = showRow ? '' : 'none';
        });
    },

    getFilterColumnIndex: function(filterId) {
        const filterMapping = {
            'filterKategori': 2,
            'filterStatus': 3,
            'filterTipe': 1
        };
        return filterMapping[filterId] || -1;
    },

    // Form validation
    validateForm: function(form) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                Admin.showFieldError(field, 'Field ini wajib diisi');
                isValid = false;
            } else {
                Admin.clearFieldError(field);
            }
        });
        
        return isValid;
    },

    showFieldError: function(field, message) {
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error text-red-500 text-sm mt-1';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
        field.classList.add('border-red-500');
    },

    clearFieldError: function(field) {
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
        field.classList.remove('border-red-500');
    },

    // Table sorting
    sortTable: function(header) {
        const table = header.closest('table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const columnIndex = Array.from(header.parentNode.children).indexOf(header);
        const sortDirection = header.dataset.sortDirection === 'asc' ? 'desc' : 'asc';
        
        rows.sort((a, b) => {
            const aText = a.cells[columnIndex].textContent.trim();
            const bText = b.cells[columnIndex].textContent.trim();
            
            if (sortDirection === 'asc') {
                return aText.localeCompare(bText);
            } else {
                return bText.localeCompare(aText);
            }
        });
        
        rows.forEach(row => tbody.appendChild(row));
        
        // Update header indicators
        table.querySelectorAll('th').forEach(th => {
            th.classList.remove('sort-asc', 'sort-desc');
            delete th.dataset.sortDirection;
        });
        
        header.classList.add(sortDirection === 'asc' ? 'sort-asc' : 'sort-desc');
        header.dataset.sortDirection = sortDirection;
    },

    // Utility functions
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

    // API helper functions
    makeRequest: function(url, options = {}) {
        const token = localStorage.getItem('admin_token');
        
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Authorization': token ? `Bearer ${token}` : ''
            }
        };
        
        return fetch(url, { ...defaultOptions, ...options })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .catch(error => {
                console.error('API request failed:', error);
                Admin.showNotification('Terjadi kesalahan saat mengirim permintaan', 'error');
                throw error;
            });
    },

    // Notification system
    showNotification: function(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg ${Admin.getNotificationClasses(type)}`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 5000);
    },

    getNotificationClasses: function(type) {
        const classes = {
            'success': 'bg-green-100 text-green-800 border border-green-200',
            'error': 'bg-red-100 text-red-800 border border-red-200',
            'warning': 'bg-yellow-100 text-yellow-800 border border-yellow-200',
            'info': 'bg-blue-100 text-blue-800 border border-blue-200'
        };
        return classes[type] || classes.info;
    },

    // Clear admin token on logout
    clearAdminToken: function() {
        localStorage.removeItem('admin_token');
        console.log('Admin token cleared');
    }
};

// Global functions for backward compatibility
function confirmDelete(id) {
    document.getElementById('deleteForm').action = document.getElementById('deleteForm').action.replace(/\/\d+$/, `/${id}`);
    Admin.showModal('deleteModal');
}

function closeDeleteModal() {
    Admin.hideModal('deleteModal');
}

function viewMedia(type, src) {
    const mediaContent = document.getElementById('mediaContent');
    const modal = document.getElementById('mediaModal');
    
    if (type === 'foto') {
        mediaContent.innerHTML = `<img src="${src}" class="max-w-full max-h-96 object-contain" alt="Media">`;
    } else if (type === 'video') {
        mediaContent.innerHTML = `<video controls class="max-w-full max-h-96"><source src="${src}" type="video/mp4">Your browser does not support the video tag.</video>`;
    }
    
    Admin.showModal('mediaModal');
}

function closeMediaModal() {
    Admin.hideModal('mediaModal');
}

function moveUp(id) {
    const currentPath = window.location.pathname;
    const basePath = currentPath.substring(0, currentPath.lastIndexOf('/'));
    
    Admin.makeRequest(`${basePath}/${id}/move-up`, {
        method: 'POST'
    })
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            Admin.showNotification('Gagal memindahkan item', 'error');
        }
    })
    .catch(error => {
        console.error('Error moving item up:', error);
    });
}

function moveDown(id) {
    const currentPath = window.location.pathname;
    const basePath = currentPath.substring(0, currentPath.lastIndexOf('/'));
    
    Admin.makeRequest(`${basePath}/${id}/move-down`, {
        method: 'POST'
    })
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            Admin.showNotification('Gagal memindahkan item', 'error');
        }
    })
    .catch(error => {
        console.error('Error moving item down:', error);
    });
}

function clearAdminToken() {
    Admin.clearAdminToken();
}

// Initialize admin functionality when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    Admin.init();
});

// Store admin token when provided
if (typeof adminToken !== 'undefined') {
    localStorage.setItem('admin_token', adminToken);
    console.log('Admin token stored from server');
}
