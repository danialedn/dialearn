// Cache names
const CACHE_NAME = 'dialearn-v3';
const STATIC_CACHE = 'dialearn-static-v3';
const DYNAMIC_CACHE = 'dialearn-dynamic-v3';
const OFFLINE_CACHE = 'dialearn-offline-v3';

// Files to cache immediately
const STATIC_ASSETS = [
    '/offline.html',
    '/offline-game.html',
    '/offline-questions.html',
    '/js/offline-storage.js',
    '/js/offline-questions.js',
    '/manifest.json'
];

// Dynamic content patterns
const DYNAMIC_PATTERNS = [
    /\/api\/.*$/,
    /\/quiz\/.*$/,
    /\/game\/.*$/
];

// Offline fallback pages
const OFFLINE_FALLBACKS = {
    '/': '/offline.html',
    '/dashboard': '/offline-dashboard.html',
    '/quiz': '/offline-game.html',
    '/questions': '/offline-questions.html'
};

// Install event - cache static assets
self.addEventListener('install', (event) => {
    console.log('[SW] Installing service worker...');
    
    event.waitUntil(
        Promise.all([
            // Cache static assets
            caches.open(STATIC_CACHE).then((cache) => {
                console.log('[SW] Caching static assets');
                return cache.addAll(STATIC_ASSETS);
            }),
            
            // Cache offline fallbacks
            caches.open(OFFLINE_CACHE).then((cache) => {
                console.log('[SW] Caching offline fallbacks');
                return cache.addAll([
                    '/offline.html',
                    '/offline-dashboard.html', 
                    '/offline-game.html',
                    '/offline-questions.html'
                ]);
            })
        ]).then(() => {
            console.log('[SW] Installation complete');
            return self.skipWaiting();
        })
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
    console.log('[SW] Activating service worker...');
    
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== STATIC_CACHE && 
                        cacheName !== DYNAMIC_CACHE && 
                        cacheName !== OFFLINE_CACHE) {
                        console.log('[SW] Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => {
            console.log('[SW] Activation complete');
            return self.clients.claim();
        })
    );
});

// Fetch event - serve from cache first, then network
self.addEventListener('fetch', event => {
    // Skip non-GET requests
    if (event.request.method !== 'GET') return;
    
    // Skip external requests
    if (!event.request.url.startsWith(self.location.origin)) return;

    const url = new URL(event.request.url);
    
    // Skip Laravel API routes and let them go through normally
    if (url.pathname.startsWith('/api/') || 
        url.pathname.startsWith('/login') || 
        url.pathname.startsWith('/register') ||
        url.pathname.startsWith('/logout') ||
        url.pathname.includes('_ignition') ||
        url.pathname.includes('livewire')) {
        return;
    }

    // Only serve offline pages when actually offline
    if (!navigator.onLine && event.request.mode === 'navigate') {
        if (url.pathname.includes('/quiz') || url.pathname.includes('/game')) {
            event.respondWith(caches.match('/offline-game.html'));
            return;
        } else {
            event.respondWith(caches.match('/offline.html'));
            return;
        }
    }

    // For static assets, use cache first strategy
    if (STATIC_ASSETS.includes(url.pathname) || 
        url.pathname.startsWith('/build/') ||
        url.pathname.includes('.css') ||
        url.pathname.includes('.js') ||
        url.pathname.includes('.ico') ||
        url.pathname.includes('.svg') ||
        url.pathname.includes('.png') ||
        url.pathname.includes('.jpg') ||
        url.pathname.includes('.jpeg') ||
        url.pathname.includes('.webp') ||
        url.pathname.includes('.woff') ||
        url.pathname.includes('.woff2') ||
        url.pathname.includes('.ttf') ||
        url.hostname === 'fonts.googleapis.com' ||
        url.hostname === 'fonts.gstatic.com') {
        
        event.respondWith(
            caches.match(event.request)
                .then(cachedResponse => {
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    
                    return fetch(event.request)
                        .then(response => {
                            if (response && response.status === 200) {
                                const responseToCache = response.clone();
                                caches.open(STATIC_CACHE)
                                    .then(cache => {
                                        cache.put(event.request, responseToCache);
                                    });
                            }
                            return response;
                        });
                })
        );
        return;
    }

    // For all other requests (Laravel pages), use network first strategy
    event.respondWith(
        fetch(event.request)
            .then(response => {
                // Cache successful responses
                if (response && response.status === 200 && response.type === 'basic') {
                    const responseToCache = response.clone();
                    caches.open(DYNAMIC_CACHE)
                        .then(cache => {
                            cache.put(event.request, responseToCache);
                        });
                }
                return response;
            })
            .catch(() => {
                // Only return cached version if network fails
                return caches.match(event.request)
                    .then(cachedResponse => {
                        if (cachedResponse) {
                            return cachedResponse;
                        }
                        
                        // Return offline page only as last resort
                        if (event.request.mode === 'navigate') {
                            return caches.match('/offline.html');
                        }
                    });
            })
    );
});

