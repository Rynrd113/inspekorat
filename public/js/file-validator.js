/**
 * File Validation Helper
 * Reusable file validation for file uploads
 * Portal Inspektorat Papua Tengah
 */

window.FileValidator = {
    /**
     * Validate file size
     * @param {File} file - File object to validate
     * @param {number} maxSizeMB - Maximum size in MB
     * @param {string} customMessage - Custom error message (optional)
     * @returns {boolean} - True if valid, false otherwise
     */
    validateFileSize: function(file, maxSizeMB, customMessage = null) {
        if (!file) return true;
        
        const fileSizeMB = file.size / 1024 / 1024;
        
        if (fileSizeMB > maxSizeMB) {
            const message = customMessage || `Ukuran file terlalu besar. Maksimal ${maxSizeMB}MB.`;
            alert(message);
            return false;
        }
        
        return true;
    },

    /**
     * Validate file type
     * @param {File} file - File object to validate
     * @param {Array} allowedTypes - Array of allowed extensions
     * @param {string} customMessage - Custom error message (optional)
     * @returns {boolean} - True if valid, false otherwise
     */
    validateFileType: function(file, allowedTypes, customMessage = null) {
        if (!file) return true;
        
        const fileName = file.name;
        const fileExtension = fileName.split('.').pop().toLowerCase();
        
        if (!allowedTypes.includes(fileExtension)) {
            const message = customMessage || `Format file tidak valid. Hanya ${allowedTypes.join(', ')} yang diperbolehkan.`;
            alert(message);
            return false;
        }
        
        return true;
    },

    /**
     * Validate file size and type
     * @param {File} file - File object to validate
     * @param {Object} options - Validation options {maxSizeMB, allowedTypes}
     * @returns {boolean} - True if valid, false otherwise
     */
    validate: function(file, options = {}) {
        if (!file) return true;
        
        const { maxSizeMB, allowedTypes } = options;
        
        // Validate size if specified
        if (maxSizeMB && !this.validateFileSize(file, maxSizeMB)) {
            return false;
        }
        
        // Validate type if specified
        if (allowedTypes && !this.validateFileType(file, allowedTypes)) {
            return false;
        }
        
        return true;
    },

    /**
     * Attach file validation to input element
     * @param {string} selector - CSS selector for input element
     * @param {Object} options - Validation options {maxSizeMB, allowedTypes, customMessage}
     */
    attachToInput: function(selector, options = {}) {
        const input = document.querySelector(selector);
        
        if (!input) {
            console.warn(`Input element not found: ${selector}`);
            return;
        }
        
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (!FileValidator.validate(file, options)) {
                e.target.value = ''; // Clear invalid file
            }
        });
    },

    /**
     * Validate multiple files
     * @param {FileList} files - FileList object to validate
     * @param {Object} options - Validation options {maxSizeMB, maxTotalSizeMB, allowedTypes}
     * @returns {boolean} - True if all valid, false otherwise
     */
    validateMultiple: function(files, options = {}) {
        if (!files || files.length === 0) return true;
        
        const { maxSizeMB, maxTotalSizeMB, allowedTypes } = options;
        let totalSize = 0;
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            
            // Validate individual file
            if (maxSizeMB && !this.validateFileSize(file, maxSizeMB)) {
                return false;
            }
            
            if (allowedTypes && !this.validateFileType(file, allowedTypes)) {
                return false;
            }
            
            totalSize += file.size;
        }
        
        // Validate total size
        if (maxTotalSizeMB) {
            const totalSizeMB = totalSize / 1024 / 1024;
            
            if (totalSizeMB > maxTotalSizeMB) {
                alert(`Total ukuran file terlalu besar. Maksimal ${maxTotalSizeMB}MB untuk semua file.`);
                return false;
            }
        }
        
        return true;
    },

    /**
     * Get file size in human-readable format
     * @param {number} bytes - File size in bytes
     * @returns {string} - Formatted file size
     */
    formatFileSize: function(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
    },

    /**
     * Display file info
     * @param {File} file - File object
     * @param {string} targetSelector - Target element to display info
     */
    displayFileInfo: function(file, targetSelector) {
        const target = document.querySelector(targetSelector);
        
        if (!target || !file) return;
        
        const fileInfo = `
            <div class="file-info text-sm text-gray-600">
                <i class="fas fa-file mr-2"></i>
                <span>${file.name}</span>
                <span class="text-gray-400 ml-2">(${this.formatFileSize(file.size)})</span>
            </div>
        `;
        
        target.innerHTML = fileInfo;
    }
};

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = FileValidator;
}
