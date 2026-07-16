// LAMAA service worker — enables installability and basic offline support.
// Strategy: cache the app shell + static assets at install time (cache-first),
// use network-first for everything else so data (orders, prices) stays fresh
// online but still falls back to cache when offline. Bump CACHE on deploy to
// invalidate old caches.
const CACHE = 'lamaa-v2';
const OFFLINE_URL = '/offline.html';
const APP_SHELL = [
    '/',
    '/manifest.json',
    '/offline.html',
    '/images/lamaa-logo.png',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
];

self.addEventListener('install', (e) => {
    e.waitUntil(
        caches.open(CACHE).then((c) => c.addAll(APP_SHELL)).then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (e) => {
    e.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter((k) => k !== CACHE).map((k) => caches.delete(k)))
        ).then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (e) => {
    if (e.request.method !== 'GET') return; // never cache POST/PUT/DELETE (orders, payments, etc.)

    e.respondWith(
        fetch(e.request)
            .then((response) => {
                // Only cache successful, same-origin responses.
                if (response.ok && e.request.url.startsWith(self.location.origin)) {
                    const clone = response.clone();
                    caches.open(CACHE).then((c) => c.put(e.request, clone));
                }
                return response;
            })
            .catch(async () => {
                const cached = await caches.match(e.request);
                if (cached) return cached;
                if (e.request.mode === 'navigate') return caches.match(OFFLINE_URL);
                return Response.error();
            })
    );
});
