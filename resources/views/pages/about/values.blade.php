{{-- Values Section --}}
@php
    $valuesSection = $aboutPage->sections['values'] ?? [];
    $isActive = $valuesSection['is_active'] ?? true;
    $subtitle = $valuesSection['subtitle'] ?? 'OUR BRAND VALUES';
    $title = $valuesSection['title'] ?? 'Building Communities with Purpose and Vision';
    $description = $valuesSection['description'] ?? '';
    $buttonText = $valuesSection['button_text'] ?? 'learn more';
    $buttonLink = $valuesSection['button_link'] ?? '#';
    $showButton = $valuesSection['show_button'] ?? true;
    $sectionImage = !empty($valuesSection['section_image'])
        ? (str_starts_with($valuesSection['section_image'], 'http') ? $valuesSection['section_image'] : (str_starts_with($valuesSection['section_image'], 'assets/') ? asset($valuesSection['section_image']) : asset('storage/' . $valuesSection['section_image'])))
        : asset('assets/img/services/s4-img-1.png');
    $bgImage = !empty($valuesSection['background_image'])
        ? (str_starts_with($valuesSection['background_image'], 'http') ? $valuesSection['background_image'] : (str_starts_with($valuesSection['background_image'], 'assets/') ? asset($valuesSection['background_image']) : asset('storage/' . $valuesSection['background_image'])))
        : asset('assets/img/services/s4-bg.png');
    $values = $valuesSection['values'] ?? [];
@endphp

@if($isActive)
<!-- values-start -->
<section class="bs-services-4-area pt-100 wa-fix" data-background="{{ $bgImage }}">
    <div class="bs-services-4-img wa-fix wa-img-cover wa-slideInLeft">
        <img src="{{ $sectionImage }}" alt="">
    </div>
    <div class="container bs-container-2">
        <h5 class="bs-subtitle-4 bs-services-4-subtitle">
            <span class="text">{{ $subtitle }}</span>
        </h5>

        <div class="bs-services-4-wrap">

            <!-- left-content -->
            <div class="bs-services-4-content">

                <h2 class="bs-sec-title-4 title wa-split-right wa-capitalize" data-cursor="-opaque">{!! $title !!}</h2>

                <p class="bs-p-4 disc wa-fadeInUp">{!! $description !!}</p>

                @if($showButton)
                <div class="btn-wrap wa-fadeInUp">
                    <a href="{{ $buttonLink }}" aria-label="{{ $buttonText }}" class="bs-pr-btn-2">
                        <span class="text" data-back="{{ $buttonText }}" data-front="{{ $buttonText }}"></span>
                        <span class="line-1"></span>
                        <span class="line-2"></span>
                        <span class="box box-1"></span>
                        <span class="box box-2"></span>
                        <span class="box box-3"></span>
                        <span class="box box-4"></span>
                    </a>
                </div>
                @endif
            </div>

            <!-- right-item -->
            <div class="bs-services-4-item">
                @foreach($values as $index => $value)
                @php
                    $valueImage = !empty($value['image'])
                        ? (str_starts_with($value['image'], 'http') ? $value['image'] : (str_starts_with($value['image'], 'assets/') ? asset($value['image']) : asset('storage/' . $value['image'])))
                        : asset('assets/img/services/s4-img-2.png');
                    $isActiveValue = $value['active'] ?? ($index === 0);
                @endphp
                <!-- single-item -->
                <div class="bs-services-4-item-single wa-bg-default {{ $isActiveValue ? 'active' : '' }}" data-background="{{ asset('assets/img/services/s4-card-bg.png') }}">
                    <div class="active-content">
                        <h4 class="bs-h-1 title">
                            <a href="{{ $value['link'] ?? '#' }}" aria-label="{{ $value['title'] ?? '' }}" class="wa-line-limit has-line-2">{{ $value['title'] ?? '' }}</a>
                        </h4>
                        <div class="main-img wa-fix wa-img-cover">
                            <img src="{{ $valueImage }}" alt="{{ $value['title'] ?? '' }}">
                        </div>
                        <p class="bs-p-4 disc wa-line-limit has-line-3">{{ $value['description'] ?? '' }}</p>
                    </div>
                    <div class="default-content">
                        <div class="img-2 wa-fix wa-img-cover">
                            <img src="{{ $valueImage }}" alt="{{ $value['title'] ?? '' }}">
                        </div>
                        <h4 class="bs-h-1 title-2 wa-line-limit has-line-1">
                            {{ $value['title'] ?? '' }}
                        </h4>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!-- values-end -->
@endif
