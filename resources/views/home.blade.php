@extends('layouts.app')

@section('title', 'Geometric Development - Leading Real Estate Developer in Egypt & Emirates')

@section('body-class', 'bs-home-5')

@section('content')

@php
    // Get homepage data once for all sections
    $homePage = \App\Models\Page::where('slug', 'home')->first();
    $projects = \App\Models\Project::where('is_published', true)
        ->where('is_featured', true)
        ->orderBy('display_order')
        ->limit(6)
        ->get();
    $blogPosts = \App\Models\BlogPost::where('is_published', true)
        ->orderBy('published_at', 'desc')
        ->limit(3)
        ->get();
@endphp

{{-- Include all homepage sections --}}
@include('frontend.home.hero')
@include('frontend.home.about')
@include('frontend.home.counters')
@include('frontend.home.video')
@include('frontend.home.services')
@include('frontend.home.projects')
@include('frontend.home.showcase')
@include('frontend.home.gallery')
@include('frontend.home.blog')

@endsection