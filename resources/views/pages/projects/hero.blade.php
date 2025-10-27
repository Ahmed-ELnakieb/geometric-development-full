@php
    $heroSection = $sections['hero'] ?? [];
@endphp

@if($heroSection['is_active'] ?? true)
<!-- showcase-start -->
<section class="bs-showcase-3-area wa-fix wa-bg-default wa-p-relative"
    data-background="{{ asset($heroSection['background_image'] ?? 'assets/img/hero/hero.png') }}">
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
                            @for($i = 0; $i < ($heroSection['trust_badge_rating'] ?? 5); $i++)
                            <i class="fa-solid fa-star"></i>
                            @endfor
                        </div>
                        <p class="bs-p-1 disc">{{ $heroSection['trust_badge_text'] ?? '500+ Properties Delivered' }}</p>
                    </div>
                </div>

                <h3 class="bs-showcase-3-content-title bs-h-2 wa-split-right wa-capitalize">
                    <span>{{ $heroSection['title'] ?? 'Discover Our' }}</span>
                    {{ $heroSection['subtitle'] ?? 'Projects' }}
                </h3>

                <div class="bs-showcase-3-content-btn wa-fadeInUp">
                    <a href="{{ $heroSection['button_link'] ?? '#projects' }}" aria-label="name" class="bs-btn-1 text-capitalize">
                        <span class="text">
                            {{ $heroSection['button_text'] ?? 'explore more' }}
                        </span>
                        <span class="icon">
                            <i class="fa-solid fa-right-long"></i>
                            <i class="fa-solid fa-right-long"></i>
                        </span>
                        <span class="shape"></span>
                    </a>
                </div>
            </div>

            <!-- right-slide -->
            @if($heroSection['show_slider'] ?? true)
            <div class="bs-showcase-3-slider wa-p-relative wa-fix wa-slideInUp">
                <div class="swiper-container wa-fix bs-showc3-active">
                    <div class="swiper-wrapper">
                        @foreach ($allProjects->take($heroSection['max_slider_projects'] ?? 5) as $project)
                            <!-- single-slider -->
                            <div class="swiper-slide">
                                <div class="bs-showcase-3-slider-item">
                                    <div class="item-img wa-fix wa-img-cover ">
                                        <a href="{{ route('projects.show', $project->slug) }}" aria-label="name"
                                            data-cursor-text="View">
                                            <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (10).png') }}"
                                                alt="">
                                        </a>
                                    </div>
                                    <h5 class="bs-h-1 item-title">
                                        <a href="{{ route('projects.show', $project->slug) }}"
                                            aria-label="name">{{ $project->title }}</a>
                                    </h5>
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
            @endif

        </div>
    </div>
</section>
<!-- showcase-end -->
@endif
