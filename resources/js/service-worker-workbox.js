"use strict";
// @see https://developer.chrome.com/docs/workbox/service-worker-overview/
// @see https://web.dev/offline-cookbook/
// @see https://www.thinktecture.com/pwa/push-api/
// @see https://web.dev/push-notifications-overview/
// @see https://web.dev/service-worker-lifecycle/
// @see https://web.dev/learn/pwa/installation/
// @see https://developer.mozilla.org/en-US/docs/Web/API/Service_Worker_API/Using_Service_Workers/

const SW_VERSION = 'v1.0.2';
const CACHE_PREFIX = `app`;

importScripts('https://storage.googleapis.com/workbox-cdn/releases/6.5.4/workbox-sw.js');

if (workbox) {
    console.info(`Yay! Workbox is loaded 🎉 ⚡️`);
} else {
    console.error(`Boo! Workbox didn't load 😬 🤯`);
}

// Force production builds
workbox.setConfig({
    debug: false,
});
self.__WB_DISABLE_DEV_LOGS = true;

const { core, navigationPreload, strategies, routing, precaching, expiration, streams, cacheableResponse, broadcastUpdate, googleAnalytics } = workbox;

const { cacheNames, setCacheNameDetails, clientsClaim } = core;
const { NavigationRoute, registerRoute } = routing;
const { matchPrecache, precacheAndRoute, getCacheKeyForURL, createHandlerBoundToURL } = precaching;
const { ExpirationPlugin, CacheExpiration } = expiration;
const composeStrategies = streams.strategy;
const { CacheableResponsePlugin } = cacheableResponse;
const { BroadcastUpdatePlugin } = broadcastUpdate;
const { CacheFirst, NetworkFirst, StaleWhileRevalidate } = strategies;

// Enable navigation preload
//navigationPreload.enable();

// Força o service worker em espera, a se tornar o ativo sem ter que
// fechar todas as guias abertas do site no navegador.
//
// Instrui o service worker mais recente a assumir o controle de todos os clientes assim que for ativado.
// @see https://developers.google.com/web/fundamentals/primers/service-workers/lifecycle
self.skipWaiting();

// Garante que o novo service worker no evento de [activate] assuma
// imediatamente o controle do cliente atual e de todos os outros
// clientes ativos(guias abertas do navegador).
//
// Instrui o service worker mais recente a ativar assim que entra na fase de espera.
clientsClaim();

// <prefix>-<cache-id>-<suffix>
setCacheNameDetails({
    prefix: CACHE_PREFIX,
    suffix: SW_VERSION,
    precache: 'precache',
    runtime: 'runtime',
    googleAnalytics: 'ga',
});

const siteCaches = {
    assets: {name: `${CACHE_PREFIX}-siteAssets-${SW_VERSION}`},
    images: {name: `${CACHE_PREFIX}-siteImages-${SW_VERSION}`},
    fonts: {name: `${CACHE_PREFIX}-siteFonts-${SW_VERSION}`},
    appShell: {name: `${CACHE_PREFIX}-appShell-${SW_VERSION}`},
    googleWebfonts: {name: `${CACHE_PREFIX}-googleWebfonts-${SW_VERSION}`},

    precache: {name: cacheNames.precache},
    runtime: {name: cacheNames.runtime},
    googleAnalytics: {name: cacheNames.googleAnalytics},
};

// workbox-precaching does all of this during the service worker's install event.
//
// workbox-precaching will set up the install and activate listeners for you.
//
// The response strategy used in this route is cache-first:
// the precached response will be used, unless that cached
// response is not present (due to some unexpected error),
// in which case a network response will be used instead.
precacheAndRoute(self.__WB_MANIFEST || [], {
    cleanUrls: false,
});

// =========
// APP SHELL
// =========
// @see https://developer.chrome.com/docs/workbox/faster-multipage-applications-with-streams/

const shellContentStrategy = new StaleWhileRevalidate({
    cacheName: siteCaches.appShell.name,
    plugins: [
        new BroadcastUpdatePlugin({
            headersToCheck: ['Last-Modified'],
        }),
        new ExpirationPlugin({
            maxEntries: 50,
        }),
        // {
        //     // NOTE: This callback will never be run if navigation
        //     // preload is not supported, because the navigation
        //     // request is dispatched while the service worker is
        //     // booting up. This callback will only run if navigation
        //     // preload is _not_ supported.
        //     requestWillFetch: ({request}) => {
        //         const headers = new Headers();

        //         // If the browser doesn't support navigation preload, we need to
        //         // send a custom `X-Content-Mode` header for the back end to use
        //         // instead of the `Service-Worker-Navigation-Preload` header.
        //         headers.append('X-Content-Mode', 'Navigation-Preload');

        //         // Send the request with the new headers.
        //         return new Request(request.url, {
        //             method: 'GET',
        //             headers
        //         });
        //     },

        //     // What to do if the request fails.
        //     handlerDidError: async ({request}) => {
        //         return await matchPrecache('/offline.html');
        //     },
        // },
    ],
});

