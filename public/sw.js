const CACHE_VERSION = 'helpdesk-pdam-v1';
const STATIC_CACHE = `${CACHE_VERSION}-static`;
const OFFLINE_URL = '/offline.html';

const APP_SHELL = [
  OFFLINE_URL,
  '/manifest.json',
  '/icons/icon-192x192.png',
  '/icons/icon-512x512.png',
  '/images/logopdam.jpg',
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(STATIC_CACHE).then((cache) => cache.addAll(APP_SHELL)).then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(
        keys
          .filter((key) => key.startsWith('helpdesk-pdam-') && key !== STATIC_CACHE)
          .map((key) => caches.delete(key))
      )
    ).then(() => self.clients.claim())
  );
});

function isStaticAsset(url) {
  return (
    url.pathname.startsWith('/build/') ||
    url.pathname.startsWith('/icons/') ||
    url.pathname.startsWith('/images/') ||
    url.pathname.startsWith('/css/') ||
    url.pathname.startsWith('/js/') ||
    /\.(css|js|png|jpg|jpeg|svg|webp|woff2?|ttf)$/.test(url.pathname)
  );
}

self.addEventListener('fetch', (event) => {
  const { request } = event;
  if (request.method !== 'GET') return;

  const url = new URL(request.url);
  if (url.origin !== self.location.origin) return;

  // Never cache authenticated/data endpoints (dashboard, tiket, chat, storage foto, dsb).
  if (url.pathname.startsWith('/storage/')) return;

  if (isStaticAsset(url)) {
    // Cache-first for the app shell / compiled assets.
    event.respondWith(
      caches.match(request).then((cached) => {
        if (cached) return cached;
        return fetch(request).then((response) => {
          const copy = response.clone();
          caches.open(STATIC_CACHE).then((cache) => cache.put(request, copy));
          return response;
        });
      })
    );
    return;
  }

  if (request.mode === 'navigate') {
    // Network-first for pages; fall back to offline page if no connection.
    event.respondWith(
      fetch(request).catch(() => caches.match(OFFLINE_URL))
    );
  }
});
