@extends('layouts.app')

@section('title', 'About Us - Geometric Development')

@section('body-class', '')

@section('content')

<!-- breadcrumb-start -->
<section class="breadcrumb-area wa-p-relative" >
    <div class="breadcrumb-bg-img wa-fix wa-img-cover">
        <img class="wa-parallax-img" src="{{ asset('assets/img/breadcrumb/breadcrumb-img.png') }}" alt="">
    </div>

    <div class="container bs-container-1">
        <div class="breadcrumb-wrap">
            <h1 class="breadcrumb-title wa-split-right wa-capitalize" data-split-delay="1.1s" >About Us</h1>

            <div class="breadcrumb-list " >
                <svg class="breadcrumb-list-shape" width="88" height="91" viewBox="0 0 88 91" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M75.3557 83.4825C51.6516 78.2316 30.2731 65.4227 30.8424 38.6307C29.0856 37.5878 27.3642 36.4078 25.6807 35.1082C15.8629 27.5282 7.34269 15.8295 0.970618 3.77828L0 1.94173L3.67259 0L4.64321 1.83605C10.7341 13.3558 18.8345 24.574 28.2197 31.82C29.1853 32.5658 30.1649 33.2687 31.1564 33.9242C31.7447 28.7351 34.2557 18.3221 41.4244 12.7755C53.1965 3.6676 66.5598 9.52271 70.2762 19.1546C74.5799 30.309 65.1659 39.6328 59.589 41.7844C51.0354 45.0846 42.7385 44.3218 35.01 40.8138C35.681 63.7945 54.9747 74.6677 76.0057 79.3717L77.1209 72.3207L87.9707 83.4999L74.2006 90.7853L75.3557 83.4825ZM35.1147 36.2473C42.2964 39.9314 50.0548 41.0102 58.0934 37.9089C62.3617 36.2618 69.6945 29.1868 66.4003 20.6502C63.5203 13.1858 53.0893 9.00325 43.9669 16.0613C37.698 20.9114 35.7338 30.1584 35.2637 34.5703C35.2034 35.1366 35.1536 35.696 35.1147 36.2473Z" fill="white"/>
                </svg>
                    
                <a href="{{ route('home') }}">Home</a>
                <span>About us</span>
            </div>

        </div>
    </div>
</section>
<!-- breadcrumb-end -->

<!-- core-features-start -->
<section class="bs-core-features-1-area pt-120">
    <div class="container bs-container-1">
        <div class="bs-core-features-1-wrap">

            <!-- single-item -->
            <div class="bs-core-features-1-item">
                <div class="icon">
                    <img src="{{ asset('assets/img/core-features/cf-icon-1.png') }}" alt="">
                </div>
                <div class="content">
                    <h5 class="bs-h-1 item-title">
                        <a href="#" aria-label="name">Masterplanned Communities</a>
                    </h5>
                    <p class="bs-p-1 item-disc">Thoughtfully designed living spaces</p>
                </div>

            </div>

            <div class="shape">
                <img src="{{ asset('assets/img/illus/star-shape.png') }}" alt="">
            </div>

            <!-- single-item -->
            <div class="bs-core-features-1-item">
                <div class="icon">
                    <img src="{{ asset('assets/img/core-features/cf-icon-2.png') }}" alt="">
                </div>
                <div class="content">
                    <h5 class="bs-h-1 item-title">
                        <a href="#" aria-label="name">Sustainable Development</a>
                    </h5>
                    <p class="bs-p-1 item-disc">Green building practices</p>
                </div>

            </div>

            <div class="shape">
                <img src="{{ asset('assets/img/illus/star-shape.png') }}" alt="">
            </div>

            <!-- single-item -->
            <div class="bs-core-features-1-item">
                <div class="icon">
                    <img src="{{ asset('assets/img/core-features/cf-icon-3.png') }}" alt="">
                </div>
                <div class="content">
                    <h5 class="bs-h-1 item-title">
                        <a href="#" aria-label="name">Strategic Locations</a>
                    </h5>
                    <p class="bs-p-1 item-disc">Prime Egyptian destinations</p>
                </div>

            </div>

            <div class="shape">
                <img src="{{ asset('assets/img/illus/star-shape.png') }}" alt="">
            </div>

            <!-- single-item -->
            <div class="bs-core-features-1-item">
                <div class="icon">
                    <img src="{{ asset('assets/img/core-features/cf-icon-4.png') }}" alt="">
                </div>
                <div class="content">
                    <h5 class="bs-h-1 item-title">
                        <a href="#" aria-label="name">Premium Amenities</a>
                    </h5>
                    <p class="bs-p-1 item-disc">Luxury lifestyle facilities</p>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- core-features-end -->

