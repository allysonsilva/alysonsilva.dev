<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">

<!-- SEO -->
@yield('seo')
<!-- //SEO -->

<!-- HEAD -->
@include('shared._head')
<!-- //HEAD -->

<script src="{{ mix('site/js/vendors-app.js') }}" defer></script>

<!-- WORKER WINDOW -->
<script type="module" src="{{ mix_local('/site/js/install-sw.js') }}"></script>
<!-- //WORKER WINDOW -->
</head>

<body id="@yield('bodyClass', 'app-body')">
    @include('shared._theme')
    @include('shared._search')

    <!-- No javascript error -->
    <noscript>JavaScript turned off...</noscript>

    <!-- Site Wrapper -->
    <section id="@yield('siteWrapperId', 'site-wrapper')">
        @include('layouts.partials._header')

        <main class="main-content @yield('mainClass')" role="main">
            <section class="@yield('containerAroundClass', 'container-inner')">
                @yield('content')
            </section>
        </main>

        @include('layouts.partials._footer')
    </section>
    <!-- // Site Wrapper -->

    <!-- Scripts -->
        <script src="{{ mix('site/js/vendors-svg.js') }}" defer></script>
        <script src="{{ mix('site/js/header.js') }}" defer></script>

        <script src="{{ mix('site/js/manifest.js') }}" defer></script>

        @stack('scripts')

        <script type="module" src="{{ mix('site/js/app.js') }}" defer></script>
    <!-- // Scripts -->
</body>
</html>
