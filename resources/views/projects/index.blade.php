@extends('layouts.app')

@section('title', 'Our Projects - Geometric Development')

@section('body-class', 'bs-home-4')

@section('content')
    <!-- showcase-start -->
    <section class="bs-showcase-3-area wa-fix wa-bg-default wa-p-relative" data-background="{{ asset('assets/img/hero/hero.png') }}">
        <div class="container bs-container-1">
            <div class="bs-showcase-3-wrap">

                <!-- left-content -->
                <div class="bs-showcase-3-content">

                    <!-- trust -->
                    <div class="bs-showcase-3-content-trust wa-fadeInRight">
                        <div class="icon">
                            <i class="fa-regular fa-circle-check"></i>
                        </div>
                        <div class="content">
                            <div class="bs-star-1">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <p class="bs-p-1 disc">500+ Properties Delivered</p>
                        </div>
                    </div>

                    <h3 class="bs-showcase-3-content-title bs-h-2 wa-split-right wa-capitalize">
                        <span>Discover Our</span>
                        Projects
                    </h3>

                    <div class="bs-showcase-3-content-btn wa-fadeInUp">
                        <a href="#projects" aria-label="name" class="bs-btn-1 text-capitalize">
                            <span class="text">
                                explore more
                            </span>
                            <span class="icon">
                                <i class="fa-solid fa-right-long"></i>
                                <i class="fa-solid fa-right-long"></i>
                            </span>
                            <span class="shape" ></span>
                        </a>
                    </div>
                </div>

                <!-- right-slide -->
                <div class="bs-showcase-3-slider wa-p-relative wa-fix wa-slideInUp">
                    <div class="swiper-container wa-fix bs-showc3-active">
                        <div class="swiper-wrapper">
                            @foreach($projects->take(5) as $project)
                                <!-- single-slider -->
                                <div class="swiper-slide">
                                    <div class="bs-showcase-3-slider-item">
                                        <div class="item-img wa-fix wa-img-cover ">
                                            <a href="{{ route('projects.show', $project->slug) }}" aria-label="name" data-cursor-text="View">
                                                <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (10).png') }}" alt="">
                                            </a>
                                        </div>
                                        <h5 class="bs-h-1 item-title">
                                            <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">{{ $project->title }}</a>
                                        </h5>
                                        <p class="bs-p-1 item-location">{{ $project->location }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bs-showcase-3-slider-pagi bs-showc3-pagi">
                    </div>

                    <div class="bs-showcase-3-slider-btn ">
                        <div class="btn-elm bs-showc-3-prev">
                            <i class="fa-solid fa-left-long"></i>
                        </div>
                        <div class="btn-elm bs-showc-3-next">
                            <i class="fa-solid fa-right-long"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- showcase-end -->

     <!-- counter-start -->
    <section class="bs-counter-2-area pt-150 pb-50">
        <div class="container bs-container-1">
            <div class="bs-counter-2-wrap" style="flex-wrap: nowrap; gap: 40px;">

                <!-- single-item -->
                <div class="bs-counter-2-item">
                    <h4 class="bs-h-2 item-number counter wa-counter" data-cursor="-opaque">15</h4>
                    <p class="item-disc bs-p-1">Years of Excellence</p>
                </div>

                <!-- shape -->
                <h6 class="bs-h-2 shape">*</h6>

                <!-- single-item -->
                <div class="bs-counter-2-item">
                    <h4 class="bs-h-2 item-number counter wa-counter" data-cursor="-opaque">2500</h4>
                    <p class="item-disc bs-p-1">Properties Delivered</p>
                </div>

                <!-- shape -->
                <h6 class="bs-h-2 shape">*</h6>
                <!-- single-item -->
                <div class="bs-counter-2-item">
                    <h4 class="bs-h-2 item-number counter wa-counter" data-cursor="-opaque">12</h4>
                    <p class="item-disc bs-p-1">Active Projects</p>
                </div>

                <h6 class="bs-h-2 shape">*</h6>

                <!-- single-item -->
                <div class="bs-counter-2-item">
                    <h4 class="bs-h-2 item-number counter wa-counter" data-cursor="-opaque">98</h4>
                    <p class="item-disc bs-p-1">Happy Clients</p>
                </div>

            </div>
        </div>
    </section>
    <!-- counter-end -->

    <!-- project-start -->
    <section id="projects" class="bs-project-4-area pt-125 wa-fix">
        <div class="container bs-container-2">

            <div class="bs-project-4-height">

                <div class="bs-project-4-content wa-fix ">
                    <h3 class="bs-h-4 big-title title " >Latest</h3>
                    <h3 class="bs-h-4 title title-2">
                        <span class="left-text">TRE</span>
                        <span class="right-text">NDS</span>
                    </h3>
                </div>

                <div class="bs-project-4-card-pin">
                    <div class="bs-project-4-card ">
                        @foreach($projects->take(4) as $index => $project)
                            <!-- single-card -->
                            <div class="bs-project-4-card-single has-card-{{ $index + 1 }}">
                                <div class="card-img wa-fix wa-img-cover">
                                    <a href="{{ route('projects.show', $project->slug) }}" aria-label="name" data-cursor-text="View" class="popup-img">
                                        <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (' . (10 + $index) . ').png') }}" alt="">
                                    </a>
                                </div>
                                <div class="content">
                                    <h5 class="bs-h-4 title">
                                        <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">{{ $project->title }}</a>
                                    </h5>
                                    <ul class="card-details wa-list-style-none">
                                        <li class="bs-p-4">
                                            <span>Location:</span>
                                            {{ $project->location }}
                                        </li>
                                        <li class="bs-p-4">
                                            <span>Type:</span>
                                            {{ $project->categories->pluck('name')->join(', ') ?: $project->type }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>


        </div>
    </section>
    <!-- project-end -->

    <!-- core-services-start -->
    <section class="bs-core-services-2-area wa-bg-default wa-fix" data-background="{{ asset('assets/img/random/geometric1.png') }}">
        <div class="bs-core-services-2-wrap">
            @foreach($projects->where('is_featured', true)->take(4) as $featuredProject)
                <!-- single-item -->
                <div class="bs-core-services-2-item ">
                    <h5 class="bs-h-2 item-title ">
                        <span class="wa-split-up wa-capitalize wa-fix">
                            {{ $featuredProject->title }}
                        </span>
                      
                        <span class="small-text">{{ $featuredProject->location }}</span>
                    </h5>
                    <div class="content-wrap">
                        <h5 class="bs-h-2 item-title">
                            {{ $featuredProject->title }}
                            <span class="small-text">{{ $featuredProject->location }}</span>
                        </h5>

                        <p class="bs-p-1 item-disc">
                            {{ Str::limit($featuredProject->excerpt, 200) }}
                        </p>

                        <div class="item-btn">
                            <a href="{{ route('projects.show', $featuredProject->slug) }}" aria-label="name" class="bs-btn-1">
                                <span class="text">
                                    View Project
                                </span>
                                <span class="shape" ></span>
                            </a>
                        </div>
                    </div>

                    <div class="item-img wa-fix wa-img-cover">
                        <img src="{{ $featuredProject->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (12).png') }}" alt="">
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    <!-- core-services-end -->

     <!-- video-start -->
    <div class="bs-video-5-area wa-fix wa-p-relative wa-img-cover" style="margin-top: 150px;">
        <img class="wa-parallax-img" src="{{ asset('assets/img/random/random (27).png') }}" alt="">
        <div class="bs-video-5-btn">
            <a href="https://www.youtube.com/watch?v=e45TPIcx5CY" aria-label="name" class="bs-play-btn-5 video-popup wa-magnetic-btn">
                <i class="fa-solid fa-play"></i>
            </a>
        </div>
    </div>
    <!-- video-end -->   
    
    <!-- testimonial-start -->
    <section class="bs-testimonial-4-area wa-fix wa-p-relative pt-130 pb-50 ">

        <div class="bs-testimonial-4-bg" data-background="{{ asset('assets/img/testimonial/t4-bg.png') }}"></div>

        <div class="container bs-container-2">
            <div class="bs-testimonial-4-content">
                <div class="inner-div" style="display: block; text-align: center;">
                    <h2 class="bs-sec-title-4 title wa-split-right wa-capitalize" data-cursor="-opaque" style="color: #000; max-width: 100%; margin-left: auto; margin-right: auto;">Building Dreams Across Horizons</h2>

                    <p class="bs-p-4" style="max-width: 800px; margin: 30px auto 0; line-height: 1.8; color: #000; text-align: center;">
                        Embark on a journey where the serenity of nature meets the elegance of modern living. Our developments invite you to experience pristine landscapes, luxurious living spaces, and a lifestyle that marries tranquillity with sophistication. From coastal paradises to vibrant urban communities, we create spaces where your dreams take shape.
                    </p>

                </div>
            </div>
        </div>
    </section>
    <!-- testimonial-end -->

    <!-- services-start -->
    <section class="bs-services-1-area wa-fix pt-130 pb-130">
        <div class="container bs-container-1">
            <!-- section-title -->
            <div class="bs-services-1-sec-title mb-40">

                <!-- left-title -->
                <div class="left">
                    <h6 class="bs-subtitle-1 wa-split-clr wa-capitalize">
                        <span class="icon">
                            <img src="{{ asset('assets/img/illus/star-shape.png') }}" alt="">
                        </span>
                        Our Projects
                    </h6>
                    <h2 class="bs-sec-title-1 wa-split-right wa-capitalize" data-cursor="-opaque">Our Projects Across Egypt & UAE</h2>
                </div>

                <!-- right-tabs-btn -->

                <ul class="bs-services-1-tabs-btn wa-list-style-none wa-fadeInRight" role="tablist">
                    <!-- single-btn -->
                    <li class="nav-item" role="presentation">
                      <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">
                        All
                        <span class="bg-shape">
                            <img src="{{ asset('assets/img/services/s1-btn-shape.png') }}" alt="">
                        </span>
                      </button>
                    </li>

                    <!-- single-btn -->                            
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="villas-tab" data-bs-toggle="tab" data-bs-target="#villas" type="button" role="tab" aria-controls="villas" aria-selected="false">
                            Villas
                            <span class="bg-shape">
                                <img src="{{ asset('assets/img/services/s1-btn-shape.png') }}" alt="">
                            </span>
                        </button>
                    </li>

                    <!-- single-btn -->                            
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="apartments-tab" data-bs-toggle="tab" data-bs-target="#apartments" type="button" role="tab" aria-controls="apartments" aria-selected="false">
                            Apartments
                            <span class="bg-shape">
                                <img src="{{ asset('assets/img/services/s1-btn-shape.png') }}" alt="">
                            </span>
                        </button>
                    </li>

                    <!-- single-btn -->                            
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="commercial-tab" data-bs-toggle="tab" data-bs-target="#commercial" type="button" role="tab" aria-controls="commercial" aria-selected="false">
                            Commercial
                            <span class="bg-shape">
                                <img src="{{ asset('assets/img/services/s1-btn-shape.png') }}" alt="">
                            </span>
                        </button>
                    </li>

                    <!-- single-btn -->                            
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="investment-tab" data-bs-toggle="tab" data-bs-target="#investment" type="button" role="tab" aria-controls="investment" aria-selected="false">
                            Investment
                            <span class="bg-shape">
                                <img src="{{ asset('assets/img/services/s1-btn-shape.png') }}" alt="">
                            </span>
                        </button>
                    </li>

                </ul>
            </div>
        </div>

        <!-- content -->
        <div class="tab-content bs-services-1-tabs-pane ">

            <!-- single-pane -->
            <div class="tab-pane fade fadeInUp animated show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                <div class="bs-services-1-slider">
                    <div class="swiper-container wa-fix bs-s1-active">
                        <div class="swiper-wrapper">
                            @foreach($projects as $project)
                                <!-- single-item -->
                                <div class="swiper-slide">
                                    <div class="bs-services-1-item">
                                        <div class="main-img  wa-fix wa-img-cover" data-cursor-text="View" style="position: relative;">
                                            <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">
                                                <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (14).png') }}" alt="">
                                            </a>
                                            <span style="position: absolute; top: 15px; right: 15px; background: linear-gradient(135deg, #d4af37 0%, #f4e4b5 100%); padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; color: #000; z-index: 10;">For Sale</span>
                                        </div>
                                        <div class="content-wrap">
                                            <div class="shape">
                                                <img src="{{ asset('assets/img/illus/star-shape-color.png') }}" alt="">
                                            </div>
                                            <div class="content">
                                                <h5 class="bs-h-1 subtitle">{{ $project->location }}</h5>
                                                <h3 class="bs-h-1 title">
                                                    <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">{{ $project->title }}</a>
                                                </h3>

                                                <a class="item-btn wa-magnetic-btn" href="{{ route('projects.show', $project->slug) }}" aria-label="name">
                                                    <i class="fa-solid fa-link"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- single-pane -->
            <div class="tab-pane fade fadeInUp animated" id="villas" role="tabpanel" aria-labelledby="villas-tab">
                <div class="bs-services-1-slider">
                    <div class="swiper-container wa-fix bs-s1-active">
                        <div class="swiper-wrapper">
                            @foreach($projects->where('type', 'Residential') as $project)
                                <!-- single-item -->
                                <div class="swiper-slide">
                                    <div class="bs-services-1-item">
                                        <div class="main-img  wa-fix wa-img-cover" data-cursor-text="View">
                                            <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">
                                                <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (19).png') }}" alt="">
                                            </a>
                                        </div>
                                        <div class="content-wrap">
                                            <div class="shape">
                                                <img src="{{ asset('assets/img/illus/star-shape-color.png') }}" alt="">
                                            </div>
                                            <div class="content">
                                                <h5 class="bs-h-1 subtitle">{{ $project->location }}</h5>
                                                <h3 class="bs-h-1 title">
                                                    <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">{{ $project->title }}</a>
                                                </h3>

                                                <a class="item-btn wa-magnetic-btn" href="{{ route('projects.show', $project->slug) }}" aria-label="name">
                                                    <i class="fa-solid fa-link"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- single-pane -->
            <div class="tab-pane fade fadeInUp animated" id="apartments" role="tabpanel" aria-labelledby="apartments-tab">
                <div class="bs-services-1-slider">
                    <div class="swiper-container wa-fix bs-s1-active">
                        <div class="swiper-wrapper">
                            @foreach($projects->where('type', 'Residential') as $project)
                                <!-- single-item -->
                                <div class="swiper-slide">
                                    <div class="bs-services-1-item">
                                        <div class="main-img  wa-fix wa-img-cover" data-cursor-text="View">
                                            <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">
                                                <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (19).png') }}" alt="">
                                            </a>
                                        </div>
                                        <div class="content-wrap">
                                            <div class="shape">
                                                <img src="{{ asset('assets/img/illus/star-shape-color.png') }}" alt="">
                                            </div>
                                            <div class="content">
                                                <h5 class="bs-h-1 subtitle">{{ $project->location }}</h5>
                                                <h3 class="bs-h-1 title">
                                                    <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">{{ $project->title }}</a>
                                                </h3>

                                                <a class="item-btn wa-magnetic-btn" href="{{ route('projects.show', $project->slug) }}" aria-label="name">
                                                    <i class="fa-solid fa-link"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- single-pane -->
            <div class="tab-pane fade fadeInUp animated" id="commercial" role="tabpanel" aria-labelledby="commercial-tab">
                <div class="bs-services-1-slider">
                    <div class="swiper-container wa-fix bs-s1-active">
                        <div class="swiper-wrapper">
                            @foreach($projects->where('type', 'Commercial') as $project)
                                <!-- single-item -->
                                <div class="swiper-slide">
                                    <div class="bs-services-1-item">
                                        <div class="main-img  wa-fix wa-img-cover" data-cursor-text="View">
                                            <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">
                                                <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (19).png') }}" alt="">
                                            </a>
                                        </div>
                                        <div class="content-wrap">
                                            <div class="shape">
                                                <img src="{{ asset('assets/img/illus/star-shape-color.png') }}" alt="">
                                            </div>
                                            <div class="content">
                                                <h5 class="bs-h-1 subtitle">{{ $project->location }}</h5>
                                                <h3 class="bs-h-1 title">
                                                    <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">{{ $project->title }}</a>
                                                </h3>

                                                <a class="item-btn wa-magnetic-btn" href="{{ route('projects.show', $project->slug) }}" aria-label="name">
                                                    <i class="fa-solid fa-link"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- single-pane -->
            <div class="tab-pane fade fadeInUp animated" id="investment" role="tabpanel" aria-labelledby="investment-tab">
                <div class="bs-services-1-slider">
                    <div class="swiper-container wa-fix bs-s1-active">
                        <div class="swiper-wrapper">
                            @foreach($projects->where('type', 'Investment') as $project)
                                <!-- single-item -->
                                <div class="swiper-slide">
                                    <div class="bs-services-1-item">
                                        <div class="main-img  wa-fix wa-img-cover" data-cursor-text="View">
                                            <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">
                                                <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (19).png') }}" alt="">
                                            </a>
                                        </div>
                                        <div class="content-wrap">
                                            <div class="shape">
                                                <img src="{{ asset('assets/img/illus/star-shape-color.png') }}" alt="">
                                            </div>
                                            <div class="content">
                                                <h5 class="bs-h-1 subtitle">{{ $project->location }}</h5>
                                                <h3 class="bs-h-1 title">
                                                    <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">{{ $project->title }}</a>
                                                </h3>

                                                <a class="item-btn wa-magnetic-btn" href="{{ route('projects.show', $project->slug) }}" aria-label="name">
                                                    <i class="fa-solid fa-link"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>
    <!-- services-end -->            
            
    <!-- contact-start -->
    <section class="bs-contact-1-area pt-130 pb-100 wa-p-relative">

        <div class="bs-contact-1-bg-color"></div>

        <div class="container bs-container-1">

            <!-- section-title -->
            <div class="bs-contact-1-sec-title text-center mb-45">
                <h6 class="bs-subtitle-1 wa-split-clr wa-capitalize ">
                    <span class="icon">
                        <img src="{{ asset('assets/img/illus/star-shape.png') }}" alt="">
                    </span>
                    contact us
                </h6>
                <h2 class="bs-sec-title-1 wa-split-right wa-capitalize" data-cursor="-opaque">Get in Touch</h2>
            </div>

            <div class="bs-contact-1-wrap">

                <!-- left-content -->
                <div class="bs-contact-1-left">

                    <!-- img -->
                    <div class="bs-contact-1-img wa-fix wa-img-cover" data-cursor="-opaque">
                        <img class="wa-parallax-img" src="{{ asset('assets/img/random/random (23).png') }}" alt="">
                    </div>

                    <div class="bs-contact-1-video wa-clip-top-bottom">
                        <div class="bg-img wa-fix wa-img-cover">
                            <img src="{{ asset('assets/img/random/random (2).png') }}" alt="">
                        </div>
                        <a href="https://www.youtube.com/watch?v=e45TPIcx5CY" aria-label="name" class="bs-play-btn-2 wa-magnetic-btn bs-p-1 popup-video">
                            <span class="icon">
                                <i class="fa-solid fa-play"></i>
                            </span>
                            <span class="text">10 years experience</span>
                        </a>
                    </div>
                </div>

                <!-- right-content -->
                <div class="bs-contact-1-right">

                    <!-- form -->
                    <form action="{{ route('contact.store') }}" method="POST" class="bs-form-1 mb-100">
                        @csrf
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
    
                        <div class="bs-form-1-item">
                            <label class="bs-form-1-item-label" for="name"><img src="{{ asset('assets/img/contact/c1-star.png') }}" alt="">name*</label>
                            <input id="name" name="name" class="bs-form-1-item-input wa-clip-left-right" type="text" aria-label="name" value="{{ old('name') }}">
                            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="bs-form-1-item">
                            <label class="bs-form-1-item-label" for="email"><img src="{{ asset('assets/img/contact/c1-star.png') }}" alt="">email*</label>
                            <input id="email" name="email" class="bs-form-1-item-input wa-clip-left-right" type="email" aria-label="name" value="{{ old('email') }}">
                            @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="bs-form-1-item">
                            <label class="bs-form-1-item-label" for="phone"><img src="{{ asset('assets/img/contact/c1-star.png') }}" alt="">phone*</label>
                            <input id="phone" name="phone" class="bs-form-1-item-input wa-clip-left-right" type="text" aria-label="phone" value="{{ old('phone') }}">
                            @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="bs-form-1-item has-full-width">
                            <label class="bs-form-1-item-label" for="user-type">Are you Individual, Broker, or Investor?</label>
                            <select id="user-type" name="user_type" class="bs-form-1-item-input wa-clip-left-right" aria-label="name">
                                <option value="">Select an option</option>
                                <option value="individual" {{ old('user_type') == 'individual' ? 'selected' : '' }}>Individual</option>
                                <option value="broker" {{ old('user_type') == 'broker' ? 'selected' : '' }}>Broker</option>
                                <option value="investor" {{ old('user_type') == 'investor' ? 'selected' : '' }}>Investor</option>
                            </select>
                            @error('user_type') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <div class="bs-form-1-item has-full-width">
                            <label class="bs-form-1-item-label" for="message"><img src="{{ asset('assets/img/contact/c1-star.png') }}" alt="">message*</label>
                            <textarea class="bs-form-1-item-input wa-clip-left-right" name="message" id="message">{{ old('message') }}</textarea>
                            @error('message') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <input type="hidden" name="type" value="contact">
                        <input type="hidden" name="subject" value="General Inquiry">
                        <div class="bs-form-1-item has-no-after has-full-width wa-clip-left-right">
                            <div class="bs-form-1-item-checkbox">
                                <input type="checkbox" id="check-1" aria-label="name">
                                <label for="check-1" class="bs-p-1">I agree to the Terms & Conditions and Privacy Policy of Geometric Development real estate services.</label>
                            </div>
                        </div>
                        <div class="bs-form-1-item has-no-after has-full-width wa-clip-left-right">
                            <button class="bs-btn-1" type="submit" aria-label="name">
                                <span class="text">
                                    contact us
                                </span>
                                <span class="icon">
                                    <i class="fa-solid fa-right-long"></i>
                                    <i class="fa-solid fa-right-long"></i>
                                </span>
                                <span class="shape"></span>
                            </button>
                        </div>
                    </form>

                    <!-- counter -->
                    <div class="bs-contact-1-counter">
                        <div class="bs-contact-1-counter-item">
                            <h4 class="bs-h-1 number counter wa-counter" data-cursor="-opaque">28</h4>
                            <p class="bs-p-1 disc">Years Experience</p>
                        </div>
                        <div class="bs-contact-1-counter-item">
                            <div class="shape">
                                <img src="{{ asset('assets/img/illus/star-shape.png') }}" alt="">
                            </div>
                        </div>
                        <div class="bs-contact-1-counter-item">
                            <h4 class="bs-h-1 number counter wa-counter" data-cursor="-opaque">99</h4>
                            <p class="bs-p-1 disc">projects
                                completed</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- contact-end -->

    <!-- Filtering UI -->
    <section class="filter-section pt-50 pb-50">
        <div class="container">
            <form method="GET" action="{{ route('projects.index') }}" class="d-flex justify-content-center gap-3">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="Residential" {{ request('type') == 'Residential' ? 'selected' : '' }}>Residential</option>
                    <option value="Mixed Use" {{ request('type') == 'Mixed Use' ? 'selected' : '' }}>Mixed Use</option>
                    <option value="Commercial" {{ request('type') == 'Commercial' ? 'selected' : '' }}>Commercial</option>
                    <option value="Investment" {{ request('type') == 'Investment' ? 'selected' : '' }}>Investment</option>
                </select>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="planning" {{ request('status') == 'planning' ? 'selected' : '' }}>Planning</option>
                    <option value="under-construction" {{ request('status') == 'under-construction' ? 'selected' : '' }}>Under Construction</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="sold-out" {{ request('status') == 'sold-out' ? 'selected' : '' }}>Sold Out</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('projects.index') }}" class="btn btn-secondary">Clear</a>
            </form>
        </div>
    </section>

    <!-- Pagination -->
    @if($projects->hasPages())
        <div class="pagination-section pt-50 pb-50">
            <div class="container">
                {{ $projects->links() }}
            </div>
        </div>
    @endif
@endsection