<!-- about-start -->
<section class="bs-about-1-area pt-125 pb-100">
    <div class="container bs-container-1">

        <!-- section-title -->
        <div class="bs-about-1-sec-title mb-30">
            <h6 class="bs-subtitle-1 wa-split-clr wa-capitalize">
                <span class="icon">
                    <img src="{{ asset('assets/img/illus/star-shape.png') }}" alt="">
                </span>
                Welcome to Geometric Development
            </h6>
            <h2 class="bs-sec-title-1  wa-split-right wa-capitalize" data-cursor="-opaque">Exceptional Communities <br> Across Egypt</h2>
        </div>

        <!-- slider -->
        <div class="bs-about-1-slider mb-40 wa-p-relative">
            <div class="swiper-container wa-fix bs-a1-active">
                <div class="swiper-wrapper">

                    <!-- single-slide -->
                    <div class="swiper-slide">
                        <div class="bs-about-1-item wa-fix ">
                            <a href="{{ asset('assets/img/about/a1-img-1.png') }}" class="popup-img wa-img-cover">
                                <img src="{{ asset('assets/img/about/a1-img-1.png') }}" alt="">
                            </a>
                         
                        </div>
                    </div>

                    <!-- single-slide -->
                    <div class="swiper-slide">
                        <div class="bs-about-1-item wa-fix ">
                            <a href="{{ asset('assets/img/about/a1-img-2.png') }}" class="popup-img wa-img-cover">
                                <img src="{{ asset('assets/img/about/a1-img-2.png') }}" alt="">
                            </a>
                        </div>
                    </div>

                    <!-- single-slide -->
                    <div class="swiper-slide">
                        <div class="bs-about-1-item wa-fix ">
                            <a href="{{ asset('assets/img/about/a1-img-3.png') }}" class="popup-img wa-img-cover">
                                <img src="{{ asset('assets/img/about/a1-img-3.png') }}" alt="">
                            </a>
                        </div>
                    </div>

                    <!-- single-slide -->
                    <div class="swiper-slide">
                        <div class="bs-about-1-item wa-fix ">
                            <a href="{{ asset('assets/img/about/a1-img-4.png') }}" class="popup-img wa-img-cover">
                                <img src="{{ asset('assets/img/about/a1-img-4.png') }}" alt="">
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <div class="bs-about-1-slider-drag bs-p-1">drag</div>

        </div>

        <!-- content -->
        <div class="bs-about-1-content">
            <p class="bs-p-1 disc wa-split-y wa-capitalize">
                Geometric Development creates exceptional and sustainable real estate communities. <b>We specialize in contemporary developments</b> that integrate excellence, innovation, and nature to enrich lifestyles across Egypt.
            </p>
            <div class="btn-wrap wa-fadeInRight">
                <a href="#" aria-label="name" class="bs-btn-1">
                    <span class="text">
                        learn more about
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
</section>
<!-- about-end -->

