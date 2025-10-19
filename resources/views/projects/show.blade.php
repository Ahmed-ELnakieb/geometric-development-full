@extends('layouts.project-details')

@section('title', $project->meta_title ?? $project->title . ' - Geometric Development')
@section('meta-description', $project->meta_description ?? $project->excerpt)
@section('body-class', 'bs-home-4')

@section('content')
<!-- hero-start -->
<section class="bs-hero-4-area wa-p-relative pt-90 wa-fix">
    <div class="container bs-container-2">
        <div class="bs-hero-4-content">
            <!-- back-to-projects-btn -->
            <a href="{{ route('projects.index') }}" class="back-to-projects-link" aria-label="Back to Projects">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Back to Our Projects</span>
            </a>

            <h1 class="bs-hero-4-title bs-h-4 wa-split-y" data-split-delay="1s">
                {{ $project->title }}
            </h1>
            <h3 class="bs-hero-4-subtitle bs-h-4 wa-split-y" data-split-delay="1.2s" style="font-size: 32px; margin-top: 10px; font-weight: 400;">
                {{ $project->location }}
            </h3>
            <div class="inner-div">
                <a href="{{ route('contact.index') }}" aria-label="Contact" class="bs-hero-4-circle-btn wa-magnetic-btn">
                    <span class="btn-icon ">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.00001 7C8.00001 6.73478 8.10537 6.48043 8.2929 6.29289C8.48044 6.10536 8.73479 6 9.00001 6H17C17.2652 6 17.5196 6.10536 17.7071 6.29289C17.8947 6.48043 18 6.73478 18 7V15C18 15.2652 17.8947 15.5196 17.7071 15.7071C17.5196 15.8946 17.2652 16 17 16C16.7348 16 16.4804 15.8946 16.2929 15.7071C16.1054 15.5196 16 15.2652 16 15V9.414L7.70701 17.707C7.51841 17.8892 7.26581 17.99 7.00361 17.9877C6.74141 17.9854 6.4906 17.8802 6.30519 17.6948C6.11978 17.5094 6.01461 17.2586 6.01234 16.9964C6.01006 16.7342 6.11085 16.4816 6.29301 16.293L14.586 8H9.00001C8.73479 8 8.48044 7.89464 8.2929 7.70711C8.10537 7.51957 8.00001 7.26522 8.00001 7Z" fill="#F16319"/>
                        </svg>
                    </span>
                    <img src="{{ asset('assets/img/illus/h4-circle-text.png') }}" alt="">
                </a>
                <p class="bs-p-4 bs-hero-4-disc wa-split-y" data-split-delay="1.6s">{{ $project->excerpt }}</p>
            </div>
        </div>
    </div>

    <!-- slider -->
    <div class="bs-hero-4-slider">

        <div class="bs-hero-4-slider-img">
            <div class="swiper-container bs-h4-img-active wa-fix">
                <div class="swiper-wrapper">
                    @php
                        $heroSliderImages = $project->getMedia('hero_slider');
                        if ($heroSliderImages->isEmpty()) {
                            $heroSliderImages = $project->getMedia('gallery')->take(4);
                        }
                    @endphp
                    @forelse($heroSliderImages as $image)
                        <!-- single-slide -->
                        <div class="swiper-slide">
                            <div class="bs-hero-4-slider-img-item ">
                                <div class="main-img wa-img-cover">
                                    <img src="{{ $image->getUrl() }}" alt="">
                                </div>
                                <!-- trusted -->
                                <div class="bs-hero-4-slider-img-item-trusted">
                                    <h5 class="bs-h-4 title">Trusted</h5>
                                    <p class="bs-p-4 ratting" >
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        4.8 view
                                    </p>

                                    <p class="bs-p-4 disc">
                                        {{ $project->title }} Developer
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- fallback slide -->
                        <div class="swiper-slide">
                            <div class="bs-hero-4-slider-img-item ">
                                <div class="main-img wa-img-cover">
                                    <img src="{{ asset('assets/img/random/random (10).png') }}" alt="">
                                </div>
                                <!-- trusted -->
                                <div class="bs-hero-4-slider-img-item-trusted">
                                    <h5 class="bs-h-4 title">Trusted</h5>
                                    <p class="bs-p-4 ratting" >
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        4.8 view
                                    </p>

                                    <p class="bs-p-4 disc">
                                        {{ $project->title }} Developer
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        @php
            // Get hero thumbnails, or use hero slider images as fallback
            $heroThumbnails = $project->getMedia('hero_thumbnails');
            if ($heroThumbnails->isEmpty()) {
                $heroSliderImages = $project->getMedia('hero_slider');
                if ($heroSliderImages->isEmpty()) {
                    $heroThumbnails = $project->getMedia('gallery')->take(4);
                } else {
                    $heroThumbnails = $heroSliderImages;
                }
            }
        @endphp

        @if($heroThumbnails->count() > 0)
        <div class="bs-hero-4-slider-thum">
            <div class="swiper-container bs-h4-thum-active wa-fix">
                <div class="swiper-wrapper">
                    @foreach($heroThumbnails as $image)
                        <!-- single-slide -->
                        <div class="swiper-slide">
                            <div class="bs-hero-4-slider-thum-item wa-img-cover wa-fix">
                                <img src="{{ $image->hasGeneratedConversion('thumb') ? $image->getUrl('thumb') : $image->getUrl() }}" alt="">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        </div>

        <h2 class="bs-hero-4-big-title bs-h-4 wa-split-up" data-split-delay="1.5s">{{ $project->title }}</h2>
    </div>


    <div class="bs-hero-4-bg" data-background="{{ asset('assets/img/hero/h4-bg.png') }}"></div>
