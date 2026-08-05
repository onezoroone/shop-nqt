@extends('layouts.app')

@section('title', 'NQT Dev')
@section('meta_description', 'Portfolio and digital product shop by NQT Dev — Full-Stack Developer specializing in Laravel, WordPress, and modern web technologies.')

@section('content')
    <div class="home-page" data-home-scene>
        <x-home.hero
            :settings="$settings"
            :featured-projects="$featuredProjects"
            :featured-products="$featuredProducts"
        />

        <x-home.featured-products :featured-products="$featuredProducts" />

        <x-home.featured-projects :featured-projects="$featuredProjects" />

        <x-home.about
            :settings="$settings"
            :skills="$skills"
        />

        <x-home.cta :settings="$settings" />
    </div>
@endsection
