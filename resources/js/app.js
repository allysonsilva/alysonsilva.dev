/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.askPermission = () => {
    return new Promise(function (resolve, reject) {
        const permissionResult = Notification.requestPermission(function (result) {
            resolve(result);
        });

        if (permissionResult) {
            permissionResult.then(resolve, reject);
        }
    }).then((permissionResult) => {
        if (permissionResult !== 'granted') {
            throw new Error("We weren't granted permission.");
        }
    });
}

// document.addEventListener('DOMContentLoaded', (event) => { askPermission() });

window.setupEcho = () => {
    const echo = window.Echo = new Echo({
        broadcaster: 'pusher',
        key: process.env.MIX_PUSHER_APP_KEY,
        cluster: '',
        wsHost: process.env.MIX_PUSHER_APP_HOST,
        wssHost: process.env.MIX_PUSHER_APP_HOST,
        wsPort: 443,
        wssPort: 443,
        forceTLS: true,
        encrypted: true,
        // enableLogging: true,
        disableStats: true,
        enabledTransports: ['ws', 'wss'],
    });

    echo.channel('public').listen('.articles.created', (event) => {

        if (! ('PushManager' in window)) {
            askPermission()

            const notification = new Notification(event.title, {
                body: event.body,
                icon: '/images/favicons/favicon-192x192.png',
                vibrate: true,
            });

            notification.onclick = () => {
                window.open(event.link);

                notification.close();
            }
        }
    });
}

setupEcho()