// Concatenates precached partials with the content partial
// obtained from the network (or its fallback response).
const navigationAppShellHandler = composeStrategies([
    // Get the precached header markup.
    () => matchPrecache('/app-shell/partial-header.html'),

    // Get the content partial from the network.
    ({request, event, url}) => {
        return shellContentStrategy.handle({
            request: new Request(`${request.url}/index.content.html?content=true`),
            event: event,
        });
    },

    // Get the precached footer markup.
    () => matchPrecache('/app-shell/partial-footer.html'),
]);

registerRoute(({ url, request }) => {
    return  request.mode === 'navigate' &&
            url.hostname === location.hostname &&
            url.pathname.match(/^\/(?:posts|blog)\/([\w-]+\/?)$/);
}, navigationAppShellHandler);

// ================
// OTHER PAGE CACHE
// ================
// - / - home
// - /blog
// - /hi
const pagesContentStrategy = new NetworkFirst({
    cacheName: siteCaches.appShell.name,
    plugins: [
        new CacheableResponsePlugin({
            statuses: [0, 200],
            // headers: {
            //     'X-Is-Cacheable': 'true',
            // },
        }),
    ],
});

// [HOME]
registerRoute(({ url, request }) =>
    request.mode === 'navigate' && url.pathname.match(/^\/$/),
    composeStrategies([
        // Get the precached header markup.
        () => matchPrecache('/app-shell/partial-header.html'),

        // Get the content partial from the network.
        ({request, event, url}) => pagesContentStrategy.handle({
            request: new Request(`${request.url}index.content.html`),
            event: event,
        }),

        // Get the precached footer markup.
        () => matchPrecache('/app-shell/partial-footer.html'),
    ])
);

// // Create a new navigation route that uses the Network-first, falling back to
// // cache strategy for navigation requests with its own cache. This route will be
// // handled by navigation preload. The NetworkOnly strategy will work as well.
// registerRoute(
//     new NavigationRoute(
//         new NetworkFirst({
//             cacheName: 'navigations'
//         })
//     )
// );

registerRoute(
    new RegExp('/site/.+\.(?:css|js)\?id=\\w{32}$'),
    new CacheFirst({
        fetchOptions: {
            cache: 'default',
        },
        cacheName: siteCaches.assets.name,
        plugins: [
            // With this, the Plugin will be added to this route.
            // After a cached response is used or a new request is added to the cache,
            // the plugin will look at the configured cache and ensure that the number
            // of cached entries doesn’t exceed the limit. If it does, the oldest entries will be removed.
            //
            // The plugin will check and remove entries after each request or cache update.
            new ExpirationPlugin({
                maxEntries: 20,
                // One month
                maxAgeSeconds: 2628000
            })
        ]
    })
);

registerRoute(
    ({url, request, event}) => {
        return  ['image'].includes(request.destination) &&
                url.hostname === self.location.hostname &&
                /^\/(?:images|storage\/images)\/(.+)$/.test(url.pathname);
    },
    new CacheFirst({
        cacheName: siteCaches.images.name,
        plugins: [
            new ExpirationPlugin({
                maxEntries: 50,
                // Cache for a maximum of a week
                // maxAgeSeconds: 7 * 24 * 60 * 60,
                // Don't keep any entries for more than 30 days
                maxAgeSeconds: 30 * 24 * 60 * 60,
                // Remove cached images before purging other caches
                // Automatically cleanup if quota is exceeded
                purgeOnQuotaError: true
            }),
            // If you use one of Workbox's built-in strategies without explicitly configuring a cacheableResponse.CacheableResponsePlugin,
            // the following default criteria is used to determine whether a response received from the network should be cached:
            //
            // - staleWhileRevalidate and networkFirst: Responses with a status of 0 (i.e. opaque responses) or 200 are considered cacheable.
            // - cacheFirst: Responses with a status of 200 are considered cacheable.
            //
            // By default, response headers are not used to determine cacheability.
            new CacheableResponsePlugin({
                statuses: [0, 200],
                // headers: {
                //     'X-Is-Cacheable': 'true',
                // },
            }),
        ],
    }),
);

registerRoute(
    ({url, request, event}) => {
        return  ['font'].includes(request.destination) &&
                url.hostname === self.location.hostname &&
                /^\/(?:site\/fonts)\/(.+)$/.test(url.pathname);
    },
    new CacheFirst({
        cacheName: siteCaches.fonts.name,
    }),
);

