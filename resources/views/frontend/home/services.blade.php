{{-- Services Section --}}
@php
    $services = $homePage->sections['services'] ?? [];
    $servicesIsActive = $services['is_active'] ?? true;
    $sectionTitle = $services['section_title'] ?? 'Geometric Development <br> Premium Real Estate Services';
    $tabs = $services['tabs'] ?? [];
    $bgImage = !empty($services['background_image']) 
        ? (str_starts_with($services['background_image'], 'http')
            ? $services['background_image']
            : (str_starts_with($services['background_image'], 'assets/')
                ? asset($services['background_image'])
                : asset('storage/' . $services['background_image'])))
        : asset('assets/img/projects/p1-bg-img-1.png');
@endphp

@if($servicesIsActive && !empty($tabs))
<section class="bs-product-5-area pt-85 pb-85 wa-bg-default wa-p-relative wa-fix">
    <div class="container bs-container-1">

        <!-- section-title -->
        <div class="bs-projects-1-sec-title mb-40">
            <h6 class="bs-subtitle-1 wa-split-clr wa-capitalize">
                <span class="icon">
                    <img src="{{ asset('assets/img/illus/star-shape.png') }}" alt="">
                </span>
                our services
            </h6>
            <h2 class="bs-sec-title-1 wa-split-right wa-capitalize" data-cursor="-opaque">{!! $sectionTitle !!}</h2>
        </div>

        <div class="bs-projects-1-wrap">

            <!-- tabs-btn -->
            <div class="bs-projects-1-tabs-btn" role="tablist">
                @foreach($tabs as $index => $tab)
                @php
                    $isActive = $tab['active'] ?? ($index === 1);
                    $tabId = 'nav-tab-' . $index;
                    $targetId = 'nav-pane-' . $index;
                @endphp
                <!-- single-btn -->
                <button class="nav-link wa-fadeInUp {{ $isActive ? 'active' : '' }}" 
                        id="{{ $tabId }}" 
                        data-bs-toggle="tab" 
                        data-bs-target="#{{ $targetId }}" 
                        type="button" 
                        role="tab" 
                        aria-controls="{{ $targetId }}" 
                        aria-selected="{{ $isActive ? 'true' : 'false' }}">
                    {{ $tab['name'] ?? 'Project' }}

                    <span class="year">{{ $tab['year'] ?? '2025' }}</span>
                    <span class="img">
                        <img src="{{ asset('assets/img/projects/p1-btn-img-1.png') }}" alt="">
                    </span>
                    <span class="right-arrow">
                        <img src="{{ asset('assets/img/illus/long-right-arrow.png') }}" alt="">
                    </span>
                </button>
                @endforeach
            </div>


            <!-- tabs-content -->
            <div class="bs-projects-1-tabs-pane tab-content">
                @foreach($tabs as $index => $tab)
                @php
                    $isActive = $tab['active'] ?? ($index === 1);
                    $paneId = 'nav-pane-' . $index;
                    $tabId = 'nav-tab-' . $index;
                    $mainImage = !empty($tab['main_image'])
                        ? (str_starts_with($tab['main_image'], 'http')
                            ? $tab['main_image']
                            : (str_starts_with($tab['main_image'], 'assets/')
                                ? asset($tab['main_image'])
                                : asset('storage/' . $tab['main_image'])))
                        : asset('assets/img/projects/p1-img-' . ($index + 1) . '.png');
                    $socialLinks = $tab['social_links'] ?? [];
                @endphp
                <!-- single-pane -->
                <div class="tab-pane fade {{ $isActive ? 'show active' : '' }}" id="{{ $paneId }}" role="tabpanel" aria-labelledby="{{ $tabId }}">
                    <div class="bs-projects-1-tabs-item">
                        <div class="main-img wa-img-cover wa-fix">
                            <a href="{{ $tab['link'] ?? '/projects' }}"><img data-cursor="-opaque" src="{{ $mainImage }}" alt="{{ $tab['name'] ?? '' }}"></a>
                        </div>
                        <div class="bs-projects-1-tabs-item-table">
                            <div class="start">
                                <h6 class="bs-h-1 title">start & completed</h6>
                                <div class="wrap">
                                    <p class="bs-p-1 disc">{{ $tab['start_date'] ?? 'jan 02, 2025' }}</p>
                                    <p class="bs-p-1 disc">{{ $tab['end_date'] ?? 'aug 02, 2025' }}</p>
                                </div>
                            </div>
                            <div class="location">
                                <h6 class="bs-h-1 title">Location</h6>
                                <p class="bs-p-1 disc">{{ $tab['location'] ?? 'Egypt' }}</p>
                            </div>
                            @if(!empty($socialLinks))
                            <div class="share">
                                <h6 class="bs-h-1 title-2">share project</h6>
                                <div class="bs-social-link-1">
                                    @foreach($socialLinks as $social)
                                    <a href="{{ $social['url'] ?? '#' }}" aria-label="{{ $social['platform'] ?? 'social' }}" class="item-link">
                                        <i class="{{ $social['icon'] ?? 'fa-brands fa-facebook-f' }}"></i>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>

    <!-- bg-img -->
    <div class="bs-projects-1-bg-img">
        <img src="{{ $bgImage }}" alt="">
    </div>
</section>
@endif