</section>
<!-- hero-end -->

<!-- project-details-start -->
<section class="bs-project-details-area pb-100 wa-p-relative">
    <div class="container bs-container-1">

        <ul class="bs-project-details-meta">
            <li><b>Status:</b>{{ ucfirst(str_replace('_', '-', $project->status)) }}</li>
            <li><b>Total Units:</b>{{ $project->total_units ?? 'N/A' }}</li>
            <li><b>Unit types:</b>{{ $project->unitTypes->pluck('name')->join(', ') }}</li>
            <li><b>Property size (sq ft):</b>{{ $project->property_size_min ?? 'N/A' }}-{{ $project->property_size_max ?? 'N/A' }}</li>
            <li><b>Completion date:</b>{{ $project->completion_date ? 'Q' . ceil($project->completion_date->month / 3) . ' - ' . $project->completion_date->format('Y') : 'TBA' }}</li>
        </ul>

    </div>
</section>
<!-- project-details-end -->

 <!-- about-start -->
<section class="bs-about-project-area bs-team-4-area pt-100 pb-100 wa-fix">
    <div class="container bs-container-2">


        <h5 class="bs-subtitle-4 bs-team-4-subtitle">
            <span class="text">About {{ $project->title }}</span>
        </h5>

        <div class="bs-team-4-wrap">

            <!-- left-content -->
            <div class="bs-team-4-content">

                <h2 class="bs-sec-title-4 title wa-split-right wa-capitalize" data-cursor="-opaque">Learn More About {{ $project->title }}</h2>

                {{-- Description output: Content is admin-controlled and sanitized upstream --}}
                @if(!empty($project->description))
                    @php
                        // Sanitize HTML output - allow only safe tags
                        $allowedTags = '<p><br><ul><ol><li><strong><b><em><i><h2><h3><h4><a><span>';
                        $sanitizedDescription = strip_tags($project->description, $allowedTags);
                    @endphp
                    <p class="bs-p-4 disc wa-fadeInUp">{!! $sanitizedDescription !!}</p>
                @endif

                <div class="btn-wrap wa-fadeInUp">
                    @php
                        $brochure = $project->getFirstMedia('brochure') ?? $project->getMedia('documents')->firstWhere('custom_properties.type', 'brochure');
                        $factsheet = $project->getFirstMedia('factsheet') ?? $project->getMedia('documents')->firstWhere('custom_properties.type', 'factsheet');
                    @endphp
                    @if($brochure)
                        <a href="{{ $brochure->getUrl() }}" aria-label="Download Brochure" class="bs-pr-btn-2" download>
                            <span class="text" data-back="Download Brochure" data-front="Download Brochure"></span>
                            <span class="line-1"></span>
                            <span class="line-2"></span>
                            <span class="box box-1"></span>
                            <span class="box box-2"></span>
                            <span class="box box-3"></span>
                            <span class="box box-4"></span>
                        </a>
                    @endif
                    @if($factsheet)
                        <a href="{{ $factsheet->getUrl() }}" aria-label="Download Factsheet" class="bs-pr-btn-2" download>
                            <span class="text" data-back="Download Factsheet" data-front="Download Factsheet"></span>
                            <span class="line-1"></span>
                            <span class="line-2"></span>
                            <span class="box box-1"></span>
                            <span class="box box-2"></span>
                            <span class="box box-3"></span>
                            <span class="box box-4"></span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- right-img -->
            <div class="bs-team-4-img wa-fix wa-img-cover wa-clip-left-right">
                <img src="{{ $project->getMedia('about_image')->first()?->getUrl() ?? $project->getMedia('gallery')->skip(1)->first()?->getUrl() ?? asset('assets/img/random/random (11).png') }}" alt="">
            </div>
        </div>


    </div>
