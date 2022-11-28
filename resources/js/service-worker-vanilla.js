'use strict';

const SW_VERSION = '1.0.1';
const CACHE_PREFIX = `offline`;

const CACHE_NAME = `${CACHE_PREFIX}?v=${SW_VERSION}`;
const CACHE_WHITELIST = [CACHE_NAME];

DummyUrlsToCache

self.addEventListener('install', event => {
    console.info('[WORKER] Install event in progress.');

    self.skipWaiting();

    // waitUntil() - Garante que o service worker não seja
    // instalado até que o código interno tenha ocorrido com êxito.
    event.waitUntil((async () => {
        const cache = await caches.open(CACHE_NAME);

        await cache.addAll(urlsToCache);

        console.info('[WORKER] Complete installation.');
    })());
});

self.addEventListener('activate', event => {
    console.info('[WORKER] Activate event in progress.');

    // Tell the active service worker to take control of the page immediately
    self.clients.claim();

    // Delete any caches that aren't in CACHE_WHITELIST
    event.waitUntil(caches.keys().then(cacheNames => {
        return  Promise.all(
                    cacheNames.map(cacheName => {
                        if (! CACHE_WHITELIST.includes(cacheName)) {
                            console.warn(String.raw`[WORKER] Clearing Old Cache "${cacheName}"`);

                            return caches.delete(cacheName);
                        }
                    })
                );
        })
        .then(() => {
            // We successfully deleted all the obsolete caches
            console.info('[WORKER] Activate completed.');
        })
    );
});

self.addEventListener('fetch', event => {
    if (event.request.method !== 'GET') {
        return
    }

    const req = event.request;
    const url = new URL(req.url);

    console.warn('[WORKER] Fetch event for:', req.url);

    // We only want to call event.respondWith()
    // if this is a navigation request for an HTML page
    if (req.mode !== 'navigate') {
        // Not a page navigation, bail
        return;
    } else {
        console.info('Navigate request', req);

        if (registration.waiting) {
            registration.waiting.postMessage('SKIP_WAITING');
        }

        if (req.method !== 'GET') {
            console.error('[WORKER] Fetch event ignored ❌', req.method, req.url);
            return;
        }
    }

    if (url.origin === location.origin && /^\/(?:posts|blog)\/.+/.test(url.pathname)) {
        event.respondWith((async () => {
            // We're going to build a single request from multiple parts
            const parts = [
                caches.match('/app-shell/partial-header.html'),
                // Network falling back to cache strategy
                fetch(`${url.pathname}/index.content.html`).catch(() => caches.match('article-offline.html')),
                // fetch(`${url.pathname}/index.content.html`).catch(() => caches.open(CACHE_NAME).then((cache) => cache.match('article-offline.html'))),
                caches.match('/app-shell/partial-footer.html'),
            ];

            // Merge them all together
            const { done, response } = await mergeResponses(parts);

            // Wait until the stream is complete
            event.waitUntil(done);

            // Return the merged response
            return response;
        })());
    }

    event.respondWith(
        // Try the cache
        caches
            .match(req, { ignoreSearch: true })
            .then((response) => {
                if (response) {
                    console.info('[WORKER] Fetch event', '(cached)', req.url);
                    return response;
                }

                console.info('[WORKER] Fetch event', '(network)', req.url);

                // Fallback to network
                return fetch(req.clone(), {cache: 'default'}).then((response) => {

                    // Check if we received a valid response
                    if (! response ||
                        response.status !== 200 ||
                        response.type !== 'basic') {
                            return response;
                    }

                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(req, response.clone());
                    })
                    .then(function() {
                        console.info('[WORKER] Fetch response stored in cache!', req.url);
                    });

                    return response;

                }).catch(() => {
                    // If both fail, show a generic fallback:
                    console.error('[WORKER] Fetch request failed in both cache and network');

                    return new Response('<h1>Service Unavailable</h1>', {
                        status: 503,
                        statusText: 'Service Unavailable',
                        headers: new Headers({
                            'Content-Type': 'text/html'
                        })
                    });
                });
            })
    );

    event.respondWith((async () => {
        return await caches.match(req) || fetch(req);
    })());

    if (req.mode === 'navigate') {
        event.respondWith((async () => {
            // Optional: Normalize the incoming URL by removing query parameters.
            // Instead of https://example.com/page?key=value,
            // use https://example.com/page when reading and writing to the cache.
            // For static HTML documents, it's unlikely your query parameters will
            // affect the HTML returned. But if you do use query parameters that
            // uniquely determine your HTML, modify this code to retain them.
            const normalizedUrl = new URL(event.request.url);
            normalizedUrl.search = '';

            // Create promises for both the network response,
            // and a copy of the response that can be used in the cache.
            const fetchResponse = fetch(normalizedUrl);
            const fetchResponseClone = fetchResponse.then(r => r.clone());

            // event.waitUntil() ensures that the service worker is kept alive
            // long enough to complete the cache update.
            event.waitUntil((async () => {
                const cache = await caches.open(CACHE_NAME);

                await cache.put(normalizedUrl, await fetchResponseClone);
            })());

            // Prefer the cached response, falling back to the fetch response.
            return (await caches.match(normalizedUrl)) || fetchResponse;
        })());
    }

});

self.addEventListener('message', async (event) => {
    if (! event.data) return;

    console.info('[WORKER] Message');

    // call in browser => registration.waiting.postMessage('FORCE_ACTIVATE')
    if (event.data === 'FORCE_ACTIVATE') {
        await self.skipWaiting();
        await self.clients.claim();

        const clients = await self.clients.matchAll({type: 'window', includeUncontrolled: true})

        clients.forEach((client) => {
            client.navigate(client.url)
            // Dispatch event to all clients browsers opened
            client.postMessage('RELOAD_WINDOW')
        })
    }
});