<!-- counter-start -->
<section class="bs-core-feature-5-area mb-160">
    <div class="container bs-container-2">
        <div class="bs-core-feature-4-line wa-scaleXInUp"></div>
        <div class="bs-core-feature-4-wrap has-5">

            <!-- single-item -->
            <div class="bs-core-feature-4-item wow fadeInRight" >
                <h4 class="bs-h-4 item-title">
                    Properties Sold
                </h4>
                <h5 class="bs-h-4 item-counter" data-cursor="-opaque">
                    <span class="counter wa-counter">15</span>
                    k+
                </h5>
                <p class="bs-p-4 item-disc">Successfully delivered thousands of premium properties across Egypt.</p>
            </div>

            <!-- single-item -->
            <div class="bs-core-feature-4-item wow fadeInRight" data-wow-delay="0.2s">
                <h4 class="bs-h-4 item-title">
                    Years in Real Estate
                </h4>
                <h5 class="bs-h-4 item-counter" data-cursor="-opaque">
                    <span class="counter wa-counter">23</span>
                    +
                </h5>
                <p class="bs-p-4 item-disc">Over two decades of excellence in Egyptian real estate development.</p>
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
                <p class="bs-p-4 item-disc">98% Happy Homeowners achieved through exceptional architectural services.</p>
            </div>

            <!-- single-item -->
            <div class="bs-core-feature-4-item wow fadeInRight" data-wow-delay="0.6s">
                <h4 class="bs-h-4 item-title">
                    Market Leadership
                </h4>
                <h5 class="bs-h-4 item-counter" data-cursor="-opaque">
                    <span class="counter wa-counter">21</span>
                    +
                </h5>
                <p class="bs-p-4 item-disc">Leading real estate company specializing in premium Egyptian properties.</p>
            </div>

        </div>
        <div class="bs-core-feature-4-line wa-scaleXInUp"></div>
    </div>
</section>
<!-- counter-end -->
         
