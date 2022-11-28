@extends('layouts.default')

@section('seo')
    <x-seo :full-title=true>
        <x-slot:title>{{ $post->title }}</x-slot>
        <x-slot:description>{{ $post->summary }}</x-slot>
    </x-seo>
@endsection

@section('content')
    @include('pages.shared._post-content')
@endsection

@push('styles')
    <link href="{{ mix('/site/css/prism.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ mix('/site/js/prism.js') }}"></script>
    <script src="{{ mix('/site/js/vendors-markdown.js') }}"></script>
    <script src="{{ mix('/site/js/article.js') }}"></script>
@endpush

@push('scriptsHead')
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Article",
        "publisher": {
            "@type": "Organization",
            "name":"{{ $me }}",
            "url": "{{ url('/') }}",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ mix('/images/favicons/favicon.ico') }}",
                "width": 48,
                "height": 48
            }
        },
        "author": {
            "@type": "Person",
            "name": "Alyson Silva",
            "email": "mailto:hi@alyson.dev",
            "url": "{{ route('web.about-me') }}",
            "sameAs":[
                "https://github.com/allysonsilva",
                "https://twitter.com/alysonsilvadev"
            ]
        },
        "creator":[
            "Alyson Silva"
        ],
        "headline": "{{ $post->title }}",
        "url": "{{ url()->current() }}",
        "dateCreated": "{{ $post->created_at }}",
        "datePublished": "{{ $post->created_at }}",
        "dateModified": "{{ $post->created_at }}",
        "keywords": "{{ $post->comma_tags }}",
        "description": "{{ $post->summary }}",
        "image": [
            "{{ mix('/images/favicons/favicon-96x96.png') }}",
            "{{ mix('/images/favicons/favicon-192x192.png') }}",
            "{{ mix('/images/favicons/favicon-512x512.png') }}"
        ],
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "{{ url('/') }}"
        }
    }
    </script>
@endpush
