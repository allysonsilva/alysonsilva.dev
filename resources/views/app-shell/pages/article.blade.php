<script defer src="{{ mix('/site/js/prism.js') }}"></script>
<script defer src="{{ mix('/site/js/vendors-markdown.js') }}"></script>
<script defer src="{{ mix('/site/js/article.js') }}"></script>

<link href="{{ mix('/site/css/prism.css') }}" rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link href="{{ mix('/site/css/prism.css') }}" rel="stylesheet"></noscript>

<script>
window.addEventListener('DOMContentLoaded', (event) => {
    document.head.insertAdjacentHTML('beforeend', String.raw`{!! $seoHTML !!}`);
    // document.head.insertAdjacentHTML('beforeend', String.raw`<link href="{{ mix('/site/css/prism.css') }}" rel="stylesheet">`);

    let script = document.createElement('script');
    script.type = 'application/ld+json';
    script.innerHTML = String.raw`
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
    `;

    document.head.appendChild(script);
});
</script>

@include('pages.shared._post-content')
