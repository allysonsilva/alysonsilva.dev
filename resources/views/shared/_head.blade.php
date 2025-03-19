<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0">

{{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

<meta name="application-name" content="{{ config('app.name') }}">
<meta name="hostname" content="{{ config('app.hostname') }}">

<meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

<meta name="theme-color" content="#303238">

<link rel="manifest" href="{{ mix_local('/manifest.json') }}">
<link rel="shortcut icon" href="{{ mix('/images/favicons/favicon.ico') }}">

<link rel="apple-touch-icon" sizes="57x57" href="{{ mix('/images/favicons/apple-icon-57x57.png') }}">
<link rel="apple-touch-icon" sizes="60x60" href="{{ mix('/images/favicons/apple-icon-60x60.png') }}">
<link rel="apple-touch-icon" sizes="72x72" href="{{ mix('/images/favicons/apple-icon-72x72.png') }}">
<link rel="apple-touch-icon" sizes="76x76" href="{{ mix('/images/favicons/apple-icon-76x76.png') }}">
<link rel="apple-touch-icon" sizes="114x114" href="{{ mix('/images/favicons/apple-icon-114x114.png') }}">
<link rel="apple-touch-icon" sizes="120x120" href="{{ mix('/images/favicons/apple-icon-120x120.png') }}">
<link rel="apple-touch-icon" sizes="144x144" href="{{ mix('/images/favicons/apple-icon-144x144.png') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ mix('/images/favicons/apple-icon-152x152.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ mix('/images/favicons/apple-icon-180x180.png') }}">
<link rel="apple-touch-icon" sizes="192x192" href="{{ mix('/images/favicons/favicon-192x192.png') }}">

<link rel="icon" type="image/png" sizes="16x16" href="{{ mix('/images/favicons/favicon-16x16.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ mix('/images/favicons/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="36x36" href="{{ mix('/images/favicons/favicon-36x36.png') }}">
<link rel="icon" type="image/png" sizes="48x48" href="{{ mix('/images/favicons/favicon-48x48.png') }}">
<link rel="icon" type="image/png" sizes="96x96" href="{{ mix('/images/favicons/favicon-96x96.png') }}">
<link rel="icon" type="image/png" sizes="128x128" href="{{ mix('/images/favicons/favicon-128x128.png') }}">
<link rel="icon" type="image/png" sizes="192x192" href="{{ mix('/images/favicons/favicon-192x192.png') }}">
<link rel="icon" type="image/png" sizes="196x196" href="{{ mix('/images/favicons/favicon-196x196.png') }}">
<link rel="icon" type="image/png" sizes="512x512" href="{{ mix('/images/favicons/favicon-512x512.png') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link rel="alternate" type="application/atom+xml" title="Alyson Silva - RSS Feed" href="{{ mix('/feed.xml') }}">
<link rel="sitemap" type="application/xml" title="Alyson Silva - Sitemap" href="{{ mix('/sitemap.xml') }}">

<script src="{{ mix('site/js/data-theme.js') }}"></script>

<link rel="preload" href="{{ mix('site/css/icons.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload" href="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/devicon.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">

<noscript>
    <link rel="stylesheet" href="{{ mix('site/css/icons.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/devicon.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</noscript>

<link id="main-stylesheet" href="{{ mix('site/css/app.css') }}" rel="stylesheet">

<link rel="modulepreload" href="{{ mix('site/js/app.js') }}">

<script nomodule src="https://unpkg.com/browser-es-module-loader/dist/babel-browser-build.js"></script>
<script nomodule src="https://unpkg.com/browser-es-module-loader"></script>

<script async src="https://google-analytics.com/analytics.js"></script>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ config('app.google_analytics_id') }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '{{ config('app.google_analytics_id') }}');
</script>

<script>
    if (! ('CSS' in window) || ! CSS.supports('color', 'var(--color-var)')) {
        let mainStylesheet = document.getElementById('main-stylesheet');

        if (mainStylesheet) {
            let href = mainStylesheet.getAttribute('href');

            href = href.replace('style.css', 'style-fallback.css');

            mainStylesheet.setAttribute('href', href);
        }
    }

    window.isMobile = () => window.matchMedia('only screen and (max-width: 767px)').matches &&
                            'ontouchstart' in document.documentElement;
</script>

@stack('styles')

@stack('scriptsHead')
