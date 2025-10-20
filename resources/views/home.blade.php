@extends('layouts.app')

@section('title', 'Geometric Development - Leading Real Estate Developer in Egypt & Emirates')

@section('body-class', 'bs-home-5')

@section('content')

<!-- hero-start -->
<section class="bs-hero-5-area wa-bg-default wa-p-relative wa-fix wa-bg-parallax" data-background="{{ asset('assets/img/hero/h5-bg-img-1.png') }}">
    <div class="container bs-container-2">
        <div class="bs-hero-5-wrap">

            <!-- left-img -->
            <!-- <div class="bs-hero-5-img wa-fix">
                <img src="assets/img/hero/h5-img-2.png" alt="">
            </div> -->

            <div class="bs-hero-5-right">
                <h1 class="bs-hero-5-title-1 bs-h-4 wa-split-right " data-split-delay="1.5s">Leading Community Developer in MUROJ</h1>

                <div class="inner-div-1">
                    <p class="bs-hero-5-disc">Inspiration of MUROJ in EGYPT</p>

                    <h2 class="bs-hero-5-title-2 bs-h-4 wa-split-right cd-headline clip" data-split-delay="2s">
                        <span class="cd-words-wrapper single-headline">
                            <b class="is-visible">Luxury Living</b>
                            <b>Invest Smart</b>
                            <b>Buy Quality</b>
                            <b>Dream Home</b>
                        </span>
                    </h2>
                </div>

                <div class="inner-div-2">
                    <a class="bs-hero-5-btn wa-magnetic" href="{{ route('projects.index') }}" aria-label="name" >
                        <span class="icon wa-bg-default" data-background="{{ asset('assets/img/hero/h5-img-3.png') }}">
                            <i class="flaticon-next-1 flaticon"></i>
                        </span>
                        <span class="btn-border has-border-1"></span>
                        <span class="btn-border has-border-2"></span>
                        <span class="btn-border has-border-3"></span>
                    </a>
                    <h3 class="bs-hero-5-title-3 bs-h-4 wa-split-right " data-split-delay="2.5s">IN GEOMETRIC</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="bs-hero-5-bg-img">
        <img src="{{ asset('assets/img/hero/h5-img-1.png') }}" alt="">
    </div>

    <div class="bs-hero-5-bg-circle"></div>
</section>
<!-- hero-end -->

<!-- about-start -->
<section class="bs-about-5-area pt-135 pb-100 wa-fix wa-p-relative">
    <div class="bs-about-5-bg-shape">
        <img src="{{ asset('assets/img/about/a5-bg-shape.png') }}" alt="">
    </div>
    <div class="bs-about-5-bg-shape-2">
        <img src="{{ asset('assets/img/about/a5-bg-shape-2.png') }}" alt="">
    </div>

    <div class="container bs-container-2">

        <!-- section-title -->
        <div class="bs-about-5-sec-title mb-55">
            <h6 class="bs-subtitle-5 wa-capitalize">
                <span>01</span>
                <span class="wa-split-right ">about us</span>
            </h6>
            <h2 class="bs-sec-title-4 wa-split-right wa-capitalize" data-cursor="-opaque">Your trusted partner in finding  properties and investment opportunities in Egypt's most desirable locations.</h2>
        </div>

        <div class="bs-about-5-wrap">

            <!-- left-side -->
            <div class="bs-about-5-left">
                <p class="bs-p-4 disc wa-fadeInUp">Discover your perfect property with Geometric Development. We specialize in premium real estate sales across Egypt's most sought-after destinations, including exclusive residential communities in Muroj. Our expert team guides you through every step of your property buying journey.</p>
                <div class="btn-wrap wa-fadeInUp">
                    <a href="{{ route('page.show', 'about') }}" aria-label="know about us" class="bs-pr-btn-3">
                        <span class="text">know about us <i class="fa-solid fa-angle-right"></i></span>
                        <span class="text">know about us <i class="fa-solid fa-angle-right"></i></span>
                    </a>
                </div>

                <div class="bs-about-5-img-1 wa-fix wa-img-cover wa-fadeInUp" data-cursor="-opaque">
                    <img class="wa-parallax-img" src="{{ asset('assets/img/about/a5-img-1.png') }}" alt="">
                </div>
            </div>

            <!-- right-side -->
            <div class="bs-about-5-right">
                <div class="bs-about-5-img-2 wa-fix wa-img-cover wa-fadeInUp" data-cursor="-opaque">
                    <img class="wa-parallax-img" src="{{ asset('assets/img/about/a5-img-2.png') }}" alt="">
                </div>

                <ul class="bs-about-5-feature wa-list-style-none">
                    <li class="bs-p-4 wa-fadeInUp">
                        <i class="fa-solid fa-plus"></i>
                        Prime Locations
                    </li>
                    <li class="bs-p-4 wa-fadeInUp">
                        <i class="fa-solid fa-plus"></i>
                        Luxury Amenities
                    </li>
                    <li class="bs-p-4 wa-fadeInUp">
                        <i class="fa-solid fa-plus"></i>
                        Modern Design
                    </li>
                    <li class="bs-p-4 wa-fadeInUp">
                        <i class="fa-solid fa-plus"></i>
                        Smart Homes
                    </li>
                    <li class="bs-p-4 wa-fadeInUp">
                        <i class="fa-solid fa-plus"></i>
                        Eco-Friendly
                    </li>
                    <li class="bs-p-4 wa-fadeInUp">
                        <i class="fa-solid fa-plus"></i>
                        Premium Finishes
                    </li>
                    <li class="bs-p-4 wa-fadeInUp">
                        <i class="fa-solid fa-plus"></i>
                        Investment Opportunities
                    </li>
                    <li class="bs-p-4 wa-fadeInUp">
                        <i class="fa-solid fa-plus"></i>
                        Customizable Options
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- about-end -->

