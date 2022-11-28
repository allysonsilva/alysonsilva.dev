@extends('layouts.default')

@section('seo')
    <x-seo>
        <x-slot:title>Search 🔍</x-slot>
        <x-slot:description>Página de pesquisa dos posts 🔍</x-slot>
    </x-seo>
@endsection

@section('mainClass', 'site-blog')

@section('content')
    <div class="search__field">
        <div class="search__magnify">
            <svg class="search__svg-icon" width="29" height="29" viewBox="0 0 24 24" fill="currentColor"><path d="M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z"></path></svg>
        </div>

        <form action="{{ route('web.blog.posts') }}" method="GET">
            <input type="text" name="search" class="search__input js-search-input" placeholder="Pesquisar no blog...">
            <input type="submit" hidden />
        </form>

        <div class="search__close js-search-close">
            <svg class="search__close-icon" viewBox="0 0 20 20" width="15" height="15" fill="currentColor"><path d="M8.114 10L.944 2.83 0 1.885 1.886 0l.943.943L10 8.113l7.17-7.17.944-.943L20 1.886l-.943.943-7.17 7.17 7.17 7.17.943.944L18.114 20l-.943-.943-7.17-7.17-7.17 7.17-.944.943L0 18.114l.943-.943L8.113 10z"></path></svg>
        </div>
    </div>

    <div class="search__lower">
        <div class="search__count-results">
            <strong>{{ $posts->count() }}</strong> posts encontrados
        </div>

        <div class="blog-posts">
            @foreach ($posts as $post)
                <article class="blog-post__item">
                    <div class="post-meta">
                        <time class="post-meta__date" datetime="{{ $post->human_readable_created }}"><i class="far fa-calendar-alt"></i>&nbsp;&nbsp;{{ $post->long_created }}<span> · ☕️&nbsp;&nbsp;{{ $post->read_duration }}</span></time>
                    </div>

                    <header>
                        <a href="{{ $post->url }}" class="post-link without-style">
                            <h1 class="post-title">{{ $post->title }}</h1>
                            <h2 class="post-subtitle">{{ $post->summary }}</h2>
                        </a>
                    </header>

                    <div class="post-labels">
                        @if ($post->tags->isNotEmpty())
                            @foreach ($post->tags as $tag)
                                <a href="{{ route('web.blog.posts', ['tag' => $tag->slug]) }}" class="link--hashtag tag-holder">#{{ $tag->title }}</a>
                            @endforeach
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let searchInput = document.querySelector('.js-search-input'),
            searchInputClose = document.querySelector('.js-search-close')

        searchInput.onkeyup = (event) => {
            searchInputClose.style.visibility = (event.target.value.length) ? 'visible' : 'hidden'
        };

        searchInputClose.onclick = function(event) {
            this.style.visibility = 'hidden';
            searchInput.value = '';
        };
    </script>
@endpush