</section>
<!-- about-end -->

  <!-- marquee-text-1-start -->
<section class="bs-marquee-text-1-area wa-fix mb-20">
    <div class="bs-marquee-text-1-active">
        <div class="bs-marquee-text-1-wrap" data-cursor="-opaque">
            <h5 class="bs-h-1 title">Register your details, and one of our team will guide you through the {{ $project->title }} journey *</h5>
            <h5 class="bs-h-1 title">Register your details, and one of our team will guide you through the {{ $project->title }} journey *</h5>
            <h5 class="bs-h-1 title">Register your details, and one of our team will guide you through the {{ $project->title }} journey *</h5>
        </div>
    </div>
</section>
<!-- marquee-text-1-end -->

<!-- project-start -->
<section class="bs-project-4-area pt-125 wa-fix">
    <div class="container bs-container-2">
        <h5 class="bs-subtitle-4 bs-project-4-subtitle">
            <span class="text">Unit Types</span>
        </h5>

        <div class="bs-project-4-height">

            <div class="bs-project-4-content wa-fix ">
                <h3 class="bs-h-4 big-title title " >Available</h3>
                <h3 class="bs-h-4 title title-2">
                    <span class="left-text">Uni</span>
                    <span class="right-text">ts</span>
                </h3>
            </div>

            <div class="bs-project-4-card-pin">
                <div class="bs-project-4-card ">
                    @php
                        $cardClasses = ['has-card-1', 'has-card-2', 'has-card-3', 'has-card-4'];
                        $unitTypes = $project->unitTypes()->ordered()->get()->take(4);
                        $unitTypesCount = $unitTypes->count();
                    @endphp
                    @foreach($unitTypes as $index => $unitType)
                        <!-- single-card -->
                        <div class="bs-project-4-card-single {{ $cardClasses[$index] }}">
                            <div class="card-img wa-fix wa-img-cover">
                                <a href="{{ route('projects.show', $project->slug) }}" aria-label="View" data-cursor-text="View">
                                    <img src="{{ $unitType->image?->getUrl() ?? asset('assets/img/random/random (17).png') }}" alt="">
                                </a>
                            </div>
                            <div class="content">
                                <h5 class="bs-h-4 title">
                                    <a href="{{ route('projects.show', $project->slug) }}" aria-label="View">{{ $unitType->name }}</a>
                                </h5>
                                <ul class="card-details wa-list-style-none">
                                    <li class="bs-p-4">
                                        <span>Size:</span>
                                        {{ $unitType->size_min }}-{{ $unitType->size_max }} sq ft
                                    </li>
                                    <li class="bs-p-4">
                                        <span>Features:</span>
                                        {{ $unitType->description }}
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