<!-- counter-start -->
<section class="bs-core-feature-5-area">
    <div class="container bs-container-2">
        <div class="bs-core-feature-4-line wa-scaleXInUp"></div>
        <div class="bs-core-feature-4-wrap has-5">

            <!-- single-item -->
            <div class="bs-core-feature-4-item wow fadeInRight" >
                <h4 class="bs-h-4 item-title">
                    Properties Sold
                </h4>
                <h5 class="bs-h-4 item-counter" data-cursor="-opaque">
                    <span class="counter wa-counter">2.5</span>
                    k+
                </h5>
                <p class="bs-p-4 item-disc">Successfully helping thousands of clients find their perfect properties across UAE.</p>
            </div>

            <!-- single-item -->
            <div class="bs-core-feature-4-item wow fadeInRight" data-wow-delay="0.2s">
                <h4 class="bs-h-4 item-title">
                    Years in Real Estate
                </h4>
                <h5 class="bs-h-4 item-counter" data-cursor="-opaque">
                    <span class="counter wa-counter">15</span>
                    +
                </h5>
                <p class="bs-p-4 item-disc">Over 15 years of expertise in UAE real estate market and property sales.</p>
            </div>

            <!-- single-item -->
            <div class="bs-core-feature-4-item wow fadeInRight" data-wow-delay="0.4s">
                <h4 class="bs-h-4 item-title">
                    Happy Homeowners
                </h4>
                <h5 class="bs-h-4 item-counter" data-cursor="-opaque">
                    <span class="counter wa-counter">98</span>
                    %
                </h5>
                <p class="bs-p-4 item-disc">98% of our clients are satisfied with their property purchases and our service.</p>
            </div>

            <!-- single-item -->
            <div class="bs-core-feature-4-item wow fadeInRight" data-wow-delay="0.6s">
                <h4 class="bs-h-4 item-title">
                    Market Leadership
                </h4>
                <h5 class="bs-h-4 item-counter" data-cursor="-opaque">
                    <span class="counter wa-counter">1</span>
                    st
                </h5>
                <p class="bs-p-4 item-disc">Leading real estate company specializing in premium Ras Al Khaimah properties.</p>
            </div>

        </div>
        <div class="bs-core-feature-4-line wa-scaleXInUp"></div>
    </div>
</section>
<!-- counter-end -->