// Background sync for offline actions
self.addEventListener('sync', (event) => {
    console.log('[SW] Background sync triggered:', event.tag);
    
    if (event.tag === 'game-progress-sync') {
        event.waitUntil(syncGameProgress());
    } else if (event.tag === 'user-data-sync') {
        event.waitUntil(syncUserData());
    }
});

// Push notification handler
self.addEventListener('push', (event) => {
    console.log('[SW] Push received:', event);
    
    let data = {};
    if (event.data) {
        data = event.data.json();
    }
    
    const options = {
        body: data.body || 'بازی آموزشی دیابت آماده است!',
        icon: '/icon-192.svg',
        badge: '/favicon.ico',
        vibrate: [200, 100, 200],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: data.primaryKey || 1,
            type: data.type || 'general'
        },
        actions: [
            {
                action: 'explore',
                title: 'شروع بازی',
                icon: '/favicon.svg'
            },
            {
                action: 'close',
                title: 'بستن'
            }
        ]
    };
    
    event.waitUntil(
        self.registration.showNotification(data.title || 'دیالرن', options)
    );
});

// Show offline installation notification
function showOfflineInstallNotification() {
    const options = {
        body: 'اپلیکیشن دیالرن برای استفاده آفلاین نصب شد!',
        icon: '/icon-192.svg',
        badge: '/favicon.ico',
        tag: 'offline-install',
        requireInteraction: true,
        vibrate: [200, 100, 200, 100, 200],
        data: {
            dateOfArrival: Date.now(),
            type: 'offline-install'
        },
        actions: [
            {
                action: 'open-app',
                title: 'باز کردن اپلیکیشن',
                icon: '/favicon.svg'
            },
            {
                action: 'view-features',
                title: 'مشاهده ویژگی‌ها'
            }
        ]
    };
    
    return self.registration.showNotification('نصب آفلاین موفق!', options);
}

// Trigger offline install notification when app is cached
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SHOW_OFFLINE_INSTALL_NOTIFICATION') {
        event.waitUntil(showOfflineInstallNotification());
    }
});

// Notification click handler
self.addEventListener('notificationclick', (event) => {
    console.log('[SW] Notification clicked:', event.action);
    
    event.notification.close();
    
    if (event.action === 'explore') {
        event.waitUntil(
            clients.openWindow('/dashboard')
        );
    } else if (event.action === 'open-app') {
        event.waitUntil(
            clients.openWindow('/dashboard')
        );
    } else if (event.action === 'view-features') {
        event.waitUntil(
            clients.openWindow('/offline.html')
        );
    } else if (event.notification.data && event.notification.data.type === 'offline-install') {
        // Default action for offline install notification
        event.waitUntil(
            clients.openWindow('/dashboard')
        );
    }
});

// Helper functions
function isStaticAsset(request) {
    return request.url.includes('/build/') || 
           request.url.includes('/favicon') ||
           request.url.includes('/manifest.json');
}

function isDynamicContent(request) {
    return DYNAMIC_PATTERNS.some(pattern => pattern.test(request.url));
}

function isAPIRequest(request) {
    return request.url.includes('/api/');
}

// Cache strategies
async function cacheFirst(request, cacheName) {
    try {
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        
        const networkResponse = await fetch(request);
        const cache = await caches.open(cacheName);
        cache.put(request, networkResponse.clone());
        
        return networkResponse;
    } catch (error) {
        console.log('[SW] Cache first failed:', error);
        return getOfflineFallback(request);
    }
}

async function networkFirst(request, cacheName) {
    try {
        const networkResponse = await fetch(request);
        const cache = await caches.open(cacheName);
        cache.put(request, networkResponse.clone());
        
        return networkResponse;
    } catch (error) {
        console.log('[SW] Network first failed, trying cache:', error);
        const cachedResponse = await caches.match(request);
        
        if (cachedResponse) {
            return cachedResponse;
        }
        
        return getOfflineFallback(request);
    }
}

async function staleWhileRevalidate(request, cacheName) {
    const cache = await caches.open(cacheName);
    const cachedResponse = await cache.match(request);
    
    const fetchPromise = fetch(request).then((networkResponse) => {
        cache.put(request, networkResponse.clone());
        return networkResponse;
    }).catch(() => cachedResponse);
    
    return cachedResponse || fetchPromise || getOfflineFallback(request);
}

async function handleAPIRequest(request) {
    const url = new URL(request.url);
    
    // Special handling for questions API endpoints
    if (url.pathname.includes('/api/questions/offline/')) {
        return handleQuestionsAPIRequest(request);
    }
    
    try {
        const networkResponse = await fetch(request);
        
        // Cache successful API responses
        if (networkResponse.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, networkResponse.clone());
        }
        
        return networkResponse;
    } catch (error) {
        console.log('[SW] API request failed:', error);
        
        // Try to return cached API response
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        
        // Return offline API response
        return new Response(JSON.stringify({
            error: 'offline',
            message: 'درخواست در حالت آفلاین قابل دسترسی نیست',
            offline: true
        }), {
            status: 503,
            headers: { 'Content-Type': 'application/json' }
        });
    }
}