<!-- amenities-section-start -->
<section class="property-amenities-section" style="background-color: #f5f5f0; padding: 80px 0;">
    <div class="container">
        <!-- Section Title -->
        <div class="amenities-title" style="text-align: center; margin-bottom: 60px;">
            <h2 style="font-size: 36px; font-weight: 400; letter-spacing: 2px; color: #000; text-transform: uppercase; font-family: 'Playfair Display', serif;">A Haven of Amenities</h2>
        </div>

        <!-- Amenities Grid - Easy to edit from dashboard -->
        <div class="amenities-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 60px 80px; max-width: 1200px; margin: 0 auto;">
            @foreach($project->amenities()->ordered()->get() as $amenity)
                <!-- Amenity Item -->
                <div class="amenity-item" data-amenity-id="{{ $amenity->id }}">
                    @if($amenity->icon)
                        <i class="{{ $amenity->icon }}"></i>
                    @endif
                    <h3 class="amenity-title" style="font-size: 16px; font-weight: 600; letter-spacing: 1.5px; margin-bottom: 12px; color: #000; text-transform: uppercase;">{{ $amenity->title }}</h3>
                    <p class="amenity-description" style="font-size: 14px; line-height: 1.6; color: #666; margin: 0;">{{ $amenity->description }}</p>
                </div>
            @endforeach
            @if($project->amenities->isEmpty())
                <p>Amenity information coming soon.</p>
            @endif
        </div>
    </div>
</section>
<!-- amenities-section-end -->


<!-- video-start -->
@if($project->video_url || $project->videoFile)
<div class="bs-video-1-area wa-fix">
    <div class="bs-video-1-content wa-p-relative">
        <div class="bs-video-1-content-img has-video-2  wa-p-relative wa-fix wa-img-cover">
            @if($project->video_url)
                <a href="{{ $project->video_url }}" aria-label="Play Video" class="popup-video" data-cursor-text="play" >
                    <!-- Video thumbnail or poster image can be added here if available -->
                </a>
            @elseif($project->videoFile)
                <video class="wa-parallax-img"  src="{{ $project->videoFile->getUrl() }}" autoplay loop muted></video>
            @endif

            <div class="bs-video-1-play-btn ">
                @if($project->video_url)
                    <a href="{{ $project->video_url }}" aria-label="Play Video" class="bs-play-btn-3 wa-magnetic popup-video">
                        <span class="icon wa-magnetic-btn">
                            <i class="fa-solid fa-play"></i>
                        </span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
<!-- video-end -->

<!-- properties-gallery-start -->
<section class="bs-projects-3-area wa-fix">
    <div class="container bs-container-1">
        <div class="bs-projects-3-content mb-75">

            <!-- section-title -->
            <div class="bs-projects-3-sec-title ">
                <h6 class="bs-subtitle-1 wa-split-clr wa-capitalize">
                    <span class="icon">
                        <img src="{{ asset('assets/img/hero/h3-start.png') }}" alt="">
                    </span>
                    Gallery
                </h6>
                <h2 class="bs-sec-title-3  wa-split-right wa-capitalize" data-cursor="-opaque">Gallery</h2>
            </div>

        </div>


        <div class="tab-content bs-projects-3-tabs-pane mb-65 wa-fadeInUp" >

            <!-- single-pane -->
            <div class="tab-pane fade animated fadeInUp show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="bs-projects-3-slider">
                    <div class="swiper-container bs-p3-active wa-fix">
                        <div class="swiper-wrapper">
                            @php $sizes = ['has-lg-size', 'has-md-size', 'has-sm-size', '']; @endphp
                            @foreach($project->getMedia('gallery') as $index => $galleryImage)
                                <!-- single-slide -->
                                <div class="swiper-slide">
                                    <div class="bs-projects-3-item {{ $sizes[$index % 4] }}">
                                        <div class="item-img wa-fix wa-img-cover" >
                                            <img src="{{ $galleryImage->getUrl() }}" alt="">
                                        </div>
                                        <a  href="{{ route('projects.show', $project->slug) }}" class="bs-h-2 title" data-cursor-text="View">{{ $galleryImage->name }}</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="bs-projects-3-slider-pagi bs-pagination-1  bs-p3-pagi wa-fadeInUp">

        </div>
    </div>
