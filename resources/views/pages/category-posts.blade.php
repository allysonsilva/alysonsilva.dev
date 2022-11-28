@extends('layouts.default')

@section('seo')
    <x-seo>
        <x-slot:title>{{ $category->title }} 🗂</x-slot>
        <x-slot:description>{{ $category->description }}</x-slot>
    </x-seo>
@endsection

@section('content')
    <div class="category-posts">
        <div class="category-posts__card">
            <h1 class="category-posts__page-title">{{ $category->title }}</h1>
            @if ($category->hasImage() && empty($category->deviconClass()))
                <img class='category-posts__card-image-icon' src='{{ $category->icon_url }}'>
            @else
                <span class='{{ $category->deviconClass() }} category-posts__card-css-icon'></span>
            @endif
        </div>

        <blockquote>
            <p>{{ $category->description }}</p>
        </blockquote>

        @if (! empty($posts->count()))
            <div class="category-posts__count-results">
                Essa <i>categoria</i> tem <strong>{{ $posts->count() }}</strong> posts
            </div>
        @endif

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