<!-- values-start -->
<section class="bs-services-4-area pt-100 wa-fix" data-background="{{ asset('assets/img/services/s4-bg.png') }}">
    <div class="bs-services-4-img wa-fix wa-img-cover wa-slideInLeft">
        <img src="{{ asset('assets/img/services/s4-img-1.png') }}" alt="">
    </div>
    <div class="container bs-container-2">
        <h5 class="bs-subtitle-4 bs-services-4-subtitle">
            <span class="text">OUR BRAND VALUES</span>
            
        </h5>

        <div class="bs-services-4-wrap">

            <!-- left-content -->
            <div class="bs-services-4-content">

                <h2 class="bs-sec-title-4 title wa-split-right wa-capitalize" data-cursor="-opaque">Building Communities with Purpose and Vision</h2>

                <p class="bs-p-4 disc wa-fadeInUp">At Geometric Development, we create exceptional real estate developments that blend innovation, sustainability, and timeless value, creating communities that inspire and elevate daily living across Egypt.</p>

                <div class="btn-wrap wa-fadeInUp">
                    <a href="#" aria-label="name" class="bs-pr-btn-2">
                        <span class="text" data-back="learn more" data-front="learn more"></span>
                        <span class="line-1"></span>
                        <span class="line-2"></span>
                        <span class="box box-1"></span>
                        <span class="box box-2"></span>
                        <span class="box box-3"></span>
                        <span class="box box-4"></span>
                    </a>
                </div>
            </div>

            <!-- right-item -->
            <div class="bs-services-4-item">

                <!-- single-item -->
                <div class="bs-services-4-item-single wa-bg-default active" data-background="{{ asset('assets/img/services/s4-card-bg.png') }}">
                    <div class="active-content">
                        <h4 class="bs-h-1 title">
                            <a href="#" aria-label="name" class="wa-line-limit has-line-2">Community-Centric</a>
                        </h4>
                        <div class="main-img wa-fix wa-img-cover">
                            <img src="{{ asset('assets/img/services/s4-img-2.png') }}" alt="">
                        </div>
                        <p class="bs-p-4 disc wa-line-limit has-line-3">At the heart of our endeavours is a profound commitment to people. From the communities we nurture to the broader ecosystem of Egypt, our focus is on creating environments where individuals can connect, grow, and flourish. We are deeply invested in the well-being of our investor community, our team, our partners, and the society at large, striving to make a positive impact in every life we touch.</p>
                    </div>
                    <div class="default-content">
                        <div class="img-2 wa-fix wa-img-cover">
                            <img src="{{ asset('assets/img/services/s4-img-2.png') }}" alt="">
                        </div>
                        <h4 class="bs-h-1 title-2 wa-line-limit has-line-1">
                            Community-Centric
                        </h4>
                    </div>
                </div>

                <!-- single-item -->
                <div class="bs-services-4-item-single wa-bg-default" data-background="{{ asset('assets/img/services/s4-card-bg.png') }}">
                    <div class="active-content">
                        <h4 class="bs-h-1 title">
                            <a href="#" aria-label="name" class="wa-line-limit has-line-2">Value-Driven</a>
                        </h4>
                        <div class="main-img wa-fix wa-img-cover">
                            <img src="{{ asset('assets/img/services/s4-img-3.png') }}" alt="">
                        </div>
                        <p class="bs-p-4 disc wa-line-limit has-line-3">Our ethos is anchored in adding meaningful value. We believe in the power of positive contributions, whether economic, social, or environmental. Our commitment to sustainable practices reflects our dedication to the betterment of society and the preservation of our planet, ensuring that we leave a legacy of positive change.</p>
                    </div>
                    <div class="default-content">
                        <div class="img-2 wa-fix wa-img-cover">
                            <img src="{{ asset('assets/img/services/s4-img-3.png') }}" alt="">
                        </div>
                        <h4 class="bs-h-1 title-2 wa-line-limit has-line-1">
                            Value-Driven
                        </h4>
                    </div>
                </div>

                <!-- single-item -->
                <div class="bs-services-4-item-single wa-bg-default" data-background="{{ asset('assets/img/services/s4-card-bg.png') }}">
                    <div class="active-content">
                        <h4 class="bs-h-1 title">
                            <a href="#" aria-label="name" class="wa-line-limit has-line-2">Responsible & Accountable</a>
                        </h4>
                        <div class="main-img wa-fix wa-img-cover">
                            <img src="{{ asset('assets/img/services/s4-img-4.png') }}" alt="">
                        </div>
                        <p class="bs-p-4 disc wa-line-limit has-line-3">Integrity, respect, and transparency guide our actions. We approach every decision with a sense of responsibility towards the people, the places, and the environment we interact with. Our commitment to ESG principles is unwavering, ensuring that our developments not only respect the natural beauty of Egypt but also enhance it for future generations.</p>
                    </div>
                    <div class="default-content">
                        <div class="img-2 wa-fix wa-img-cover">
                            <img src="{{ asset('assets/img/services/s4-img-4.png') }}" alt="">
                        </div>
                        <h4 class="bs-h-1 title-2 wa-line-limit has-line-1">
                            Responsible & Accountable
                        </h4>
                    </div>
                </div>

                <!-- single-item -->
                <div class="bs-services-4-item-single wa-bg-default" data-background="{{ asset('assets/img/services/s4-card-bg.png') }}">
                    <div class="active-content">
                        <h4 class="bs-h-1 title">
                            <a href="#" aria-label="name" class="wa-line-limit has-line-2">Excellence</a>
                        </h4>
                        <div class="main-img wa-fix wa-img-cover">
                            <img src="{{ asset('assets/img/services/s4-img-5.png') }}" alt="">
                        </div>
                        <p class="bs-p-4 disc wa-line-limit has-line-3">Our pursuit of excellence is relentless. We believe in setting new benchmarks, constantly evolving, and striving to exceed expectations in everything we do. For us, excellence is not just a goal but a continuous journey.</p>
                    </div>
                    <div class="default-content">
                        <div class="img-2 wa-fix wa-img-cover">
                            <img src="{{ asset('assets/img/services/s4-img-5.png') }}" alt="">
                        </div>
                        <h4 class="bs-h-1 title-2 wa-line-limit has-line-1">
                            Excellence
                        </h4>
                    </div>
                </div>

                <!-- single-item -->
                <div class="bs-services-4-item-single wa-bg-default" data-background="{{ asset('assets/img/services/s4-card-bg.png') }}">
                    <div class="active-content">
                        <h4 class="bs-h-1 title">
                            <a href="#" aria-label="name" class="wa-line-limit has-line-2">Sustainability</a>
                        </h4>
                        <div class="main-img wa-fix wa-img-cover">
                            <img src="{{ asset('assets/img/services/s4-img-2.png') }}" alt="">
                        </div>
                        <p class="bs-p-4 disc wa-line-limit has-line-3">Sustainability is the cornerstone of our vision for the future. We are driven by the long-term impact of our actions on our community and the environment. Our developments are designed to create lasting value, balancing progress with preservation, and ensuring that our legacy is one of responsible stewardship and sustainable growth.</p>
                    </div>
                    <div class="default-content">
                        <div class="img-2 wa-fix wa-img-cover">
                            <img src="{{ asset('assets/img/services/s4-img-2.png') }}" alt="">
                        </div>
                        <h4 class="bs-h-1 title-2 wa-line-limit has-line-1">
                            Sustainability
                        </h4>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- values-end -->


