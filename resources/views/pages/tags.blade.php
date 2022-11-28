@extends('layouts.default')

@section('seo')
    <x-seo>
        <x-slot:title>Tags 🏷</x-slot>
        <x-slot:description>Quantidade de posts que cada tag contêm</x-slot>
    </x-seo>
@endsection

@section('content')
    <section class="container-page">
        <header class="without-border">
            <h1 class="page-title"><i class="fas fa-tags fa-fw page-title__icon"></i> Tags</h1>
            <h2 class="page-subtitle no-anchor">Quantidade de posts que cada tag contêm 🏷</h2>
        </header>

        <section class="single wrap-content">
            <ul>
                @foreach ($tags as $tag)
                    @empty (! $tag->articles_count)
                        <li><a href="{{ route('web.blog.posts', ['tag' => $tag->slug]) }}">{{ $tag->title }} <sup>{{ $tag->articles_count }}</sup></a></li>
                    @endempty
                @endforeach
            </ul>
        </section>
    </section>
@endsection
