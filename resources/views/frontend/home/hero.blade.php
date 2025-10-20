{{-- Hero Section --}}
@php
    $hero = $homePage->sections['hero'] ?? [];
    $heroIsActive = $hero['is_active'] ?? true;
    $mainTitle = $hero['main_title'] ?? 'Leading Community Developer in MUROJ';
    $subtitle = $hero['subtitle'] ?? 'Inspiration of MUROJ in EGYPT';
    $rotatingTexts = $hero['rotating_texts'] ?? ['Luxury Living', 'Invest Smart', 'Buy Quality', 'Dream Home'];
    $buttonText = $hero['button_text'] ?? 'IN GEOMETRIC';
    $buttonLink = $hero['button_link'] ?? '/projects';
    $iconClass = $hero['icon_class'] ?? 'flaticon-next-1';
    
    // Handle image paths
    $backgroundImage = !empty($hero['background_image']) 
        ? (str_starts_with($hero['background_image'], 'http') 
            ? $hero['background_image'] 
            : (str_starts_with($hero['background_image'], 'assets/') 
                ? asset($hero['background_image']) 
                : asset('storage/' . $hero['background_image']))) 
        : asset('assets/img/hero/h5-bg-img-1.png');
    
    $foregroundImage = !empty($hero['foreground_image']) 
        ? (str_starts_with($hero['foreground_image'], 'http') 
            ? $hero['foreground_image'] 
            : (str_starts_with($hero['foreground_image'], 'assets/') 
                ? asset($hero['foreground_image']) 
                : asset('storage/' . $hero['foreground_image']))) 
        : asset('assets/img/hero/h5-img-1.png');
    
    $iconImage = !empty($hero['icon_image']) 
        ? (str_starts_with($hero['icon_image'], 'http') 
            ? $hero['icon_image'] 
            : (str_starts_with($hero['icon_image'], 'assets/') 
                ? asset($hero['icon_image']) 
                : asset('storage/' . $hero['icon_image']))) 
        : asset('assets/img/hero/h5-img-3.png');
@endphp

@if($heroIsActive)
<section class="bs-hero-5-area wa-bg-default wa-p-relative wa-fix wa-bg-parallax" data-background="{{ $backgroundImage }}">
    <div class="container bs-container-2">
        <div class="bs-hero-5-wrap">

            <div class="bs-hero-5-right">
                <h1 class="bs-hero-5-title-1 bs-h-4 wa-split-right" data-split-delay="1.5s">{{ $mainTitle }}</h1>

                <div class="inner-div-1">
                    <p class="bs-hero-5-disc">{{ $subtitle }}</p>

                    <h2 class="bs-hero-5-title-2 bs-h-4 wa-split-right cd-headline clip" data-split-delay="2s">
                        <span class="cd-words-wrapper single-headline">
                            @foreach($rotatingTexts as $index => $text)
                                <b @if($index === 0) class="is-visible" @endif>{{ $text }}</b>
                            @endforeach
                        </span>
                    </h2>
                </div>

                <div class="inner-div-2">
                    <a class="bs-hero-5-btn wa-magnetic" href="{{ url($buttonLink) }}" aria-label="{{ $buttonText }}">
                        <span class="icon wa-bg-default" data-background="{{ $iconImage }}">
                            <i class="{{ $iconClass }} flaticon"></i>
                        </span>
                        <span class="btn-border has-border-1"></span>
                        <span class="btn-border has-border-2"></span>
                        <span class="btn-border has-border-3"></span>
                    </a>
                    <h3 class="bs-hero-5-title-3 bs-h-4 wa-split-right" data-split-delay="2.5s">{{ $buttonText }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="bs-hero-5-bg-img">
        <img src="{{ $foregroundImage }}" alt="{{ $mainTitle }}">
    </div>

    <div class="bs-hero-5-bg-circle"></div>
</section>
@endif
