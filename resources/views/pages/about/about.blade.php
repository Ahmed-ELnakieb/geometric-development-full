{{-- About Section --}}
@php
    $aboutSection = $aboutPage->sections['about'] ?? [];
    $isActive = $aboutSection['is_active'] ?? true;
    $subtitle = $aboutSection['subtitle'] ?? 'Welcome to Geometric Development';
    $title = $aboutSection['title'] ?? 'Exceptional Communities <br> Across Egypt';
    $description = $aboutSection['description'] ?? 'Geometric Development creates exceptional and sustainable real estate communities.';
    $buttonText = $aboutSection['button_text'] ?? 'learn more about';
    $buttonLink = $aboutSection['button_link'] ?? '#';
    $showButton = $aboutSection['show_button'] ?? true;
    $images = $aboutSection['images'] ?? [];
@endphp

@if($isActive)
<!-- about-start -->
<section class="bs-about-1-area pt-125 pb-100">
    <div class="container bs-container-1">

        <!-- section-title -->
        <div class="bs-about-1-sec-title mb-30">
            <h6 class="bs-subtitle-1 wa-split-clr wa-capitalize">
                <span class="icon">
                    <img src="{{ asset('assets/img/illus/star-shape.png') }}" alt="">
                </span>
                {{ $subtitle }}
            </h6>
            <h2 class="bs-sec-title-1 wa-split-right wa-capitalize" data-cursor="-opaque">{!! $title !!}</h2>
        </div>

        <!-- slider -->
        @if(!empty($images))
        <div class="bs-about-1-slider mb-40 wa-p-relative">
            <div class="swiper-container wa-fix bs-a1-active">
                <div class="swiper-wrapper">
                    @foreach($images as $image)
                    @php
                        $imageUrl = !empty($image['image'])
                            ? (str_starts_with($image['image'], 'http')
                                ? $image['image']
                                : (str_starts_with($image['image'], 'assets/')
                                    ? asset($image['image'])
                                    : asset('storage/' . $image['image'])))
                            : asset('assets/img/about/a1-img-1.png');
                    @endphp
                    <!-- single-slide -->
                    <div class="swiper-slide">
                        <div class="bs-about-1-item wa-fix">
                            <a href="{{ $imageUrl }}" class="popup-img wa-img-cover">
                                <img src="{{ $imageUrl }}" alt="">
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bs-about-1-slider-drag bs-p-1">drag</div>

        </div>
        @endif

        <!-- content -->
        <div class="bs-about-1-content">
            <p class="bs-p-1 disc wa-split-y wa-capitalize">
                {!! $description !!}
            </p>
            @if($showButton)
            <div class="btn-wrap wa-fadeInRight">
                <a href="{{ $buttonLink }}" aria-label="{{ $buttonText }}" class="bs-btn-1">
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
            @endif
        </div>

    </div>
</section>
<!-- about-end -->
@endif
