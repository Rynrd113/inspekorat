/**
 * Service Worker for Portal Inspektorat Papua Tengah
 * Provides offline support, asset caching, and performance optimization
 */

const CACHE_NAME = 'inspektorat-v3.0';
const CACHE_STATIC_NAME = 'inspektorat-static-v3.0';
const CACHE_DYNAMIC_NAME = 'inspektorat-dynamic-v3.0';

// Static assets yang akan di-cache
const STATIC_ASSETS = [
    '/',
    '/css/app.css',
    '/js/app.js',
    '/css/admin.css',
    '/js/admin.js',
    '/favicon.ico',
    '/logo.svg',
    '/manifest.json',
    '/offline.html',
    // External CDN assets
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
    'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap'
];

// Dynamic assets patterns
const DYNAMIC_CACHE_PATTERNS = [
    /\/api\//,
    /\/storage\//,
    /\.(?:png|jpg|jpeg|svg|gif|webp|avif)$/,
    /\.(?:js|css)$/,
    /\.(?:woff|woff2|eot|ttf|otf)$/
];

// Assets yang tidak boleh di-cache
const CACHE_BLACKLIST = [
    /\/admin\/login/,
    /\/admin\/logout/,
    /\/api\/auth/,
    /\/api\/csrf/,
    /\/sanctum\//,
    /\/broadcasting\//
];

// Install event - cache static assets
self.addEventListener('install', function(event) {
    console.log('Service Worker: Installing...');
    
    event.waitUntil(
        Promise.all([
            // Cache static assets
            caches.open(CACHE_STATIC_NAME).then(function(cache) {
                console.log('Service Worker: Caching static assets');
                return cache.addAll(STATIC_ASSETS);
            }),
            // Initialize dynamic cache
            caches.open(CACHE_DYNAMIC_NAME)
        ])
        .then(function() {
            console.log('Service Worker: Installation complete');
            return self.skipWaiting();
        })
        .catch(function(error) {
            console.error('Service Worker: Installation failed', error);
        })
    );
});

// Activate event - cleanup old caches
self.addEventListener('activate', function(event) {
    console.log('Service Worker: Activating...');
    
    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cacheName) {
                    if (cacheName !== CACHE_STATIC_NAME && 
                        cacheName !== CACHE_DYNAMIC_NAME &&
                        cacheName !== CACHE_NAME) {
                        console.log('Service Worker: Deleting old cache', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
        .then(function() {
            console.log('Service Worker: Activation complete');
            return self.clients.claim();
        })
    );
});

// Fetch event - serve from cache with fallback
self.addEventListener('fetch', function(event) {
    // Skip non-http requests (chrome-extension, data, etc.)
    if (!event.request.url.startsWith('http')) {
        return;
    }

    event.respondWith(
        caches.match(event.request)
            .then(function(response) {
                // Return cached version or fetch from network
                if (response) {
                    console.log('Service Worker: Serving from cache', event.request.url);
                    return response;
                }

                return fetch(event.request).then(function(response) {
                    // Check if we received a valid response
                    if (!response || response.status !== 200 || response.type !== 'basic') {
                        return response;
                    }

                    // Clone the response for caching
                    const responseToCache = response.clone();

                    // Only cache http/https requests
                    if (event.request.url.startsWith('http')) {
                        caches.open(CACHE_NAME)
                            .then(function(cache) {
                                cache.put(event.request, responseToCache);
                            })
                            .catch(function(error) {
                                console.log('Cache put failed:', error);
                            });
                    }

                    return response;
                });
            })
            .catch(function(error) {
                console.error('Service Worker: Fetch failed', error);

                // Return offline page for navigation requests
                if (event.request.destination === 'document') {
                    return caches.match('/offline.html');
                }

                return new Response('Offline - Content not available', {
                    status: 503,
                    statusText: 'Service Unavailable'
                });
            })
    );
});

// Background sync for form submissions
self.addEventListener('sync', function(event) {
    if (event.tag === 'faq-search-sync') {
        event.waitUntil(syncFAQSearch());
    }
});

// Push notifications
self.addEventListener('push', function(event) {
    if (event.data) {
        const data = event.data.json();

        const options = {
            body: data.body,
            icon: '/icon-192x192.png',
            badge: '/badge-72x72.png',
            vibrate: [100, 50, 100],
            data: {
                dateOfArrival: Date.now(),
                primaryKey: data.primaryKey
            },
            actions: [
                {
                    action: 'explore',
                    title: 'Lihat FAQ',
                    icon: '/icon-explore.png'
                },
                {
                    action: 'close',
                    title: 'Tutup',
                    icon: '/icon-close.png'
                }
            ]
        };

        event.waitUntil(
            self.registration.showNotification(data.title, options)
        );
    }
});

// Notification click handler
self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    if (event.action === 'explore') {
        event.waitUntil(
            clients.openWindow('/faq')
        );
    } else if (event.action === 'close') {
        // Just close the notification
        return;
    } else {
        // Default action
        event.waitUntil(
            clients.openWindow('/faq')
        );
    }
});

