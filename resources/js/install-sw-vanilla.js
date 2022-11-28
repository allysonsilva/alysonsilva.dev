if ('serviceWorker' in navigator &&
    'caches' in window &&
    'indexedDB' in window) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/service-worker.js', {scope: '/', updateViaCache: 'imports',})
                .then(registration => {
                    // registration.installing; // the installing worker, or undefined
                    // registration.waiting; // the waiting worker, or undefined
                    // registration.active; // the active worker, or undefined

                    let serviceWorker;

                    if (registration.installing) {
                        serviceWorker = registration.installing;
                    } else if (registration.waiting) {
                        serviceWorker = registration.waiting;
                    } else if (registration.active) {
                        serviceWorker = registration.active;
                    }

                    if (serviceWorker) {
                        if (serviceWorker.state === 'activated') {
                            console.info('Service worker activated!');
                        }

                        serviceWorker.addEventListener('statechange', (statechangeEvent) => {
                            console.warn('statechangeEvent.target.state', statechangeEvent.target.state)
                        });
                    }

                    // Service Worker update detected!
                    registration.addEventListener('updatefound', (updatefoundEvent) => {
                        // A wild service worker has appeared in registration.installing!
                        const installingWorker = registration.installing;

                        installingWorker.state;
                        // "installing" - the install event has fired, but not yet complete
                        // "installed"  - install complete
                        // "activating" - the activate event has fired, but not yet complete
                        // "activated"  - fully active
                        // "redundant"  - discarded. Either failed install, or it's been replaced by a newer version

                        // our new instance is visible under installing property, because it is in 'installing' state
                        // let's wait until it changes its state
                        // wait until the new Service worker is actually installed (ready to take over)
                        installingWorker.addEventListener('statechange', (statechangeEvent) => {
                            switch (installingWorker.state) {
                                case 'installed':
                                    if (navigator.serviceWorker.controller) {
                                        // At this point, the old content will have been purged and the fresh content will have been added to the cache.
                                        // It's the perfect time to display a "New content is available; please refresh." message in the page's interface.
                                        console.info('New or updated content is available.');
                                    } else {
                                        // At this point, everything has been precached.
                                        // It's the perfect time to display a "Content is cached for offline use." message.
                                        console.info('Content is now available offline!');
                                    }
                                    break;

                                case 'redundant':
                                    console.error('The installing service worker became redundant.');
                                    break;
                            }
                        });
                    });

                    console.info(`Service Worker registered! 😎 Scope: ${registration.scope}`);
                })
                .catch(err => {
                    console.error('Error during service worker registration:', err);
                });

            navigator.serviceWorker.addEventListener('controllerchange', () => {
                // This fires when the service worker controlling this page
                // changes, eg a new worker has skipped waiting and become the new active worker.
                window.location.reload();
            });

            // Listen event from service worker
            navigator.serviceWorker.addEventListener('message', (event) => {
                if (! event.data) return;

                // {event} is a MessageEvent object
                console.info(`The service worker sent me a message: ${event.data}`);

                switch (event.data) {
                    case 'RELOAD_WINDOW':
                        window.location.reload();
                        break;
                    default:
                        // NOOP
                        break;
                }
            });

            navigator.serviceWorker.ready.then(registration => {
                // navigator.serviceWorker.controller.postMessage('Hi service worker');
                registration.active.postMessage('Hi service worker');
            });
        });
}
