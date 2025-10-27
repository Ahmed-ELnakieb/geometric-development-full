@extends('layouts.app')

@section('title', $page->meta_title ?? 'Our Projects - Geometric Development')

@section('body-class', 'bs-home-4')

@section('content')

@php
    // Get projects page data once for all sections
    $projectsPage = $page ?? \App\Models\Page::where('slug', 'projects')->first();
    $sections = $projectsPage->sections ?? [];
    
    // Get all projects for sections to use
    $allProjects = \App\Models\Project::published()->ordered()->with(['categories', 'unitTypes', 'media'])->get();
@endphp

{{-- Include all projects page sections --}}
@include('pages.projects.hero')
@include('pages.projects.counters')
@include('pages.projects.latest-trends')
@include('pages.projects.featured-projects')
@include('pages.projects.video')
@include('pages.projects.testimonial')
@include('pages.projects.all-projects')
@include('pages.projects.contact')

@endsection