// Cache the Google Fonts webfont files with a cache first strategy for 1 year!
registerRoute(
    // ({url}) => url.origin === 'https://fonts.googleapis.com',
    /^https:\/\/fonts\.googleapis\.com/,
    new CacheFirst({
        cacheName: siteCaches.googleWebfonts.name,
        plugins: [
            new ExpirationPlugin({
                maxEntries: 60,
                maxAgeSeconds: 60 * 60 * 24 * 365,
            }),
        ],
    }),
);

self.addEventListener('activate', (event) => {
    console.info('[WORKER] "activate" event in progress.');

    // Tell the active service worker to take control of the page immediately
    self.clients.claim();

    async function cleanupOldCaches() {
        // Keep caches supported by the current version of the service worker
        const cachesToKeep = Object.values(siteCaches).map(({ name }) => name);

        const allCaches = await caches.keys();
        const cachesToCleanup = allCaches.filter((cache) => ! cachesToKeep.includes(cache));

        for (const cacheToCleanup of cachesToCleanup) {
            // Delete the cache
            await caches.delete(cacheToCleanup);

            // Delete the IDB cache expiration informations used by Workbox.
            // See this comment on why we need to set "maxEntries" to 1:
            // https://github.com/GoogleChrome/workbox/issues/2234
            const cacheExpiration = new CacheExpiration(cacheToCleanup, { maxEntries: 1 });
            cacheExpiration.delete();
        }
    }

    // Keep the service worker alive until all caches are deleted.
    event.waitUntil(cleanupOldCaches());
});

// @see https://web.dev/push-notifications-overview/
self.addEventListener('push', (event) => {
    if (! (self.Notification && self.Notification.permission === 'granted')) {
        // notifications aren't supported or permission not granted!
        return;
    }

    // console.warn('Received a push message', event);

    if (! event.data) return;

    const payload = event.data ? event.data.json() : {};

    event.waitUntil(self.registration.showNotification(payload.title, {
        requireInteraction: true,
        body: payload.body,
        icon: '/images/favicons/favicon-192x192.png',
        // actions: payload.actions,
        data: payload.data,
        vibrate: [300, 100, 400], // Vibrate 300ms, pause 100ms, then vibrate 400ms
    }));
});

self.addEventListener('notificationclick', (event) => {
    // console.info('On notification click: ', event);
    event.notification.close();

    const clickedNotification = event.notification;

    // switch (event.action) {
    //     case 'show.post':
    //         console.log('');
    //     break;
    //     default:
    //         console.log(`Unknown action clicked: '${event.action}'`);
    //     break;
    // }

    const winClients = clients;
    const urlToOpen = new URL(clickedNotification.data.link, self.location.origin).href;

    event.waitUntil(clients.matchAll({
        type: "window",
        includeUncontrolled: true,
    }).then((clientList) => {
        for (const client of clientList) {
            if (client.url === urlToOpen) {
                client.postMessage({type: 'RELOAD_WINDOW'});

                return client.focus();
            } else if (client.url === '/' && 'focus' in client) {
                return client.focus();
            }
        }

        return winClients.openWindow(urlToOpen);
    }));
});

self.addEventListener('message', (event) => {
    // console.info(`The client sent me a message:`, event.data);
    // event.source.postMessage('Hi client');

    const { type, payload } = event.data;

    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }

    if (event.data && event.data.type === 'GET_VERSION') {
        // @see https://developer.mozilla.org/pt-BR/docs/Web/API/MessageChannel
        const getVersion = async () => {
            event.ports?.length && event.ports[0].postMessage(SW_VERSION);
        };

        event.waitUntil(getVersion());

        self.clients.matchAll({
            type: 'window',
            includeUncontrolled: true,
        }).then((clients) => {
            if (clients && clients.length) {
                // Send a response - the clients
                // array is ordered by last focused
                clients[0].postMessage({
                    version: SW_VERSION,
                });
            }
        });
    }

    if (event.data && (['HI_SERVICE_WORKER'].includes(event.data.type))) {
        self.clients.matchAll({
            type: 'window',
            includeUncontrolled: true,
        }).then((clients) => {
            if (clients && clients.length) {
                // Array is ordered by last focused
                clients[0].postMessage({
                    type: event.data.type,
                });
            }
        });
    }
});

// // Use a stale-while-revalidate strategy for all other requests.
// setDefaultHandler(new StaleWhileRevalidate({fetchOptions: { cache: 'default'}}));

// const FALLBACK_HTML_URL = '/offline-page.html';
// const FALLBACK_IMAGE_URL = '/images/offline-image.png';

// // This "catch" handler is triggered when any of the other routes fail to generate a response.
// setCatchHandler(({ event, request, url }) => {
//     switch (event.request.destination) {
//         case 'document':
//             return caches.match(FALLBACK_HTML_URL);
//         break;

//         case 'image':
//             return caches.match(FALLBACK_IMAGE_URL);
//         break;

//         default:
//             // If we don't have a fallback, just return an error response.
//             return Response.error();
//     }
// });
