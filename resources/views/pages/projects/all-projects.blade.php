@php
    $allProjectsSection = $sections['all_projects'] ?? [];
@endphp

@if($allProjectsSection['is_active'] ?? true)
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
                    {{ $allProjectsSection['subtitle'] ?? 'Our Projects' }}
                </h6>
                <h2 class="bs-sec-title-1 wa-split-right wa-capitalize" data-cursor="-opaque">{{ $allProjectsSection['title'] ?? 'Our Projects Across Egypt & UAE' }}</h2>
            </div>

            <!-- right-tabs-btn -->
            @if($allProjectsSection['show_tabs'] ?? true)
            <ul class="bs-services-1-tabs-btn wa-list-style-none wa-fadeInRight" role="tablist">
                <!-- single-btn -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all"
                        type="button" role="tab" aria-controls="all" aria-selected="true">
                        All
                        <span class="bg-shape">
                            <img src="{{ asset('assets/img/services/s1-btn-shape.png') }}" alt="">
                        </span>
                    </button>
                </li>

                <!-- single-btn -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="villas-tab" data-bs-toggle="tab" data-bs-target="#villas"
                        type="button" role="tab" aria-controls="villas" aria-selected="false">
                        Villas
                        <span class="bg-shape">
                            <img src="{{ asset('assets/img/services/s1-btn-shape.png') }}" alt="">
                        </span>
                    </button>
                </li>

                <!-- single-btn -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="apartments-tab" data-bs-toggle="tab" data-bs-target="#apartments"
                        type="button" role="tab" aria-controls="apartments" aria-selected="false">
                        Apartments
                        <span class="bg-shape">
                            <img src="{{ asset('assets/img/services/s1-btn-shape.png') }}" alt="">
                        </span>
                    </button>
                </li>

                <!-- single-btn -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="commercial-tab" data-bs-toggle="tab" data-bs-target="#commercial"
                        type="button" role="tab" aria-controls="commercial" aria-selected="false">
                        Commercial
                        <span class="bg-shape">
                            <img src="{{ asset('assets/img/services/s1-btn-shape.png') }}" alt="">
                        </span>
                    </button>
                </li>

                <!-- single-btn -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="investment-tab" data-bs-toggle="tab" data-bs-target="#investment"
                        type="button" role="tab" aria-controls="investment" aria-selected="false">
                        Investment
                        <span class="bg-shape">
                            <img src="{{ asset('assets/img/services/s1-btn-shape.png') }}" alt="">
                        </span>
                    </button>
                </li>

            </ul>
            @endif
        </div>
    </div>

    <!-- content -->
    <div class="tab-content bs-services-1-tabs-pane ">

        <!-- single-pane -->
        <div class="tab-pane fade fadeInUp animated show active" id="all" role="tabpanel"
            aria-labelledby="all-tab">
            <div class="bs-services-1-slider">
                <div class="swiper-container wa-fix bs-s1-active">
                    <div class="swiper-wrapper">
                        @foreach ($allProjects as $project)
                            <!-- single-item -->
                            <div class="swiper-slide">
                                <div class="bs-services-1-item">
                                    <div class="main-img  wa-fix wa-img-cover" data-cursor-text="View"
                                        style="position: relative;">
                                        <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">
                                            <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (14).png') }}"
                                                alt="">
                                        </a>
                                        <span
                                            style="position: absolute; top: 15px; right: 15px; background: linear-gradient(135deg, #d4af37 0%, #f4e4b5 100%); padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; color: #000; z-index: 10;">For
                                            Sale</span>
                                    </div>
                                    <div class="content-wrap">
                                        <div class="shape">
                                            <img src="{{ asset('assets/img/illus/star-shape-color.png') }}"
                                                alt="">
                                        </div>
                                        <div class="content">
                                            <h5 class="bs-h-1 subtitle">{{ $project->location }}</h5>
                                            <h3 class="bs-h-1 title">
                                                <a href="{{ route('projects.show', $project->slug) }}"
                                                    aria-label="name">{{ $project->title }}</a>
                                            </h3>

                                            <a class="item-btn wa-magnetic-btn"
                                                href="{{ route('projects.show', $project->slug) }}"
                                                aria-label="name">
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
                        @foreach ($allProjects->where('type', 'Residential') as $project)
                            <!-- single-item -->
                            <div class="swiper-slide">
                                <div class="bs-services-1-item">
                                    <div class="main-img  wa-fix wa-img-cover" data-cursor-text="View">
                                        <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">
                                            <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (19).png') }}"
                                                alt="">
                                        </a>
                                    </div>
                                    <div class="content-wrap">
                                        <div class="shape">
                                            <img src="{{ asset('assets/img/illus/star-shape-color.png') }}"
                                                alt="">
                                        </div>
                                        <div class="content">
                                            <h5 class="bs-h-1 subtitle">{{ $project->location }}</h5>
                                            <h3 class="bs-h-1 title">
                                                <a href="{{ route('projects.show', $project->slug) }}"
                                                    aria-label="name">{{ $project->title }}</a>
                                            </h3>

                                            <a class="item-btn wa-magnetic-btn"
                                                href="{{ route('projects.show', $project->slug) }}"
                                                aria-label="name">
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
        <div class="tab-pane fade fadeInUp animated" id="apartments" role="tabpanel"
            aria-labelledby="apartments-tab">
            <div class="bs-services-1-slider">
                <div class="swiper-container wa-fix bs-s1-active">
                    <div class="swiper-wrapper">
                        @foreach ($allProjects->where('type', 'Residential') as $project)
                            <!-- single-item -->
                            <div class="swiper-slide">
                                <div class="bs-services-1-item">
                                    <div class="main-img  wa-fix wa-img-cover" data-cursor-text="View">
                                        <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">
                                            <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (19).png') }}"
                                                alt="">
                                        </a>
                                    </div>
                                    <div class="content-wrap">
                                        <div class="shape">
                                            <img src="{{ asset('assets/img/illus/star-shape-color.png') }}"
                                                alt="">
                                        </div>
                                        <div class="content">
                                            <h5 class="bs-h-1 subtitle">{{ $project->location }}</h5>
                                            <h3 class="bs-h-1 title">
                                                <a href="{{ route('projects.show', $project->slug) }}"
                                                    aria-label="name">{{ $project->title }}</a>
                                            </h3>

                                            <a class="item-btn wa-magnetic-btn"
                                                href="{{ route('projects.show', $project->slug) }}"
                                                aria-label="name">
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
        <div class="tab-pane fade fadeInUp animated" id="commercial" role="tabpanel"
            aria-labelledby="commercial-tab">
            <div class="bs-services-1-slider">
                <div class="swiper-container wa-fix bs-s1-active">
                    <div class="swiper-wrapper">
                        @foreach ($allProjects->where('type', 'Commercial') as $project)
                            <!-- single-item -->
                            <div class="swiper-slide">
                                <div class="bs-services-1-item">
                                    <div class="main-img  wa-fix wa-img-cover" data-cursor-text="View">
                                        <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">
                                            <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (19).png') }}"
                                                alt="">
                                        </a>
                                    </div>
                                    <div class="content-wrap">
                                        <div class="shape">
                                            <img src="{{ asset('assets/img/illus/star-shape-color.png') }}"
                                                alt="">
                                        </div>
                                        <div class="content">
                                            <h5 class="bs-h-1 subtitle">{{ $project->location }}</h5>
                                            <h3 class="bs-h-1 title">
                                                <a href="{{ route('projects.show', $project->slug) }}"
                                                    aria-label="name">{{ $project->title }}</a>
                                            </h3>

                                            <a class="item-btn wa-magnetic-btn"
                                                href="{{ route('projects.show', $project->slug) }}"
                                                aria-label="name">
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
        <div class="tab-pane fade fadeInUp animated" id="investment" role="tabpanel"
            aria-labelledby="investment-tab">
            <div class="bs-services-1-slider">
                <div class="swiper-container wa-fix bs-s1-active">
                    <div class="swiper-wrapper">
                        @foreach ($allProjects->where('type', 'Investment') as $project)
                            <!-- single-item -->
                            <div class="swiper-slide">
                                <div class="bs-services-1-item">
                                    <div class="main-img  wa-fix wa-img-cover" data-cursor-text="View">
                                        <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">
                                            <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (19).png') }}"
                                                alt="">
                                        </a>
                                    </div>
                                    <div class="content-wrap">
                                        <div class="shape">
                                            <img src="{{ asset('assets/img/illus/star-shape-color.png') }}"
                                                alt="">
                                        </div>
                                        <div class="content">
                                            <h5 class="bs-h-1 subtitle">{{ $project->location }}</h5>
                                            <h3 class="bs-h-1 title">
                                                <a href="{{ route('projects.show', $project->slug) }}"
                                                    aria-label="name">{{ $project->title }}</a>
                                            </h3>

                                            <a class="item-btn wa-magnetic-btn"
                                                href="{{ route('projects.show', $project->slug) }}"
                                                aria-label="name">
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
@endif
