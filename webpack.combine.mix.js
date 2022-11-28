const mix = require('laravel-mix')

// npm run mix-production --section=combine

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.minify('public/service-worker.js');

mix.version(['public/service-worker.min.js',]).mergeManifest();

mix.then(webpackStats => {
    console.log('\x1b[42m\x1b[30m%s\x1b[0m', '\n{webpack.combine.mix.js} has been successfully compiled!\n')
})
