import { Workbox } from 'workbox-window/Workbox.mjs';

const minimumRequirementsCheck = () => {
    if (! ('serviceWorker' in navigator)) {
        throw new Error('No Service Worker support!')
    }

    // if (! ('Notification' in window)) {
    //     throw new Error('No Notification Support!')
    // }

    // if (! window.Notification || ! Notification.requestPermission) {
    //     console.error('This browser does not support desktop notification 😔')
    //     return
    // }
}

window.addEventListener('online', (event) => {
    // Resync data with server.
    console.info('You are online');
}, false);

window.addEventListener('offline', function (e) {
    // Queue up events for server.
    console.warn('You are offline');
}, false);

minimumRequirementsCheck()

try {

    if ('serviceWorker' in navigator &&
        'caches' in window &&
        'indexedDB' in window) {

            function urlBase64ToUint8Array(base64String) {
                let padding = '='.repeat((4 - (base64String.length % 4)) % 4);
                let base64 = (base64String + padding)
                                .replace(/\-/g, '+')
                                .replace(/_/g, '/');

                let rawData = window.atob(base64);
                let outputArray = new Uint8Array(rawData.length);

                for (let i = 0; i < rawData.length; ++i) {
                    outputArray[i] = rawData.charCodeAt(i);
                }

                return outputArray;
            }

            const VAPID_PUBLIC_KEY = process.env.MIX_VAPID_PUBLIC_KEY;

            async function enableNotifications(pushManager) {
                const swalActivatedzNotificationSuccessfully = () => {
                    Swal.mixin({customClass: {popup: 'popup-request-notification'}})
                        .fire({
                            icon: 'success',
                            position: 'top-end',
                            text: 'Agora você receberá notificações sempre que um novo post for criado 🎉',
                            showCloseButton: true,
                            showConfirmButton: false,
                        });
                }

                try {
                    const permission = await Notification.requestPermission()

                    if (permission !== 'granted') {
                        console.warn('User blocked notifications 😟');

                        throw new Error('We weren\'t granted permission.');
                    }

                    if (permission === 'granted' && (! window.PushManager)) {
                        swalActivatedzNotificationSuccessfully()

                        return;
                    }

                    // https://web.dev/push-notifications-in-all-modern-browsers
                    // https://caniuse.com/push-api
                    // https://developer.mozilla.org/en-US/docs/Web/API/PushEvent/PushEvent#browser_compatibility
                    if (! ('PushManager' in window)) {
                        Swal.mixin({customClass: {popup: 'popup-request-notification'}})
                            .fire({
                                icon: 'info',
                                position: 'top-end',
                                html: '<b>Seu navegador não suporta o envio de notificações push na web.</b>',
                                footer: '<a target="_blank" href="https://developer.mozilla.org/en-US/docs/Web/API/PushEvent/PushEvent#browser_compatibility">Confira aqui a lista de navegadores compatíveis 👍</a>',
                                showCloseButton: true,
                                showConfirmButton: false,
                            });

                        throw new Error('Browser does not support Push Messages!')
                    }

                    let subscription = null
                    const subscribed = await pushManager.getSubscription()

                    if (subscribed) { // User is already subscribed
                        subscription = subscribed
                    } else {
                        const serverKey = urlBase64ToUint8Array(VAPID_PUBLIC_KEY);

                        subscription = await pushManager.subscribe({userVisibleOnly: true, applicationServerKey: serverKey});
                    }

                    const key = subscription.getKey('p256dh')
                    const token = subscription.getKey('auth')
                    const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0]

                    const data = {
                        endpoint: subscription.endpoint,
                        public_key: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
                        auth_token: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
                        encoding: contentEncoding,
                    };

                    let res = await axios.post('/notifications/subscribe', data, {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    });

                    swalActivatedzNotificationSuccessfully()

                } catch (error) {
                    console.error(error);
                }
            }

            const pushNotificationAccepted = () => window.localStorage.getItem('acceptPushNotifications');

            (async () => {
                const wb = new Workbox('/service-worker.min.js');
                let registration;

                // Assuming the user accepted the update, set up a listener
                // that will reload the page as soon as the previously waiting
                // service worker has taken control.
                wb.addEventListener('controlling', (event) => {
                    // At this point, reloading will ensure that the current
                    // tab is loaded under the control of the new service worker.
                    // Depending on your web app, you may want to auto-save or
                    // persist transient state before triggering the reload.
                    window.location.reload();
                });

                wb.addEventListener('installed', async (event) => {
                    // If this is not the very first service worker install (event.isUpdate === true),
                    // it means a newer version of the service worker has been found and
                    // installed (that is, a different version from the one currently controlling the page).
                    if (! event.isUpdate) {
                        // First-installed code goes here...
                        console.info('Service worker [INSTALLED] for the first time!');
                    } else {
                        console.info('Service worker [INSTALLED]!');
                    }
                });

                wb.addEventListener('activated', (event) => {
                    // `event.isUpdate` will be true if another version of the service
                    // worker was controlling the page when this version was registered.
                    if (! event.isUpdate) {
                        console.info('Service worker [ACTIVATED] for the first time!');

                        // If your service worker is configured to precache assets, those
                        // assets should all be available now.
                    } else {
                        console.info('Service worker [ACTIVATED]!');
                    }
                });

                wb.addEventListener('waiting', (event) => {
                    console.info(`A new service worker has installed, but it can't activate until all tabs running the current version have fully unloaded.`);

                    // Send a message to the waiting service worker, instructing it to activate.
                    // same as => wb.messageSW({type: 'SKIP_WAITING'});
                    wb.messageSkipWaiting();
                });

                wb.addEventListener('message', async event => {
                    if (! event.data) {
                        return;
                    }

                    switch (event.data.type) {
                        case 'RELOAD_WINDOW':
                            window.location.reload();
                            break;
                        default:
                            // NOOP
                            break;
                    }

                    if (event.data.type === 'CACHE_UPDATED' && event.data.meta === 'workbox-broadcast-update') {
                        const {cacheName, updatedURL} = event.data.payload;

                        const cache = await caches.open(cacheName);
                        const updatedResponse = await cache.match(updatedURL);
                        const updatedText = await updatedResponse.text();

                        const doc = (new DOMParser()).parseFromString(updatedText, 'text/html');
                        const title = doc.getElementById('post-title').textContent.trim();

                        registration.showNotification('Conteúdo do post foi atualizado ✍️', {
                            body: `Clique para ver o novo conteúdo de: ${title}`,
                            icon: '/images/favicons/favicon-192x192.png',
                            tag: 'post-update-notification',
                            data: {
                                link: updatedURL.replace("/index.content.html?content=true", "").trim(),
                                title: title,
                            },
                            vibrate: true,
                            requireInteraction: false,
                        });
                    }

                    if (event.data.type === 'HI_SERVICE_WORKER') {
                        console.log('Hi service worker');
                    }
                });

                // Register the service worker after event listeners have been added.
                registration = await wb.register();

                const swVersion = await wb.messageSW({type: 'GET_VERSION'});

                console.info('Service Worker Version: ', swVersion);
            })();

            // At this point, a Service Worker is controlling the current page
            navigator.serviceWorker.ready.then((registration) => {
                let serviceWorker = registration.active;

                console.log(`A service worker is [${serviceWorker.state}] 🚀`);

                // If the key does not exist, `null` is returned
                if (serviceWorker?.state === 'activated' && pushNotificationAccepted() === null && window.location.pathname.match(/^\/blog\/.*/)?.length) {
                    const swalCustomButtons = Swal.mixin({
                        customClass: {
                            popup: 'popup-request-notification',
                            confirmButton: 'btn confirm-button without-style icon solid fa-bell primary',
                            cancelButton: 'btn without-style light small'
                        },
                        buttonsStyling: false
                    });

                    swalCustomButtons.fire({
                        position: 'top-end',
                        html: '<b>Ative as notificações</b> para receber as <i>últimas atualizações</i> do blog.',
                        confirmButtonText: 'Ativar',
                        showCancelButton: true,
                        cancelButtonText: 'Agora não',
                        reverseButtons: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.localStorage.setItem('acceptPushNotifications', true);

                            enableNotifications(registration.pushManager);
                        } else {
                            window.localStorage.setItem('acceptPushNotifications', false);
                        }
                    });
                }

                document.querySelector('.enable-notifications')
                        .addEventListener('click', () => enableNotifications(registration.pushManager));
            });

        } else {
            console.warn("Service Worker is not supported in this browser 😔");
        }

} catch (err) {
    console.err(err);
}
