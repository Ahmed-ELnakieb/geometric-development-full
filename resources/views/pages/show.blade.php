@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title . ' - Geometric Development')

@section('body-class', '')

@section('content')

<!-- Breadcrumb Section -->
<section class="breadcrumb-area" style="background-image: url('{{ asset('assets/img/breadcrumb/breadcrumb-img.png') }}');">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-content">
                    <h2>{{ $page->title }}</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content Section -->
<div class="container bs-container-1">
    <div class="page-content pt-120 pb-120">
        <h1 class="bs-h-1 mb-30">{{ $page->title }}</h1>
        {!! $page->content !!}
    </div>
</div>

<!-- Sections Display -->
@if($page->sections && is_array($page->sections))
    @foreach($page->sections as $section)
        @if($section['type'] === 'heading')
            <div class="container bs-container-1">
                <div class="section-heading">
                    <h2>{{ $section['content'] ?? '' }}</h2>
                </div>
            </div>
        @elseif($section['type'] === 'text')
            <div class="container bs-container-1">
                <div class="section-content">
                    <p>{!! $section['content'] ?? '' !!}</p>
                </div>
            </div>
        @elseif($section['type'] === 'image')
            <div class="container bs-container-1">
                <div class="section-image">
                    <img src="{{ $section['src'] ?? '' }}" alt="{{ $section['alt'] ?? '' }}" class="img-fluid">
                </div>
            </div>
        @endif
    @endforeach
@endif

@endsection