<!-- expertise-start -->
<section class="bs-expertise-4-area wa-fix pt-100">
    <div class="container bs-container-2">

        <div class="bs-expertise-4-wrap">
            <h3 class="bs-h-4 bs-expertise-4-title">
                <span class="wa-split-up wa-capitalize-hidden" >Geometric</span>
                <span class="wa-split-up wa-capitalize-hidden" data-split-delay="1s">Development</span>
            </h3>


            <div class="bs-expertise-4-box" txa-matter-scene="true">

                <div class="single-tag" txa-matter-item>
                    <span class="single-tag-item bs-p-4" >
                        <span class="icon has-clr-3">
                            <i class="flaticon-check flaticon"></i>
                        </span>
                        <span class="text">Residential Communities</span>
                    </span>
                </div>

                <div class="single-tag" txa-matter-item>
                    <span class="single-tag-item bs-p-4" >
                        <span class="text">Luxury Villas</span>
                        <span class="icon has-clr-3">
                            <i class="flaticon-check flaticon"></i>
                        </span>
                    </span>
        
                </div>

                <div class="single-tag" txa-matter-item>
                    <span class="single-tag-item bs-p-4 " >
                        <span class="icon has-clr-3">
                            <i class="flaticon-check flaticon"></i>
                        </span>
                        <span class="text">Beachfront Properties</span>
                    </span>
                </div>

                <div class="single-tag" txa-matter-item>
                    <span class="single-tag-item bs-p-4" >
                        <span class="text">Smart Homes</span>
                        <span class="icon has-clr-2">
                            <i class="flaticon-check flaticon"></i>
                        </span>
                   </span>
                </div>

                <div class="single-tag" txa-matter-item>
                    <span class="single-tag-item bs-p-4" >
                        <span class="text">Sustainable Design</span>
                        <span class="icon">
                            <i class="flaticon-check flaticon"></i>
                        </span>
                   </span>
                </div>

                <div class="single-tag" txa-matter-item>
                    <span class="single-tag-item bs-p-4" >
                        <span class="icon">
                            <i class="flaticon-check flaticon"></i>
                        </span>
                        <span class="text">Premium Amenities</span>
                   </span>
                </div>
                 
    
             </div>
        </div>

    </div>
</section>
<!-- expertise-end -->


<!-- video-start -->
<!-- <section class="bs-video-1-area wa-fix">
    <div class="bs-video-1-content wa-p-relative">
        <div class="bs-video-1-content-img wa-p-relative wa-fix wa-img-cover">
            <img class="wa-parallax-img" src="assets/img/video/v1-img-1.png" alt="">

            <div class="bs-video-1-play-btn">
                <a href="https://www.youtube.com/watch?v=e45TPIcx5CY" aria-label="name" class="bs-play-btn-3 wa-magnetic popup-video">
                    <span class="icon wa-magnetic-btn">
                        <i class="fa-solid fa-play"></i>
                    </span>
                </a>
            </div>
        </div>
        <div class="bs-h-1 title wa-split-down" data-cursor="-opaque">
            luxury
            <div class="bs-video-1-content-list">
                <div class="main-img wa-fix wa-img-cover">
                    <img src="assets/img/video/v1-img-2.png" alt="">
                </div>
                <ul class="list">
                    <li>
                        <b>location:</b>
                        Muroj, Egypt
                    </li>
                    <li>
                        <b>year:</b>
                        2024 SQ.FT: 3,500 m
                    </li>
                </ul>
            </div>
            architectural
        </div>
    </div>
</section> -->
<!-- video-end -->