<!-- video-start -->
<div class="bs-video-1-area wa-fix">
    <div class="bs-video-1-content wa-p-relative">
        <div class="bs-video-1-content-img has-video-2  wa-p-relative wa-fix wa-img-cover">
            <a href="https://www.youtube.com/watch?v=e45TPIcx5CY" aria-label="name" class="popup-video" data-cursor-text="play" >
                <video class="wa-parallax-img"  src="{{ asset('assets/img/video/v2-video-1.mp4') }}" autoplay loop muted></video>
            </a>


            <div class="bs-video-1-play-btn ">
                <a href="https://www.youtube.com/watch?v=e45TPIcx5CY" aria-label="name" class="bs-play-btn-3 wa-magnetic popup-video">
                    <span class="icon wa-magnetic-btn">
                        <i class="fa-solid fa-play"></i>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- video-end -->

<!-- services-start -->
 <!-- projects-start -->
<section class="bs-projects-1-area pt-90 pb-145 wa-p-relative wa-fix">
    <div class="container bs-container-1">

        <!-- section-title -->
        <div class="bs-projects-1-sec-title mb-40">
            <h6 class="bs-subtitle-1 wa-split-clr wa-capitalize">
                <span class="icon">
                    <img src="{{ asset('assets/img/illus/star-shape.png') }}" alt="">
                </span>
                our services
            </h6>
            <h2 class="bs-sec-title-1 wa-split-right wa-capitalize" data-cursor="-opaque">Geometric Development <br> Premium Real Estate Services</h2>
        </div>

        <div class="bs-projects-1-wrap">

            <!-- tabs-btn -->
            <div class="bs-projects-1-tabs-btn" role="tablist">

                <!-- single-btn -->
                <button class="nav-link  wa-fadeInUp" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                    Muroj Villa

                    <span class="year">2025</span>
                    <span class="img">
                        <img src="{{ asset('assets/img/projects/p1-btn-img-1.png') }}" alt="">
                    </span>
                    <span class="right-arrow">
                        <img src="{{ asset('assets/img/illus/long-right-arrow.png') }}" alt="">
                    </span>
                </button>

                <!-- single-btn -->
                <button class="nav-link wa-fadeInUp active" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">
                    Mina Marina
                    <span class="year">2025</span>
                    <span class="img">
                        <img src="{{ asset('assets/img/projects/p1-btn-img-1.png') }}" alt="">
                    </span>
                    <span class="right-arrow">
                        <img src="{{ asset('assets/img/illus/long-right-arrow.png') }}" alt="">
                    </span>
                </button>

                <!-- single-btn -->
                <button class="nav-link wa-fadeInUp" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">
                    Rich Hills
                    <span class="year">2025</span>
                    <span class="img">
                        <img src="{{ asset('assets/img/projects/p1-btn-img-1.png') }}" alt="">
                    </span>
                    <span class="right-arrow">
                        <img src="{{ asset('assets/img/illus/long-right-arrow.png') }}" alt="">
                    </span>
                </button>

                <!-- single-btn -->
                <button class="nav-link wa-fadeInUp" id="nav-contact-tab2" data-bs-toggle="tab" data-bs-target="#nav-contact2" type="button" role="tab" aria-controls="nav-contact2" aria-selected="false">
                    Ras Al Khaimah
                    <span class="year">2025</span>
                    <span class="img">
                        <img src="{{ asset('assets/img/projects/p1-btn-img-1.png') }}" alt="">
                    </span>
                    <span class="right-arrow">
                        <img src="{{ asset('assets/img/illus/long-right-arrow.png') }}" alt="">
                    </span>
                </button>

            </div>


            <!-- tabs-content -->
            <div class="bs-projects-1-tabs-pane tab-content" >

                <!-- single-pane -->
                <div class="tab-pane fade " id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="bs-projects-1-tabs-item">
                        <div class="main-img wa-img-cover wa-fix">
                            <a href="{{ route('projects.index') }}"><img data-cursor="-opaque" src="{{ asset('assets/img/projects/p1-img-1.png') }}" alt=""></a>
                        </div>
                        <div class="bs-projects-1-tabs-item-table">
                            <div class="start">
                                <h6 class="bs-h-1 title">start & completed</h6>
                                <div class="wrap">
                                    <p class="bs-p-1 disc">jan 02, 2025</p>
                                    <p class="bs-p-1 disc">aug 02, 2025</p>
                                </div>
                            </div>
                            <div class="location">
                                <h6 class="bs-h-1 title">Location</h6>
                                <p class="bs-p-1 disc">136 North Coast, Egypt</p>
                            </div>
                            <div class="share">
                                <h6 class="bs-h-1 title-2">share project</h6>
                                <div class="bs-social-link-1">
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-facebook-f"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-x-twitter"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-linkedin-in"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-pinterest-p"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- single-pane -->
                <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <div class="bs-projects-1-tabs-item">
                        <div class="main-img wa-img-cover wa-fix">
                            <a href="{{ route('projects.index') }}"><img data-cursor="-opaque" src="{{ asset('assets/img/projects/p1-img-2.png') }}" alt=""></a>
                        </div>
                        <div class="bs-projects-1-tabs-item-table">
                            <div class="start">
                                <h6 class="bs-h-1 title">start & completed</h6>
                                <div class="wrap">
                                    <p class="bs-p-1 disc">jan 02, 2025</p>
                                    <p class="bs-p-1 disc">aug 02, 2025</p>
                                </div>
                            </div>
                            <div class="location">
                                <h6 class="bs-h-1 title">Location</h6>
                                <p class="bs-p-1 disc">New Cairo, Egypt</p>
                            </div>
                            <div class="share">
                                <h6 class="bs-h-1 title-2">share project</h6>
                                <div class="bs-social-link-1">
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-facebook-f"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-x-twitter"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-linkedin-in"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-pinterest-p"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- single-pane -->
                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                    <div class="bs-projects-1-tabs-item">
                        <div class="main-img wa-img-cover wa-fix">
                            <a href="{{ route('projects.index') }}"><img data-cursor="-opaque" src="{{ asset('assets/img/projects/p1-img-3.png') }}" alt=""></a>
                        </div>
                        <div class="bs-projects-1-tabs-item-table">
                            <div class="start">
                                <h6 class="bs-h-1 title">start & completed</h6>
                                <div class="wrap">
                                    <p class="bs-p-1 disc">jan 02, 2025</p>
                                    <p class="bs-p-1 disc">aug 02, 2025</p>
                                </div>
                            </div>
                            <div class="location">
                                <h6 class="bs-h-1 title">Location</h6>
                                <p class="bs-p-1 disc">North Coast, Egypt</p>
                            </div>
                            <div class="share">
                                <h6 class="bs-h-1 title-2">share project</h6>
                                <div class="bs-social-link-1">
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-facebook-f"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-x-twitter"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-linkedin-in"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-pinterest-p"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- single-pane -->
                <div class="tab-pane fade" id="nav-contact2" role="tabpanel" aria-labelledby="nav-contact-tab2">
                    <div class="bs-projects-1-tabs-item">
                        <div class="main-img wa-img-cover wa-fix">
                            <a href="{{ route('projects.index') }}"><img data-cursor="-opaque" src="{{ asset('assets/img/projects/p1-img-4.png') }}" alt=""></a>
                        </div>
                        <div class="bs-projects-1-tabs-item-table">
                            <div class="start">
                                <h6 class="bs-h-1 title">start & completed</h6>
                                <div class="wrap">
                                    <p class="bs-p-1 disc">jan 02, 2025</p>
                                    <p class="bs-p-1 disc">aug 02, 2025</p>
                                </div>
                            </div>
                            <div class="location">
                                <h6 class="bs-h-1 title">Location</h6>
                                <p class="bs-p-1 disc">Hurghada, Egypt</p>
                            </div>
                            <div class="share">
                                <h6 class="bs-h-1 title-2">share project</h6>
                                <div class="bs-social-link-1">
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-facebook-f"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-x-twitter"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-linkedin-in"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-pinterest-p"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- bg-img -->
    <div class="bs-projects-1-bg-img">
        <img src="{{ asset('assets/img/projects/p1-bg-img-1.png') }}" alt="">
    </div>
