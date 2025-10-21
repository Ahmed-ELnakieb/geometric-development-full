@extends('layouts.app')

@section('title', 'About Us - Geometric Development')

@section('body-class', '')

@section('content')

@php
    // Get about page data once for all sections
    $aboutPage = \App\Models\Page::where('slug', 'about')->first();
@endphp

{{-- Include all about page sections --}}
@include('pages.about.breadcrumb')
@include('pages.about.core-features')
@include('pages.about.about')
@include('pages.about.counters')
@include('pages.about.values')
@include('pages.about.expertise')
@include('pages.about.projects-portfolio')
@include('pages.about.why-choose-us')

@endsection
