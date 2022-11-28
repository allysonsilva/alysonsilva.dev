@props([
    'fullTitle' => false,
])

@php
    $title = ($fullTitle === true) ? ($title ?? $seoTitle ) : ($title ?? $seoTitle ) . ' | ' . $me;
    $url = $seoUrl ?? url()->current();
@endphp

<title>{{ $title }}</title>

<meta name="description" content="{{ $seoDescription ?? $description }}">
<meta property="url" content="{{ $url }}">

<meta property="author" content="{{ $author }}">
<meta property="creator" content="{{ $author }}">

<link rel="canonical" href="{{ $url }}">

<!-- Open Graph -->
<meta property="og:locale" content="pt-br">
<meta property="og:site_name" content="{{ $author }}">
<meta property="og:title" content="{{ $seoTitle ?? $title }}">
<meta property="og:description" content="{{ $seoDescription ?? $description }}">
<meta property="og:url" content="{{ $url }}">
<meta property="og:type" content="{{ $ogType ?? 'article' }}">
<meta property="og:image" content="{{ $seoImage ?? mix('/images/favicons/favicon-512x512.png') }}">

<!-- Twitter Card -->
<meta name="twitter:card" content="{{ $twitCardType ?? 'summary_large_image' }}">
<meta name="twitter:site_name" content="{{ $author }}">
<meta name="twitter:site" content="@alysonsilvadev">
<meta name="twitter:title" content="{{ $seoTitle ?? $title }}">
<meta name="twitter:url" content="{{ $url }}">
<meta name="twitter:description" content="{{ $seoDescription ?? $description }}">
<meta name="twitter:image" content="{{ $seoImage ?? mix('/images/favicons/favicon-512x512.png') }}">