</section>
<!-- projects-end -->
<!-- services-end -->

<!-- project-start -->
<section class="bs-project-5-area pt-135 pb-140 wa-fix">
    <div class="container bs-container-2">
        <h2 class="bs-project-5-sec-title wa-split-right wa-capitalize">RESIDENTIAL PROPERTIES</h2>

        <div class="bs-project-5-wrap wa-p-relative">
            @foreach($projects->take(6) as $index => $project)
            @if($index == 0)
            <!-- single-project -->
            <div class="bs-project-5-item">
                <div class="main-img wa-fix wa-img-cover">
                    <a href="{{ route('projects.show', $project->slug) }}" data-cursor-text="View">
                        <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (10).png') }}" alt="">
                    </a>
                </div>
                <h5 class="bs-h-4 title">
                    <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">{{ $project->title }}</a>
                </h5>
                <ul class="item-list wa-list-style-none">
                    <li class="bs-p-4" >{{ $project->location }}</li>
                    <li class="bs-p-4" >More Details</li>
                </ul>
            </div>
            @elseif($index == 1)
            <!-- single-project -->
            <div class="bs-project-5-item  height-2">
                <div class="main-img wa-fix wa-img-cover">
                    <a href="{{ route('projects.show', $project->slug) }}" data-cursor-text="View">
                        <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (10).png') }}" alt="">
                    </a>
                </div>
                <h5 class="bs-h-4 title">
                    <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">{{ $project->title }}</a>
                </h5>
                <ul class="item-list wa-list-style-none">
                    <li class="bs-p-4" >{{ $project->location }}</li>
                    <li class="bs-p-4" >More Details</li>
                </ul>
            </div>
            @endif
            @endforeach
        </div>

        <div class="bs-project-5-wrap-2  wa-p-relative">
            @foreach($projects->take(6) as $index => $project)
            @if($index == 2)
            <!-- single-project -->
            <div class="bs-project-5-item height-3">
                <div class="main-img wa-fix wa-img-cover">
                    <a href="{{ route('projects.show', $project->slug) }}" data-cursor-text="View">
                        <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (10).png') }}" alt="">
                    </a>
                </div>
                <h5 class="bs-h-4 title">
                    <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">{{ $project->title }}</a>
                </h5>
                <ul class="item-list wa-list-style-none">
                    <li class="bs-p-4" >{{ $project->location }}</li>
                    <li class="bs-p-4" >More Details</li>
                </ul>
            </div>
            @endif
            @endforeach
        </div>

        <div class="bs-project-5-wrap-3 mb-60  wa-p-relative">
            @foreach($projects->take(6) as $index => $project)
            @if($index == 3)
            <!-- single-project -->
            <div class="bs-project-5-item height-4">
                <div class="main-img wa-fix wa-img-cover">
                    <a href="{{ route('projects.show', $project->slug) }}" data-cursor-text="View">
                        <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (10).png') }}" alt="">
                    </a>
                </div>
                <h5 class="bs-h-4 title">
                    <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">{{ $project->title }}</a>
                </h5>
                <ul class="item-list wa-list-style-none">
                    <li class="bs-p-4" >{{ $project->location }}</li>
                    <li class="bs-p-4" >More Details</li>
                </ul>
            </div>
            @elseif($index == 4)
            <!-- single-project -->
            <div class="bs-project-5-item height-5">
                <div class="main-img wa-fix wa-img-cover">
                    <a href="{{ route('projects.show', $project->slug) }}" data-cursor-text="View">
                        <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (10).png') }}" alt="">
                    </a>
                </div>
                <h5 class="bs-h-4 title">
                    <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">{{ $project->title }}</a>
                </h5>
                <ul class="item-list wa-list-style-none">
                    <li class="bs-p-4" >{{ $project->location }}</li>
                    <li class="bs-p-4" >More Details</li>
                </ul>
            </div>
            @elseif($index == 5)
            <!-- single-project -->
            <div class="bs-project-5-item height-5">
                <div class="main-img wa-fix wa-img-cover">
                    <a href="{{ route('projects.show', $project->slug) }}" data-cursor-text="View">
                        <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (10).png') }}" alt="">
                    </a>
                </div>
                <h5 class="bs-h-4 title">
                    <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">{{ $project->title }}</a>
                </h5>
                <ul class="item-list wa-list-style-none">
                    <li class="bs-p-4" >{{ $project->location }}</li>
                    <li class="bs-p-4" >More Details</li>
                </ul>
            </div>
            @endif
            @endforeach
        </div>
    </div>

    <div class="bs-project-5-all-btn wa-fadeInUp" data-background="{{ asset('assets/img/projects/p5-btn-bg-shape.png') }}">
        <a href="{{ route('projects.index') }}" aria-label="name" class="bs-pr-btn-3">
            <span class="text">view all projects <i class="fa-solid fa-angle-right"></i></span>
            <span class="text">view all projects <i class="fa-solid fa-angle-right"></i></span>
        </a>
    </div>