<!-- award-start -->
<section class="bs-award-5-area pt-135 wa-fix">
    <div class="container bs-container-2">
        <div class="bs-award-5-wrap">

            <!-- left-content -->
            <div class="bs-award-5-content">

                <!-- section-title -->
                <div class="bs-award-5-sec-title mb-50">
                    <h6 class="bs-subtitle-5 wa-capitalize">
                        <span class="wa-split-right ">projects</span>
                    </h6>
                    <h2 class="bs-sec-title-4 wa-split-right wa-capitalize" data-cursor="-opaque">Our Development Portfolio</h2>
                </div>

                <!-- img -->
                <div class="bs-award-5-img wa-fix wa-img-cover wa-clip-top-bottom" data-cursor="-opaque" data-split-duration="1.5s">
                    <img src="{{ asset('assets/img/award/a5-img-1.png') }}" alt="">
                </div>

            </div>

            <!-- right-list -->
            <div class="bs-award-5-item">

                <!-- single-item -->
                <a href="{{ route('projects.show', 'muroj-villa') }}" class="bs-award-5-item-single wa-fadeInUp">
                    <h3 class="bs-h-4 year">2025</h3><h4 class="bs-h-4 title">Muroj Villa - Ras El Hekma</h4>
                    <span class="icon">
                        <i class="flaticon-next-1 flaticon"></i>
                    </span>
                    <span class="item-img cursor-follow">
                        <img src="{{ asset('assets/img/award/a5-item-img-1.png') }}" alt="">
                    </span>
                </a>

                <!-- single-item -->
                <a href="{{ route('projects.show', 'mina-marina') }}" class="bs-award-5-item-single wa-fadeInUp">
                    <h3 class="bs-h-4 year">2024</h3><h4 class="bs-h-4 title">Mina Marina - New Cairo</h4>
                    <span class="icon">
                        <i class="flaticon-next-1 flaticon"></i>
                    </span>
                    <span class="item-img cursor-follow">
                        <img src="{{ asset('assets/img/award/a5-item-img-2.png') }}" alt="">
                    </span>
                </a>

                <!-- single-item -->
                <a href="{{ route('projects.show', 'rich-hills') }}" class="bs-award-5-item-single wa-fadeInUp">
                    <h3 class="bs-h-4 year">2023</h3><h4 class="bs-h-4 title">Rich Hills - North Coast</h4>
                    <span class="icon">
                        <i class="flaticon-next-1 flaticon"></i>
                    </span>
                    <span class="item-img cursor-follow">
                        <img src="{{ asset('assets/img/award/a5-item-img-3.png') }}" alt="">
                    </span>
                </a>

                <!-- single-item -->
                <a href="{{ route('projects.show', 'ras-al-khaimah') }}" class="bs-award-5-item-single wa-fadeInUp">
                    <h3 class="bs-h-4 year">2022</h3><h4 class="bs-h-4 title">Ras Al Khaimah - Hurghada</h4>
                    <span class="icon">
                        <i class="flaticon-next-1 flaticon"></i>
                    </span>
                    <span class="item-img cursor-follow">
                        <img src="{{ asset('assets/img/award/a5-item-img-4.png') }}" alt="">
                    </span>
                </a>

                <!-- single-item -->
                <a href="{{ route('projects.show', 'zara-residence') }}" class="bs-award-5-item-single wa-fadeInUp">
                    <h3 class="bs-h-4 year">2021</h3><h4 class="bs-h-4 title">Zara Residence - Sheikh Zayed</h4>
                    <span class="icon">
                        <i class="flaticon-next-1 flaticon"></i>
                    </span>
                    <span class="item-img cursor-follow">
                        <img src="{{ asset('assets/img/award/a5-item-img-5.png') }}" alt="">
                    </span>
                </a>

                <!-- single-item -->
                <a href="{{ route('projects.show', 'solera-complex') }}" class="bs-award-5-item-single wa-fadeInUp">
                    <h3 class="bs-h-4 year">2020</h3><h4 class="bs-h-4 title">Solera Complex - Downtown</h4>
                    <span class="icon">
                        <i class="flaticon-next-1 flaticon"></i>
                    </span>
                    <span class="item-img cursor-follow">
                        <img src="{{ asset('assets/img/award/a5-item-img-6.png') }}" alt="">
                    </span>
                </a>
            </div>


        </div>
    </div>
</section>
<!-- award-end -->
            

