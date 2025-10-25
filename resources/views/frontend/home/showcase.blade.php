{{-- Showcase Section --}}
@php
    $showcase = $homePage->sections['showcase'] ?? [];
    $showcaseIsActive = $showcase['is_active'] ?? true;
    $showcaseItems = $showcase['items'] ?? [];
    // Filter only items with showcase enabled
    $activeShowcaseItems = collect($showcaseItems)->filter(function($item) {
        return ($item['showcase'] ?? true) == true;
    });
@endphp

@if($showcaseIsActive && $activeShowcaseItems->isNotEmpty())
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
                            if (str_starts_with($item['image'], 'http')) {
                                $imageUrl = $item['image'];
                            } elseif (str_starts_with($item['image'], 'assets/')) {
                                // Direct asset path
                                $imageUrl = asset($item['image']);
                            } else {
                                // Storage path
                                $imageUrl = asset('storage/' . $item['image']);
                            }
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