</section>
<!-- project-end -->

 <!-- showcase-start -->
@php
    $homePage = \App\Models\Page::where('slug', 'home')->first();
    $showcase = $homePage->sections['showcase'] ?? [];
    $showcaseItems = $showcase['items'] ?? [];
    // Filter only items with showcase enabled
    $activeShowcaseItems = collect($showcaseItems)->filter(function($item) {
        return ($item['showcase'] ?? true) == true;
    });
@endphp

@if($activeShowcaseItems->isNotEmpty())
<section class="bs-showcase-1-area pb-80 wa-fix">
    <div class="bs-showcase-1-slider wa-fix wa-p-relative">
        <div class="swiper-container bs-sh1-active">
            <div class="swiper-wrapper">

                @foreach($activeShowcaseItems as $item)
                @php
                    // Get image: uploaded image or project image
                    $imageUrl = null;
                    if (!empty($item['image'])) {
                        // Check if it's a storage path or full URL
                        if (is_string($item['image'])) {
                            $imageUrl = str_starts_with($item['image'], 'http') 
                                ? $item['image'] 
                                : asset('storage/' . $item['image']);
                        }
                    }
                    
                    // If no uploaded image, try to get project image
                    if (!$imageUrl && !empty($item['project_id'])) {
                        $project = \App\Models\Project::find($item['project_id']);
                        $imageUrl = $project?->getFirstMediaUrl('hero_thumbnails');
                    }
                    
                    // Fallback to default showcase image
                    if (!$imageUrl) {
                        $imageUrl = asset('assets/img/showcase/sh1-img-1.png');
                    }
                    
                    $link = $item['link'] ?? '/projects';
                    $subtitle = $item['subtitle'] ?? 'Project';
                    $title = $item['title'] ?? 'Featured Project';
                    $buttonText = $item['button_text'] ?? 'more details';
                @endphp
                
                <!-- single-slider -->
                <div class="swiper-slide">
                    <div class="bs-showcase-1-item">
                        <div class="item-img wa-fix wa-img-cover">
                            <a href="{{ url($link) }}" aria-label="{{ $subtitle }}" data-cursor-text="View">
                                <img src="{{ $imageUrl }}" alt="{{ $subtitle }}">
                            </a>
                        </div>
                        <h5 class="subtitle" data-cursor="-opaque">{{ $subtitle }}</h5>
                        <h4 class="bs-h-2 title">
                            <a href="{{ url($link) }}" aria-label="{{ $subtitle }}">{{ $title }}</a>
                        </h4>
                        <a href="{{ url($link) }}" aria-label="{{ $subtitle }}" class="item-btn bs-h-2">
                            {{ $buttonText }} <i class="fa-solid fa-angle-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach

            </div>
        </div>

        <div class="bs-showcase-1-slider-btn">
            <div class="single-btn lw-sh1-prev wa-magnetic-btn">
                <img src="{{ asset('assets/img/illus/left-arrow.png') }}" alt="">
            </div>
            <div class="single-btn lw-sh1-next wa-magnetic-btn">
                <img src="{{ asset('assets/img/illus/right-arrow.png') }}" alt="">
            </div>
        </div>
    </div>