// Special handler for questions API requests
async function handleQuestionsAPIRequest(request) {
    const QUESTIONS_CACHE = 'dialearn-questions-v1';
    
    try {
        // Try network first for fresh data
        const networkResponse = await fetch(request);
        
        if (networkResponse.ok) {
            // Cache the response with longer expiry for questions
            const cache = await caches.open(QUESTIONS_CACHE);
            const responseToCache = networkResponse.clone();
            
            // Add timestamp to cached response
            const responseData = await responseToCache.json();
            responseData.cached_at = new Date().toISOString();
            responseData.cache_source = 'network';
            
            const modifiedResponse = new Response(JSON.stringify(responseData), {
                status: networkResponse.status,
                statusText: networkResponse.statusText,
                headers: networkResponse.headers
            });
            
            cache.put(request, modifiedResponse.clone());
            return modifiedResponse;
        }
        
        return networkResponse;
    } catch (error) {
        console.log('[SW] Questions API network failed, trying cache:', error);
        
        // Try cached response
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            console.log('[SW] Serving questions from cache');
            
            // Modify cached response to indicate it's from cache
            const cachedData = await cachedResponse.json();
            cachedData.cache_source = 'cache';
            cachedData.served_offline = true;
            
            return new Response(JSON.stringify(cachedData), {
                status: cachedResponse.status,
                statusText: cachedResponse.statusText,
                headers: cachedResponse.headers
            });
        }
        
        // Return offline fallback for questions
        return new Response(JSON.stringify({
            success: false,
            error: 'offline',
            message: 'سوالات در حالت آفلاین در دسترس نیستند. لطفاً اتصال اینترنت خود را بررسی کنید.',
            offline: true,
            questions: [],
            total_count: 0,
            cache_source: 'offline_fallback'
        }), {
            status: 503,
            headers: { 
                'Content-Type': 'application/json',
                'Cache-Control': 'no-cache'
            }
        });
    }
}

async function getOfflineFallback(request) {
    const url = new URL(request.url);
    const pathname = url.pathname;
    
    // Find appropriate offline fallback
    for (const [pattern, fallback] of Object.entries(OFFLINE_FALLBACKS)) {
        if (pathname.startsWith(pattern)) {
            const cache = await caches.open(OFFLINE_CACHE);
            return cache.match(fallback);
        }
    }
    
    // Default offline page
    const cache = await caches.open(OFFLINE_CACHE);
    return cache.match('/offline.html');
}

// Background sync functions
async function syncGameProgress() {
    try {
        console.log('[SW] Syncing game progress...');
        
        // Get offline game data from IndexedDB
        const offlineData = await getOfflineGameData();
        
        if (offlineData.length > 0) {
            const response = await fetch('/api/sync/game-progress', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ data: offlineData })
            });
            
            if (response.ok) {
                await clearOfflineGameData();
                console.log('[SW] Game progress synced successfully');
            }
        }
    } catch (error) {
        console.log('[SW] Game progress sync failed:', error);
        throw error;
    }
}

async function syncUserData() {
    try {
        console.log('[SW] Syncing user data...');
        
        const offlineData = await getOfflineUserData();
        
        if (offlineData.length > 0) {
            const response = await fetch('/api/sync/user-data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ data: offlineData })
            });
            
            if (response.ok) {
                await clearOfflineUserData();
                console.log('[SW] User data synced successfully');
            }
        }
    } catch (error) {
        console.log('[SW] User data sync failed:', error);
        throw error;
    }
}

// Background sync event listener
self.addEventListener('sync', (event) => {
    console.log('[SW] Background sync triggered:', event.tag);
    
    if (event.tag === 'offline-install-notification') {
        event.waitUntil(handleOfflineInstallSync());
    } else if (event.tag === 'game-progress-sync') {
        event.waitUntil(syncGameProgress());
    } else if (event.tag === 'user-data-sync') {
        event.waitUntil(syncUserData());
    }
});

// Handle offline install notification sync
async function handleOfflineInstallSync() {
    try {
        console.log('[SW] Handling offline install notification sync...');
        
        // Check if we're online
        if (navigator.onLine) {
            // Show the offline installation notification
            await showOfflineInstallNotification();
            
            // Log the installation event
            try {
                await fetch('/api/log/pwa-install', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        type: 'offline-install',
                        timestamp: Date.now(),
                        userAgent: navigator.userAgent
                    })
                });
            } catch (logError) {
                console.log('[SW] Failed to log PWA install:', logError);
            }
        } else {
            // If still offline, schedule another sync
            throw new Error('Still offline, will retry');
        }
    } catch (error) {
        console.log('[SW] Offline install sync failed:', error);
        throw error;
    }
}

// IndexedDB helper functions (simplified)
async function getOfflineGameData() {
    // This would interact with IndexedDB to get offline game data
    return [];
}

async function clearOfflineGameData() {
    // This would clear synced data from IndexedDB
}

async function getOfflineUserData() {
    // This would interact with IndexedDB to get offline user data
    return [];
}

async function clearOfflineUserData() {
    // This would clear synced data from IndexedDB
}