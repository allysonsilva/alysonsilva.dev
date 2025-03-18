const mix = require('laravel-mix')
const path = require('path')
const S3Plugin = require('webpack-s3-plugin')

require('laravel-mix-merge-manifest')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
    resolve: {
        extensions: ['.js', '.json', '.scss'],
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            '@sass': path.resolve(__dirname, 'resources/sass'),
            '@vendors': path.resolve(__dirname, 'resources/js/vendors'),
            '@components': path.resolve(__dirname, 'resources/js/components'),
        },
    },
    plugins: []
})

mix.options({
    hmrOptions: {
        host: '0.0.0.0',
        port: 5040,
    },
})

if (mix.inProduction()) {
    mix.webpackConfig({
        plugins: [
            new S3Plugin({
                s3Options: {
                    accessKeyId: process.env.MIX_S3_KEY,
                    secretAccessKey: process.env.MIX_S3_SECRET,
                    s3BucketEndpoint: true,
                    endpoint: process.env.MIX_S3_ENDPOINT,
                },
                s3UploadOptions: {
                    Bucket: process.env.MIX_S3_BUCKET,
                    CacheControl: 'public, max-age=31536000'
                },
            }),
        ]
    });
}

mix.disableNotifications()

if (['site', 'combine', 'site.sw', 'admin'].includes(process.env.npm_config_section)) {
    require(`${__dirname}/webpack.${process.env.npm_config_section}.mix.js`)
} else {
    console.log('\x1b[41m%s\x1b[0m', 'Provide correct --section argument to build command: site, admin')

    throw new Error('Provide correct --section argument to build command!')
}