</section>
@endif
<!-- showcase-end -->

<!-- gallery-start -->
@php
    $homePage = \App\Models\Page::where('slug', 'home')->first();
    $gallery = $homePage->sections['gallery'] ?? [];
    $galleryItems = $gallery['items'] ?? [];
    $sectionSubtitle = $gallery['section_subtitle'] ?? 'Stay Inspired with Instagram';
    $sectionTitle = $gallery['section_title'] ?? '<i class="fa-brands fa-instagram"></i> Instagram';
    $buttonText = $gallery['button_text'] ?? 'Follow Us';
    $buttonLink = $gallery['button_link'] ?? 'https://instagram.com/geometric_development';
    $starIcon = $gallery['star_icon'] ?? 'assets/img/illus/star-shape.png';
@endphp

@if(!empty($galleryItems))
<section class="bs-gallery-2-area pt-100 pb-145">
    <div class="container bs-container-1">
        <div class="bs-gallery-2-wrap">

            <!-- left -->
            <div class="left">

                <!-- section-title -->
                <div class="bs-gallery-2-sec-title wa-fix ">
                    <h6 class="bs-subtitle-1 wa-split-clr wa-capitalize">
                        <span class="icon">
                            <img src="{{ asset($starIcon) }}" alt="">
                        </span>
                        <span class="text">
                            {{ $sectionSubtitle }}
                        </span>
                    </h6>
                    <h2 class="bs-sec-title-1 wa-split-right wa-capitalize">
                        {!! $sectionTitle !!}
                    </h2>
                </div>

                @foreach($galleryItems as $index => $item)
                    @if($index < 2)
                        @php
                            // Handle image path properly
                            if (!empty($item['image'])) {
                                if (str_starts_with($item['image'], 'http')) {
                                    $imageUrl = $item['image']; // Full URL
                                } elseif (str_starts_with($item['image'], 'assets/')) {
                                    $imageUrl = asset($item['image']); // Asset path
                                } else {
                                    $imageUrl = asset('storage/' . $item['image']); // Storage path (uploaded)
                                }
                            } else {
                                $imageUrl = asset('assets/img/gallery/g2-img-' . ($index + 1) . '.png');
                            }
                            $instagramUrl = $item['instagram_url'] ?? '#';
                            $size = $item['size'] ?? 'normal';
                        @endphp
                        <!-- img -->
                        <a href="{{ $instagramUrl }}" 
                           target="_blank"
                           aria-label="Instagram post" 
                           class="bs-gallery-2-img {{ $size !== 'normal' ? $size : '' }} popup-img wa-img-cover wa-fix">
                            <img src="{{ $imageUrl }}" alt="Gallery image {{ $index + 1 }}">
                        </a>
                    @endif
                @endforeach

            </div>

            <!-- middle -->
            <div class="meddle">

                <div class="meddle-row">
                    @foreach($galleryItems as $index => $item)
                        @if($index >= 2 && $index < 4)
                            @php
                                // Handle image path properly
                                if (!empty($item['image'])) {
                                    if (str_starts_with($item['image'], 'http')) {
                                        $imageUrl = $item['image']; // Full URL
                                    } elseif (str_starts_with($item['image'], 'assets/')) {
                                        $imageUrl = asset($item['image']); // Asset path
                                    } else {
                                        $imageUrl = asset('storage/' . $item['image']); // Storage path (uploaded)
                                    }
                                } else {
                                    $imageUrl = asset('assets/img/gallery/g2-img-' . ($index + 1) . '.png');
                                }
                                $instagramUrl = $item['instagram_url'] ?? '#';
                                $size = $item['size'] ?? 'normal';
                            @endphp
                            <!-- img -->
                            <a href="{{ $instagramUrl }}" 
                               target="_blank"
                               aria-label="Instagram post" 
                               class="bs-gallery-2-img {{ $size !== 'normal' ? $size : '' }} popup-img wa-img-cover wa-fix">
                                <img src="{{ $imageUrl }}" alt="Gallery image {{ $index + 1 }}">
                            </a>
                        @endif
                    @endforeach
                </div>

                <div class="meddle-row-2">
                    @foreach($galleryItems as $index => $item)
                        @if($index >= 4 && $index < 6)
                            @php
                                // Handle image path properly
                                if (!empty($item['image'])) {
                                    if (str_starts_with($item['image'], 'http')) {
                                        $imageUrl = $item['image']; // Full URL
                                    } elseif (str_starts_with($item['image'], 'assets/')) {
                                        $imageUrl = asset($item['image']); // Asset path
                                    } else {
                                        $imageUrl = asset('storage/' . $item['image']); // Storage path (uploaded)
                                    }
                                } else {
                                    $imageUrl = asset('assets/img/gallery/g2-img-' . ($index + 1) . '.png');
                                }
                                $instagramUrl = $item['instagram_url'] ?? '#';
                                $size = $item['size'] ?? 'normal';
                            @endphp
                            <!-- img -->
                            <a href="{{ $instagramUrl }}" 
                               target="_blank"
                               aria-label="Instagram post" 
                               class="bs-gallery-2-img {{ $size !== 'normal' ? $size : '' }} popup-img wa-img-cover wa-fix">
                                <img src="{{ $imageUrl }}" alt="Gallery image {{ $index + 1 }}">
                            </a>
                        @endif
                    @endforeach
                </div>

            </div>

            <!-- right -->
            <div class="right">

                @foreach($galleryItems as $index => $item)
                    @if($index >= 6 && $index < 7)
                        @php
                            // Handle image path properly
                            if (!empty($item['image'])) {
                                if (str_starts_with($item['image'], 'http')) {
                                    $imageUrl = $item['image']; // Full URL
                                } elseif (str_starts_with($item['image'], 'assets/')) {
                                    $imageUrl = asset($item['image']); // Asset path
                                } else {
                                    $imageUrl = asset('storage/' . $item['image']); // Storage path (uploaded)
                                }
                            } else {
                                $imageUrl = asset('assets/img/gallery/g2-img-' . ($index + 1) . '.png');
                            }
                            $instagramUrl = $item['instagram_url'] ?? '#';
                            $size = $item['size'] ?? 'normal';
                        @endphp
                        <!-- img -->
                        <a href="{{ $instagramUrl }}" 
                           target="_blank"
                           aria-label="Instagram post" 
                           class="bs-gallery-2-img {{ $size !== 'normal' ? $size : '' }} popup-img wa-img-cover wa-fix">
                            <img src="{{ $imageUrl }}" alt="Gallery image {{ $index + 1 }}">
                        </a>
                    @endif
                @endforeach

                <div class="link-btn text-center">
                    <a href="{{ $buttonLink }}" target="_blank" aria-label="Follow us on Instagram" class="bs-btn-1">
                        <span class="text">
                            {{ $buttonText }}
                        </span>
                        <span class="icon">
                            <i class="fa-solid fa-right-long"></i>
                            <i class="fa-solid fa-right-long"></i>
                        </span>
                        <span class="shape"></span>
                    </a>
                </div>

            </div>

        </div>
    </div>