</section>
<!-- properties-gallery-end -->

<!-- contact-start -->
<section class="bs-contact-6-area pt-130 pb-160">
    <div class="bs-contact-6-bg wa-fix wa-img-cover wa-p-relative">
        <img class="wa-parallax-img" src="{{ asset('assets/img/contact/c4-img-1.png') }}" alt="">
        <img src="{{ asset('assets/img/contact/c4-grow-1.png') }}" alt="" class="glow-1">
    </div>
    <div class="container bs-container-2">
        <div class="bs-contact-6-wrap">

            <!-- left-form -->
            <div class="bs-contact-6-form">
                <h5 class="bs-h-1 title">Interested in {{ $project->title }}?</h5>

                <form action="{{ route('contact.project.inquiry') }}" class="bs-form-1" method="POST">
                    @csrf
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                    <div class="bs-form-1-item wa-clip-left-right">
                        <label class="bs-form-1-item-label" for="name">name</label>
                        <input id="name" class="bs-form-1-item-input" type="text" aria-label="name" placeholder="e.g. Ahmed Mohamed  |" name="name" value="{{ old('name') }}">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="bs-form-1-item wa-clip-left-right">
                        <label class="bs-form-1-item-label" for="phone">phone</label>
                        <input id="phone" class="bs-form-1-item-input" type="tel" aria-label="phone" placeholder="e.g. +20 1234567890" name="phone" value="{{ old('phone') }}">
                        @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="bs-form-1-item wa-clip-left-right">
                        <label class="bs-form-1-item-label" for="email">email</label>
                        <input id="email" class="bs-form-1-item-input" type="email" aria-label="email" placeholder="example@email.com" name="email" value="{{ old('email') }}">
                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                      <div class="bs-form-1-item wa-clip-left-right">
                        <label class="bs-form-1-item-label" for="user_type">user type</label>
                        <select id="user_type" class="bs-form-1-item-input nice-select" aria-label="user_type" name="user_type">
                            <option value="individual" {{ old('user_type') == 'individual' ? 'selected' : '' }}>Individual</option>
                            <option value="broker" {{ old('user_type') == 'broker' ? 'selected' : '' }}>Broker</option>
                            <option value="investor" {{ old('user_type') == 'investor' ? 'selected' : '' }}>Investor</option>
                        </select>
                        @error('user_type') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="bs-form-1-item  wa-clip-left-right">
                        <label class="bs-form-1-item-label" for="message">message</label>
                        <textarea class="bs-form-1-item-input" name="message" id="message" placeholder="write your message here">{{ old('message') }}</textarea>
                        @error('message') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="bs-form-1-item  wa-clip-left-right ">
                        <button type="submit" class="bs-pr-btn-2">
                            <span class="text" data-back="submit now" data-front="submit now"></span>
                            <span class="line-1"></span>
                            <span class="line-2"></span>
                            <span class="box box-1"></span>
                            <span class="box box-2"></span>
                            <span class="box box-3"></span>
                            <span class="box box-4"></span>
                        </button>
                    </div>

                </form>
            </div>

            <!-- section-title -->
            <h2 class="bs-sec-title-4 bs-contact-6-title wa-split-right wa-capitalize">{{ $project->title }} - Where Luxury Meets the Sea.</h2>

            <div class="bs-contact-6-img wa-fix wa-img-cover wa-clip-top-bottom">
                <img class="wa-parallax-img" src="{{ asset('assets/img/contact/c4-img-2.png') }}" alt="">
            </div>
        </div>
    </div>
</section>
<!-- contact-end -->


<!-- map-start -->
<div class="bs-contact-page-map">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3453.0757788567597!2d30.9502945!3d30.0774592!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1458596ebd719e87%3A0xdff54007e2e42e6a!2sGeometric%20Development!5e0!3m2!1sen!2seg!4v1738045355332!5m2!1sen!2seg" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
<!-- map-end -->


@endsection
