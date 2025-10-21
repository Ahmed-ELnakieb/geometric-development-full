{{-- Why Choose Us Section --}}
@php
    $whyChooseSection = $aboutPage->sections['why_choose_us'] ?? [];
    $isActive = $whyChooseSection['is_active'] ?? true;
    $subtitle = $whyChooseSection['subtitle'] ?? 'Why choose us';
    $title = $whyChooseSection['title'] ?? 'Leading Real Estate Developer Transforming Egypt\'s Landscape';
    $description = $whyChooseSection['description'] ?? '';
    $bgImage = !empty($whyChooseSection['background_image'])
        ? (str_starts_with($whyChooseSection['background_image'], 'http') ? $whyChooseSection['background_image'] : (str_starts_with($whyChooseSection['background_image'], 'assets/') ? asset($whyChooseSection['background_image']) : asset('storage/' . $whyChooseSection['background_image'])))
        : asset('assets/img/choose/ch4-bg-img-1.png');
    $videoUrl = $whyChooseSection['video_url'] ?? 'https://www.youtube.com/watch?v=e45TPIcx5CY';
    $showVideo = $whyChooseSection['show_video'] ?? true;
    $progress = $whyChooseSection['progress'] ?? [];
    $features = $whyChooseSection['features'] ?? [];
@endphp

@if($isActive)
<!-- choose-start -->
<section class="bs-choose-4-area wa-bg-default wa-fix pb-120" data-background="{{ $bgImage }}">
    <div class="container bs-container-2">
        <div class="bs-choose-4-wrap">

            <div class="bs-choose-4-content-height">
                <!-- left-content -->
                <div class="bs-choose-4-content-pin">
                    <div class="bs-choose-4-content">

                        <h5 class="bs-subtitle-4 bs-choose-4-subtitle">
                            <span class="text">{{ $subtitle }}</span>
                        </h5>

                        <h2 class="bs-sec-title-4 title wa-split-right wa-capitalize" data-cursor="-opaque">{!! $title !!}</h2>

                        @if($description)
                        <p class="bs-p-4 disc wa-fadeInUp">{!! $description !!}</p>
                        @endif

                        @if(!empty($progress))
                        <div class="bs-choose-4-progress">
                            @foreach($progress as $item)
                            <!-- single-item -->
                            <div class="bs-choose-4-progress-item">
                                <h5 class="bs-p-1 progress-title" style="width: {{ $item['percentage'] ?? '0' }}%;">
                                    <span>{{ $item['title'] ?? '' }}</span>
                                    <span class="counter">{{ $item['percentage'] ?? '0' }}</span>%
                                </h5>
                                <div class="progress-line">
                                    <div class="progress-line-fill wa-progress" style="width: {{ $item['percentage'] ?? '0' }}%;"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- right-play-btn -->
            @if($showVideo)
            <div class="bs-choose-4-right d-flex justify-content-center align-items-center">
                <a href="{{ $videoUrl }}" aria-label="Play Video" class="bs-play-btn-4 wa-magnetic popup-video">
                    <span class="icon wa-magnetic-btn">
                        <i class="flaticon-play flaticon"></i>
                    </span>
                </a>
            </div>
            @endif
        </div>

        @if(!empty($features))
        <div class="bs-choose-4-feature">
            @foreach($features as $feature)
            <!-- single-feature -->
            <div class="item-margin">
                <div class="bs-choose-4-feature-single">
                    <div class="icon">
                        <i class="{{ $feature['icon'] ?? 'flaticon-minimalist' }} flaticon"></i>
                    </div>
                    <h4 class="bs-h-4 title">
                        <a href="{{ $feature['link'] ?? '#' }}" aria-label="{{ $feature['title'] ?? '' }}">{{ $feature['title'] ?? '' }}</a>
                    </h4>
                    <p class="bs-p-4 disc">{{ $feature['description'] ?? '' }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</section>
<!-- choose-end -->
@endif