// Message handler for communication with main thread
self.addEventListener('message', function(event) {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }

    if (event.data && event.data.type === 'GET_CACHE_STATUS') {
        caches.open(CACHE_NAME).then(function(cache) {
            return cache.keys();
        }).then(function(keys) {
            event.ports[0].postMessage({
                type: 'CACHE_STATUS',
                cached: keys.length,
                cacheSize: keys.length
            });
        });
    }
});

// Helper function to sync FAQ search data
function syncFAQSearch() {
    return new Promise(function(resolve) {
        // Implement FAQ search sync logic here
        console.log('Service Worker: Syncing FAQ search data');
        resolve();
    });
}

// Update available notification
self.addEventListener('message', function(event) {
    if (event.data.action === 'skipWaiting') {
        self.skipWaiting();
    }
});

// Periodic background sync
self.addEventListener('periodicsync', function(event) {
    if (event.tag === 'faq-update') {
        event.waitUntil(updateFAQCache());
    }
});

// Update FAQ cache function
function updateFAQCache() {
    return caches.open(CACHE_NAME)
        .then(function(cache) {
            return cache.addAll([
                '/api/faq',
                '/faq'
            ]);
        });
}

// Error handling for uncaught errors
self.addEventListener('error', function(event) {
    console.error('Service Worker: Error occurred', event.error);
});

// Handle unhandled promise rejections
self.addEventListener('unhandledrejection', function(event) {
    console.error('Service Worker: Unhandled promise rejection', event.reason);
});

// Network status monitoring
self.addEventListener('online', function(event) {
    console.log('Service Worker: Back online');
    // Sync any pending data
    syncFAQSearch();
});

self.addEventListener('offline', function(event) {
    console.log('Service Worker: Gone offline');
});

// Cache size management
function cleanupCache() {
    caches.open(CACHE_NAME)
        .then(function(cache) {
            return cache.keys();
        })
        .then(function(keys) {
            if (keys.length > 100) { // Limit cache size
                return cache.delete(keys[0]);
            }
        });
}

// Cleanup old entries periodically
setInterval(cleanupCache, 60000); // Every minute

// Performance monitoring
const performance = {
    cacheHits: 0,
    cacheMisses: 0,
    networkRequests: 0,
    errors: 0
};

// Track cache performance
function trackCacheHit() {
    performance.cacheHits++;
}

function trackCacheMiss() {
    performance.cacheMisses++;
}

function trackNetworkRequest() {
    performance.networkRequests++;
}

function trackError() {
    performance.errors++;
}

// Send performance data to main thread
function sendPerformanceData() {
    self.clients.matchAll().then(function(clients) {
        clients.forEach(function(client) {
            client.postMessage({
                type: 'PERFORMANCE_DATA',
                data: performance
            });
        });
    });
}

// Send performance data every 5 minutes
setInterval(sendPerformanceData, 300000);
