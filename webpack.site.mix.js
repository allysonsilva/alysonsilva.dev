const mix = require('laravel-mix')
const fs = require('fs')
const postcssColorMod = require('postcss-color-mod-function')

// npm run mix-production --section=site

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

const cssFolder = 'public/site/css'
const jsFolder = 'public/site/js'

mix.options({
    fileLoaderDirs:  {
        fonts: 'site/fonts',
    },
    runtimeChunkPath: 'site/js'
})

if (! mix.inProduction()) {
   mix.sourceMaps()
   mix.webpackConfig({devtool: 'inline-source-map'})
}

mix.copyDirectory('resources/images', 'public/images')

mix.sass('resources/sass/icons.scss', `${cssFolder}/icons.css`)
   .sass('resources/sass/app.scss', `${cssFolder}/app.css`)
   .options({
       postCss: [postcssColorMod({})],
   })

mix.js('resources/js/app.js', `${jsFolder}/app.js`)

mix.js('resources/js/libraries.js', `${jsFolder}/vendors-app.js`)

mix.js('resources/js/install-sw-workbox.js', `${jsFolder}/install-sw.js`)

mix.js('resources/js/article.js', `${jsFolder}/article.js`)
   .extract(['@vendors/markdown', 'dompurify'], `${jsFolder}/vendors-markdown.js`)

mix.scripts(['resources/js/vendors/prismjs/prism.js'], `${jsFolder}/prism.js`)
   .styles(['resources/js/vendors/prismjs/prism.css'], `${cssFolder}/prism.css`)

mix.scripts(['resources/js/vendors/ease.min.js', 'resources/js/vendors/segment.min.js'], `${jsFolder}/vendors-svg.js`)
   .scripts(['resources/js/header.js'], `${jsFolder}/header.js`)
   .scripts(['resources/js/data-theme.js'], `${jsFolder}/data-theme.js`)

mix.version([
        'public/images',
        'public/storage/images/icons',
        'public/manifest.json',
        'public/sitemap.xml',
        'public/feed.xml',
    ])
    .options({ processCssUrls: true })
    .mergeManifest()

mix.then(webpackStats => {
    console.log('\x1b[42m\x1b[30m%s\x1b[0m', '\n{webpack.site.mix.js} has been successfully compiled!\n')
})
