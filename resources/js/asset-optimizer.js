/**
 * Asset Optimization Utilities
 * Handles image lazy loading, font optimization, and performance monitoring
 */

class AssetOptimizer {
    constructor() {
        this.lazyImages = [];
        this.imageObserver = null;
        this.fontObserver = null;
        this.performanceMetrics = {
            lcp: 0,
            fid: 0,
            cls: 0
        };
        
        this.init();
    }

    init() {
        this.setupLazyLoading();
        this.setupFontOptimization();
        this.setupPerformanceMonitoring();
        this.setupWebPSupport();
        this.setupCriticalResourceHints();
    }

    /**
     * Setup lazy loading for images
     */
    setupLazyLoading() {
        if ('IntersectionObserver' in window) {
            this.imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.loadImage(entry.target);
                        this.imageObserver.unobserve(entry.target);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });

            this.observeImages();
        } else {
            // Fallback for browsers without IntersectionObserver
            this.loadAllImages();
        }
    }

    /**
     * Observe images for lazy loading
     */
    observeImages() {
        const images = document.querySelectorAll('img[data-src]');
        images.forEach(img => {
            this.imageObserver.observe(img);
        });
    }

    /**
     * Load individual image
     */
    loadImage(img) {
        const src = img.dataset.src;
        const srcset = img.dataset.srcset;
        
        if (src) {
            img.src = src;
        }
        
        if (srcset) {
            img.srcset = srcset;
        }

        img.classList.add('loaded');
        img.removeAttribute('data-src');
        img.removeAttribute('data-srcset');

        // Add loading animation
        img.style.opacity = '0';
        img.onload = () => {
            img.style.transition = 'opacity 0.3s ease';
            img.style.opacity = '1';
        };
    }

    /**
     * Load all images (fallback)
     */
    loadAllImages() {
        const images = document.querySelectorAll('img[data-src]');
        images.forEach(img => {
            this.loadImage(img);
        });
    }

    /**
     * Setup font optimization
     */
    setupFontOptimization() {
        if ('fonts' in document) {
            // Preload critical fonts
            document.fonts.load('400 16px Inter').then(() => {
                document.documentElement.classList.add('fonts-loaded');
            });

            // Monitor font loading
            document.fonts.ready.then(() => {
                console.log('All fonts loaded');
            });
        }
    }

    /**
     * Setup performance monitoring
     */
    setupPerformanceMonitoring() {
        if ('PerformanceObserver' in window) {
            // Largest Contentful Paint
            const lcpObserver = new PerformanceObserver((entryList) => {
                const entries = entryList.getEntries();
                const lastEntry = entries[entries.length - 1];
                this.performanceMetrics.lcp = lastEntry.startTime;
                console.log('LCP:', lastEntry.startTime);
            });
            lcpObserver.observe({entryTypes: ['largest-contentful-paint']});

            // First Input Delay
            const fidObserver = new PerformanceObserver((entryList) => {
                const entries = entryList.getEntries();
                entries.forEach(entry => {
                    this.performanceMetrics.fid = entry.processingStart - entry.startTime;
                    console.log('FID:', this.performanceMetrics.fid);
                });
            });
            fidObserver.observe({entryTypes: ['first-input']});

            // Cumulative Layout Shift
            const clsObserver = new PerformanceObserver((entryList) => {
                const entries = entryList.getEntries();
                entries.forEach(entry => {
                    if (!entry.hadRecentInput) {
                        this.performanceMetrics.cls += entry.value;
                        console.log('CLS:', this.performanceMetrics.cls);
                    }
                });
            });
            clsObserver.observe({entryTypes: ['layout-shift']});
        }
    }

    /**
     * Setup WebP support detection
     */
    setupWebPSupport() {
        const webpSupport = this.checkWebPSupport();
        if (webpSupport) {
            document.documentElement.classList.add('webp-support');
        } else {
            document.documentElement.classList.add('no-webp-support');
        }
    }

    /**
     * Check WebP support
     */
    checkWebPSupport() {
        const canvas = document.createElement('canvas');
        canvas.width = 1;
        canvas.height = 1;
        return canvas.toDataURL('image/webp').indexOf('data:image/webp') === 0;
    }

    /**
     * Setup critical resource hints
     */
    setupCriticalResourceHints() {
        // Preload critical resources
        const criticalResources = [
            '/css/app.css',
            '/js/app.js',
            '/fonts/inter-variable.woff2'
        ];

        criticalResources.forEach(resource => {
            const link = document.createElement('link');
            link.rel = 'preload';
            
            if (resource.endsWith('.css')) {
                link.as = 'style';
                link.onload = () => {
                    link.rel = 'stylesheet';
                };
            } else if (resource.endsWith('.js')) {
                link.as = 'script';
            } else if (resource.endsWith('.woff2')) {
                link.as = 'font';
                link.type = 'font/woff2';
                link.crossOrigin = 'anonymous';
            }
            
            link.href = resource;
            document.head.appendChild(link);
        });
    }

    /**
     * Optimize images dynamically
     */
    optimizeImages() {
        const images = document.querySelectorAll('img');
        
        images.forEach(img => {
            // Add loading attribute for native lazy loading
            if (!img.hasAttribute('loading')) {
                img.setAttribute('loading', 'lazy');
            }
            
            // Add decoding attribute for async decoding
            if (!img.hasAttribute('decoding')) {
                img.setAttribute('decoding', 'async');
            }
            
            // Optimize dimensions
            if (img.naturalWidth > 0 && img.naturalHeight > 0) {
                if (!img.hasAttribute('width')) {
                    img.setAttribute('width', img.naturalWidth);
                }
                if (!img.hasAttribute('height')) {
                    img.setAttribute('height', img.naturalHeight);
                }
            }
        });
    }

    /**
     * Defer non-critical CSS
     */
    deferNonCriticalCSS() {
        const nonCriticalCSS = document.querySelectorAll('link[rel="stylesheet"][data-defer]');
        
        nonCriticalCSS.forEach(link => {
            const href = link.href;
            link.remove();
            
            // Load after page load
            window.addEventListener('load', () => {
                const deferredLink = document.createElement('link');
                deferredLink.rel = 'stylesheet';
                deferredLink.href = href;
                document.head.appendChild(deferredLink);
            });
        });
    }

    /**
     * Optimize third-party scripts
     */
    optimizeThirdPartyScripts() {
        const scripts = document.querySelectorAll('script[src]');
        
        scripts.forEach(script => {
            const src = script.src;
            
            // Add loading attributes for external scripts
            if (src.includes('//')) {
                script.setAttribute('defer', 'defer');
                
                // Add integrity check for CDN scripts
                if (src.includes('cdn')) {
                    script.setAttribute('crossorigin', 'anonymous');
                }
            }
        });
    }

    /**
     * Get performance metrics
     */
    getPerformanceMetrics() {
        return this.performanceMetrics;
    }

    /**
     * Report performance metrics
     */
    reportPerformanceMetrics() {
        // Send to analytics or monitoring service
        console.log('Performance Metrics:', this.performanceMetrics);
        
        // Example: Send to Google Analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'performance_metrics', {
                'lcp': this.performanceMetrics.lcp,
                'fid': this.performanceMetrics.fid,
                'cls': this.performanceMetrics.cls
            });
        }
    }

    /**
     * Cleanup observers
     */
    cleanup() {
        if (this.imageObserver) {
            this.imageObserver.disconnect();
        }
        if (this.fontObserver) {
            this.fontObserver.disconnect();
        }
    }
}

// Initialize asset optimizer when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.assetOptimizer = new AssetOptimizer();
});

// Report performance metrics after page load
window.addEventListener('load', () => {
    setTimeout(() => {
        if (window.assetOptimizer) {
            window.assetOptimizer.reportPerformanceMetrics();
        }
    }, 1000);
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (window.assetOptimizer) {
        window.assetOptimizer.cleanup();
    }
});

// Export for use in other modules
export default AssetOptimizer;
