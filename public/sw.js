// Hasnain Portfolio Management PWA Service Worker
const CACHE_NAME = 'portfolio-admin-shell-v1.0.0';
const STATIC_ASSETS = [
    '/css/portfolio.css',
    '/js/portfolio.js',
    '/js/qrcode.min.js',
    '/manifest.json',
    '/icons/icon.svg',
    '/offline.html'
];

// Install: Cache App Shell
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(STATIC_ASSETS);
        }).then(() => self.skipWaiting())
    );
});

// Activate: Clean old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k))
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch: Strict security-conscious strategy
// NEVER cache private admin pages, API calls, or sensitive message data
self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    // Bypass cache completely for:
    // 1. Admin authenticated routes
    // 2. API requests
    // 3. POST / PUT / DELETE mutations
    if (
        event.request.method !== 'GET' ||
        url.pathname.startsWith('/admin') ||
        url.pathname.startsWith('/portal') ||
        url.pathname.startsWith('/api') ||
        url.pathname.startsWith('/contact')
    ) {
        event.respondWith(
            fetch(event.request).catch(() => {
                // Return offline page only if user is navigating HTML
                if (event.request.mode === 'navigate') {
                    return caches.match('/offline.html');
                }
                return new Response(JSON.stringify({ error: 'Network unavailable' }), {
                    status: 503,
                    headers: { 'Content-Type': 'application/json' }
                });
            })
        );
        return;
    }

    // Static assets: Stale-while-revalidate
    event.respondWith(
        caches.match(event.request).then((cachedResponse) => {
            const fetchPromise = fetch(event.request).then((networkResponse) => {
                if (networkResponse && networkResponse.status === 200) {
                    const responseToCache = networkResponse.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseToCache);
                    });
                }
                return networkResponse;
            }).catch(() => {
                if (event.request.mode === 'navigate') {
                    return caches.match('/offline.html');
                }
            });

            return cachedResponse || fetchPromise;
        })
    );
});

// Push Event: Handle server-triggered push notifications
self.addEventListener('push', (event) => {
    let payload = {
        title: 'Portfolio Notification',
        body: 'New activity on your portfolio platform.',
        url: '/admin'
    };

    if (event.data) {
        try {
            payload = event.data.json();
        } catch (e) {
            payload.body = event.data.text();
        }
    }

    const options = {
        body: payload.body,
        icon: '/icons/icon-192.png',
        badge: '/icons/icon-192.png',
        vibrate: [100, 50, 100],
        data: {
            url: payload.url || '/admin'
        },
        actions: [
            { action: 'open', title: 'Open Console' }
        ]
    };

    event.waitUntil(
        self.registration.showNotification(payload.title, options)
    );
});

// Notification Click: Focus existing window or open new
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const targetUrl = event.notification.data?.url || '/admin';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            for (const client of clientList) {
                if (client.url.includes('/admin') && 'focus' in client) {
                    client.navigate(targetUrl);
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
