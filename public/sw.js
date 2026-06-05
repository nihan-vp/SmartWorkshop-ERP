const CACHE_NAME = 'suhaim-soft-workshop-v1';
const ASSETS_TO_CACHE = [
  '/',
  '/favicon.svg',
  '/manifest.json'
];

// Install Event
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      return cache.addAll(ASSETS_TO_CACHE);
    })
  );
  self.skipWaiting();
});

// Activate Event
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys => {
      return Promise.all(
        keys.filter(key => key !== CACHE_NAME).map(key => caches.delete(key))
      );
    })
  );
  self.clients.claim();
});

// Fetch Event - Network-first for views, cache-first for static assets
self.addEventListener('fetch', event => {
  // Only handle local GET requests
  if (event.request.method !== 'GET' || !event.request.url.startsWith(self.location.origin)) {
    return;
  }

  // Define static asset patterns
  const isStaticAsset = event.request.url.match(/\.(css|js|png|jpg|jpeg|svg|woff2|woff|ttf|ico|json)$/) ||
                        event.request.url.includes('fonts.googleapis.com') ||
                        event.request.url.includes('fonts.gstatic.com') ||
                        event.request.url.includes('cdn.tailwindcss.com');

  if (isStaticAsset) {
    // Cache First with Network Fallback
    event.respondWith(
      caches.match(event.request).then(cachedResponse => {
        if (cachedResponse) {
          return cachedResponse;
        }
        return fetch(event.request).then(networkResponse => {
          if (networkResponse.status === 200) {
            const responseClone = networkResponse.clone();
            caches.open(CACHE_NAME).then(cache => cache.put(event.request, responseClone));
          }
          return networkResponse;
        }).catch(() => new Response('Asset unavailable offline', { status: 503 }));
      })
    );
  } else {
    // Network First with Cache Fallback for dynamic pages
    event.respondWith(
      fetch(event.request)
        .then(networkResponse => {
          // Cache the offline shell/homepage if it's the root path
          if (networkResponse.status === 200 && new URL(event.request.url).pathname === '/') {
            const responseClone = networkResponse.clone();
            caches.open(CACHE_NAME).then(cache => cache.put(event.request, responseClone));
          }
          return networkResponse;
        })
        .catch(() => {
          return caches.match(event.request).then(cachedResponse => {
            if (cachedResponse) {
              return cachedResponse;
            }
            // Fallback content when totally offline and no cache matches
            return new Response(
              `<!DOCTYPE html>
              <html lang="en">
              <head>
                  <meta charset="UTF-8">
                  <meta name="viewport" content="width=device-width, initial-scale=1.5">
                  <title>Offline — Suhaim Soft</title>
                  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
                  <style>
                      body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; padding: 20px; text-align: center; }
                      .card { background: white; padding: 40px; border-radius: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); max-width: 400px; border: 1px solid #e2e8f0; }
                      h1 { font-size: 24px; margin: 0 0 10px 0; color: #0f172a; }
                      p { font-size: 14px; color: #64748b; line-height: 1.6; margin: 0 0 24px 0; }
                      .btn { display: inline-block; background: #1d4ed8; color: white; padding: 12px 24px; border-radius: 12px; font-weight: 600; text-decoration: none; font-size: 14px; transition: background 0.2s; }
                      .btn:hover { background: #1e40af; }
                  </style>
              </head>
              <body>
                  <div class="card">
                      <h1>You are offline</h1>
                      <p>Check your internet connection and try reloading the page.</p>
                      <a href="/" class="btn">Retry Connection</a>
                  </div>
              </body>
              </html>`,
              {
                headers: { 'Content-Type': 'text/html' }
              }
            );
          });
        })
    );
  }
});