</section>
@endif
<!-- gallery-end -->

<!-- blog-start -->
<section class="bs-blog-3-area pt-85 pb-185">
    <div class="container bs-container-1">
        <div class="bs-blog-3-wrap wa-skew-1">
            <!-- section-title -->
            <div class="bs-blog-3-sec-title wa-p-relative text-center mb-35">
                <h6 class="bs-subtitle-1 wa-capitalize">
                    <span class="icon">
                        <img src="{{ asset('assets/img/hero/h3-start.png') }}" alt="">
                    </span>
                    <span class="text wa-split-clr ">
                        recent blog
                    </span>
                </h6>
                <h2 class="bs-sec-title-3  wa-split-right wa-capitalize" data-cursor="-opaque">news & ideas</h2>
            </div>

            <!-- blog-item -->
            <div class="bs-blog-3-item mb-85">
                @foreach($blogPosts as $post)
                <!-- single-item -->
                <div class="bs-blog-3-item-single wa-skew-1">
                    <div class="item-img wa-fix wa-img-cover">
                        <a href="{{ route('blog.show', $post->slug) }}" aria-label="name" data-cursor-text="View">
                            <img src="{{ $post->getFirstMediaUrl('featured_image') ?: asset('assets/img/random/random (10).png') }}" alt="">
                        </a>
                    </div>
                    <p class="bs-p-1 item-date">{{ $post->published_at->format('d M Y') }}</p>
                    <h4 class="bs-h-1 item-title">
                        <a href="{{ route('blog.show', $post->slug) }}" aria-label="name">{{ $post->title }}</a>
                    </h4>
                    <p class="bs-p-3 item-disc">{{ $post->excerpt }}</p>
                </div>
                @endforeach
            </div>

            <!-- all-btn -->
            <div class="bs-blog-3-all-btn text-center wa-fadeInUp">
                <a href="{{ route('blog.index') }}" aria-label="name" class="bs-btn-1 text-capitalize">
                    <span class="text">
                        view all blogs
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
<!-- blog-end -->

@endsection