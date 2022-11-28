// @see https://developers.google.com/web/tools/workbox/reference-docs/latest/module-workbox-build#.injectManifest
const chalk = require('chalk')

console.log(chalk.black.bgGreen.italic('injectManifest: Injects the assets to precache into your project.'), '\n');

module.exports = {
    globDirectory: './public/',
    globPatterns: [
        // '{site,images}/**/*.{js,css,html,json,ico,jpg,png,svg}',
        // 'site/**/*.{js,css}',
        'app-shell/*.html',
    ],
    globFollow: true,
    globStrict: true,
    globIgnores: [
        '**/node_modules/**/*',
        '**/*/vendors-*.js',
        '**/*/workbox-window.js',
        '**/*/service-worker.js',
        'images/favicons/{favicon,apple-icon}-*.png',
    ],
    modifyURLPrefix: {
        '': '/',
    },
    dontCacheBustURLsMatching: new RegExp('.+.[a-f0-9]{20}..+'),
    maximumFileSizeToCacheInBytes: 5000000,
    swDest: './public/service-worker.js',
    swSrc: './resources/js/service-worker-workbox.js',
};
