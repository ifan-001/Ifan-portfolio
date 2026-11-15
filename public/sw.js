// Hasnain Portfolio PWA Service Worker
// Bump SW_VERSION on every deployment that changes cached static assets.
const SW_VERSION = '2.2.0';
const CACHE_NAME = 'portfolio-static-v' + SW_VERSION;
const STATIC_ASSETS = [
    '/css/portfolio.css',
    '/css/admin.css',
    '/css/premium.css',
    '/js/premium.js',
    '/js/portfolio.js',
    '/js/admin-in-place.js',
    '/js/qrcode.min.js',
    '/icons/icon.svg'
];

// Built in, so it never depends on a network request (some hosts challenge non-page requests)
const OFFLINE_HTML = '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Offline</title><style>body{margin:0;min-height:100vh;display:grid;place-items:center;background:#0B1120;color:#E2E8F0;font-family:system-ui,sans-serif;text-align:center;padding:24px}h1{font-size:1.3rem;margin:0 0 8px}p{color:#94A3B8;margin:0 0 18px}button{background:#3B82F6;color:#fff;border:0;border-radius:12px;padding:12px 22px;font-size:1rem}</style></head><body><div><h1>You are offline</h1><p>Check your internet connection and try again.</p><button onclick="location.reload()">Retry</button></div></body></html>';
const STATIC_EXTENSIONS = /\.(?:css|js|png|jpe?g|webp|gif|avif|svg|ico|woff2?)$/i;

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            // Tolerate a missing asset so one 404 cannot block installation
            .then((cache) => Promise.all(STATIC_ASSETS.map((u) => cache.add(u).catch(() => null))))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k))))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const request = event.request;
    const url = new URL(request.url);

    // Never touch non-GET requests or cross-origin requests
    if (request.method !== 'GET' || url.origin !== self.location.origin) {
        return;
    }

    // HTML pages (public, admin, feed, blog...) are NEVER cached: they can contain
    // admin UI, CSRF tokens and private data. Network only, offline page on failure.
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request).catch(() => new Response(OFFLINE_HTML, { status: 503, headers: { 'Content-Type': 'text/html; charset=utf-8' } }))
        );
        return;
    }

    // API, admin, auth and upload endpoints always go to the network
    if (/^\/(admin|portal|api|contact|track|feed|resume\/download)/.test(url.pathname)) {
        return;
    }

    // Static assets only: stale-while-revalidate, successful responses only
    if (STATIC_EXTENSIONS.test(url.pathname)) {
        event.respondWith(
            caches.open(CACHE_NAME).then((cache) =>
                cache.match(request).then((cached) => {
                    const network = fetch(request).then((response) => {
                        if (response && response.status === 200 && response.type === 'basic') {
                            cache.put(request, response.clone());
                        }
                        return response;
                    }).catch(() => cached);
                    return cached || network;
                })
            )
        );
    }
});

// Push Event: handle server-triggered push notifications (only fires once server push is configured)
self.addEventListener('push', (event) => {
    let payload = {
        title: 'Portfolio Notification',
        body: 'New activity on your portfolio platform.',
        url: '/admin'
    };

    if (event.data) {
        try {
            payload = Object.assign(payload, event.data.json());
        } catch (e) {
            payload.body = event.data.text();
        }
    }

    // Only allow same-origin relative targets
    const target = typeof payload.url === 'string' && payload.url.startsWith('/') && !payload.url.startsWith('//')
        ? payload.url : '/admin';

    event.waitUntil(
        self.registration.showNotification(payload.title, {
            body: payload.body,
            icon: '/icons/icon-192.png',
            badge: '/icons/icon-192.png',
            vibrate: [100, 50, 100],
            data: { url: target },
            actions: [{ action: 'open', title: 'Open Console' }]
        })
    );
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const targetUrl = (event.notification.data && event.notification.data.url) || '/admin';

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
