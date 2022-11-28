@extends('layouts.default')

@section('seo')
    <x-seo>
        <x-slot:title>{{ $seoTitle }}</x-slot>
        <x-slot:description>{{ $seoDescription }}</x-slot>
    </x-seo>
@endsection

@section('content')
    @include('pages.shared._home-content')
@endsection

@push('scriptsHead')
<script type="application/ld+json">
@include('schemas.home')
</script>
@endpush