<!-- choose-start -->
<section class="bs-choose-4-area wa-bg-default wa-fix pb-120" data-background="{{ asset('assets/img/choose/ch4-bg-img-1.png') }}">
    <div class="container bs-container-2">
        <div class="bs-choose-4-wrap">

            <div class="bs-choose-4-content-height">
                <!-- left-content -->
                <div class="bs-choose-4-content-pin">
                    <div class="bs-choose-4-content">

                        <h5 class="bs-subtitle-4 bs-choose-4-subtitle">
                            <span class="text">Why choose us</span>
                            
                        </h5>

                        <h2 class="bs-sec-title-4 title wa-split-right wa-capitalize" data-cursor="-opaque">Leading Real Estate Developer Transforming Egypt's Landscape</h2>

                        <p class="bs-p-4 disc wa-fadeInUp">With decades of expertise in real estate development, Geometric Development delivers exceptional communities across Egypt. Our commitment to quality, innovation, and sustainability sets us apart.</p>

                        <div class="bs-choose-4-progress">

                            <!-- single-item -->
                            <div class="bs-choose-4-progress-item">
                                <h5 class="bs-p-1 progress-title" style="width: 84%;">
                                    <span >Customer Satisfaction</span>
                                    <span class="counter">84</span>%
                                </h5>
                                <div class="progress-line">
                                    <div class="progress-line-fill wa-progress" style="width: 84%;"></div>
                                </div>
                            </div>

                            <!-- single-item -->
                            <div class="bs-choose-4-progress-item">
                                <h5 class="bs-p-1 progress-title" style="width: 72%;">
                                    <span >Project Delivery</span>
                                    <span class="counter">72</span>%
                                </h5>
                                <div class="progress-line">
                                    <div class="progress-line-fill wa-progress" style="width: 72%;"></div>
                                </div>
                            </div>

                            <!-- single-item -->
                            <div class="bs-choose-4-progress-item">
                                <h5 class="bs-p-1 progress-title" style="width: 96%;">
                                    <span >Quality Standards</span>
                                    <span class="counter">96</span>%
                                </h5>
                                <div class="progress-line">
                                    <div class="progress-line-fill wa-progress" style="width: 96%;"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>




            <!-- right-play-btn -->
            <div class="bs-choose-4-right d-flex justify-content-center align-items-center">

                <a href="https://www.youtube.com/watch?v=e45TPIcx5CY" aria-label="name" class="bs-play-btn-4 wa-magnetic popup-video">
                    <span class="icon wa-magnetic-btn">
                        <i class="flaticon-play flaticon"></i>
                    </span>
                </a>

            </div>
        </div>

        <div class="bs-choose-4-feature">

            <!-- single-feature -->
            <div class="item-margin">
                <div class="bs-choose-4-feature-single">
                    <div class="icon">
                        <i class="flaticon-minimalist flaticon"></i>
                    </div>
                    <h4 class="bs-h-4 title">
                        <a href="services-details.html" aria-label="name">Prime Locations</a>
                    </h4>
                    <p class="bs-p-4 disc">We strategically select locations across Egypt's most desirable destinations, from coastal retreats to urban centers, ensuring lasting value for investors.</p>
                </div>
            </div>

            <!-- single-feature -->
            <div class="item-margin">
                <div class="bs-choose-4-feature-single">
                    <div class="icon">
                        <i class="flaticon-blueprint flaticon"></i>
                    </div>
                    <h4 class="bs-h-4 title">
                        <a href="services-details.html" aria-label="name">Proven Track Record</a>
                    </h4>
                    <p class="bs-p-4 disc">With successful projects spanning over two decades, we've established ourselves as Egypt's trusted developer with thousands of satisfied homeowners.</p>
                </div>
            </div>

            <!-- single-feature -->
            <div class="item-margin">
                <div class="bs-choose-4-feature-single">
                    <div class="icon">
                        <i class="flaticon-property-insurance flaticon"></i>
                    </div>
                    <h4 class="bs-h-4 title">
                        <a href="services-details.html" aria-label="name">Integrated Amenities</a>
                    </h4>
                    <p class="bs-p-4 disc">Our developments feature world-class facilities including sports clubs, retail centers, and green spaces designed for modern living.</p>
                </div>
            </div>

            <!-- single-feature -->
            <div class="item-margin">
                <div class="bs-choose-4-feature-single">
                    <div class="icon">
                        <i class="flaticon-goodwill-1 flaticon"></i>
                    </div>
                    <h4 class="bs-h-4 title">
                        <a href="services-details.html" aria-label="name">Investment Value</a>
                    </h4>
                    <p class="bs-p-4 disc">Our properties offer exceptional ROI potential, combining strategic locations with premium construction to maximize long-term investment value.</p>
                </div>
            </div>

        </div>

    </div>
</section>
<!-- choose-end -->

@endsection