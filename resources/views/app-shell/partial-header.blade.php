<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">

<!-- HEAD -->
@include('shared._head')
<!-- //HEAD -->

<script src="{{ mix('site/js/vendors-app.js') }}" defer></script>

<!-- WORKER WINDOW -->
<script type="module" src="{{ mix_local('/site/js/install-sw.js') }}"></script>
<!-- //WORKER WINDOW -->
</head>

<body>
    @include('shared._theme')
    @include('shared._search')

    <!-- No javascript error -->
    <noscript>JavaScript turned off...</noscript>

    <!-- Site Wrapper -->
    <section>
        @include('layouts.partials._header')

        <main class="main-content">
            <section class="container-inner">
