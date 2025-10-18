@extends('layouts.app')

@section('title', '404 - Page Not Found - Geometric Development')

@section('body-class', '')

@section('content')
<section class="bs-error-page-area pt-160 pb-160">
    <div class="container bs-container-1">
        <div class="bs-error-page-wrap">
            <div class="bs-error-page-content">
                <h4 class="bs-h-4 title-1 ">Oops!</h4>
                <h4 class="bs-h-4 title-2 ">404 Error!</h4>
                <p class="bs-p-4 disc">We can't find the page that you're looking for. Can't find what you need? Take a moment and search below!</p>

                <a href="{{ route('home') }}" aria-label="name" class="bs-btn-1">
                    <span class="text">
                        back to home page
                    </span>
                    <span class="icon">
                        <i class="fa-solid fa-right-long"></i>
                        <i class="fa-solid fa-right-long"></i>
                    </span>
                    <span class="shape" ></span>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
