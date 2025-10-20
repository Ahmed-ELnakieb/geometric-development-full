{{-- About Section --}}
@php
    $about = $homePage->sections['about'] ?? [];
    $aboutIsActive = $about['is_active'] ?? true;
    $sectionNumber = $about['section_number'] ?? '01';
    $sectionSubtitle = $about['section_subtitle'] ?? 'about us';
    $sectionTitle = $about['section_title'] ?? 'Your trusted partner in finding properties and investment opportunities in Egypt\'s most desirable locations.';
    $description = $about['description'] ?? 'Discover your perfect property with Geometric Development...';
    $buttonText = $about['button_text'] ?? 'know about us';
    $buttonLink = $about['button_link'] ?? '/about';
    $features = $about['features'] ?? [];
    
    // Handle image paths
    $bgShape1 = !empty($about['bg_shape_1']) 
        ? (str_starts_with($about['bg_shape_1'], 'http') 
            ? $about['bg_shape_1'] 
            : (str_starts_with($about['bg_shape_1'], 'assets/') 
                ? asset($about['bg_shape_1']) 
                : asset('storage/' . $about['bg_shape_1']))) 
        : asset('assets/img/about/a5-bg-shape.png');
    
    $bgShape2 = !empty($about['bg_shape_2']) 
        ? (str_starts_with($about['bg_shape_2'], 'http') 
            ? $about['bg_shape_2'] 
            : (str_starts_with($about['bg_shape_2'], 'assets/') 
                ? asset($about['bg_shape_2']) 
                : asset('storage/' . $about['bg_shape_2']))) 
        : asset('assets/img/about/a5-bg-shape-2.png');
    
    $aboutImage1 = !empty($about['image_1']) 
        ? (str_starts_with($about['image_1'], 'http') 
            ? $about['image_1'] 
            : (str_starts_with($about['image_1'], 'assets/') 
                ? asset($about['image_1']) 
                : asset('storage/' . $about['image_1']))) 
        : asset('assets/img/about/a5-img-1.png');
    
    $aboutImage2 = !empty($about['image_2']) 
        ? (str_starts_with($about['image_2'], 'http') 
            ? $about['image_2'] 
            : (str_starts_with($about['image_2'], 'assets/') 
                ? asset($about['image_2']) 
                : asset('storage/' . $about['image_2']))) 
        : asset('assets/img/about/a5-img-2.png');
@endphp

@if($aboutIsActive)
<section class="bs-about-5-area pt-135 pb-100 wa-fix wa-p-relative">
    <div class="bs-about-5-bg-shape">
        <img src="{{ $bgShape1 }}" alt="Background shape">
    </div>
    <div class="bs-about-5-bg-shape-2">
        <img src="{{ $bgShape2 }}" alt="Background shape">
    </div>

    <div class="container bs-container-2">

        <!-- section-title -->
        <div class="bs-about-5-sec-title mb-55">
            <h6 class="bs-subtitle-5 wa-capitalize">
                <span>{{ $sectionNumber }}</span>
                <span class="wa-split-right">{{ $sectionSubtitle }}</span>
            </h6>
            <h2 class="bs-sec-title-4 wa-split-right wa-capitalize" data-cursor="-opaque">{{ $sectionTitle }}</h2>
        </div>

        <div class="bs-about-5-wrap">

            <!-- left-side -->
            <div class="bs-about-5-left">
                <p class="bs-p-4 disc wa-fadeInUp">{{ $description }}</p>
                <div class="btn-wrap wa-fadeInUp">
                    <a href="{{ url($buttonLink) }}" aria-label="{{ $buttonText }}" class="bs-pr-btn-3">
                        <span class="text">{{ $buttonText }} <i class="fa-solid fa-angle-right"></i></span>
                        <span class="text">{{ $buttonText }} <i class="fa-solid fa-angle-right"></i></span>
                    </a>
                </div>

                <div class="bs-about-5-img-1 wa-fix wa-img-cover wa-fadeInUp" data-cursor="-opaque">
                    <img class="wa-parallax-img" src="{{ $aboutImage1 }}" alt="About image 1">
                </div>
            </div>

            <!-- right-side -->
            <div class="bs-about-5-right">
                <div class="bs-about-5-img-2 wa-fix wa-img-cover wa-fadeInUp" data-cursor="-opaque">
                    <img class="wa-parallax-img" src="{{ $aboutImage2 }}" alt="About image 2">
                </div>

                @if(!empty($features))
                <ul class="bs-about-5-feature wa-list-style-none">
                    @foreach($features as $feature)
                    <li class="bs-p-4 wa-fadeInUp">
                        <i class="{{ $feature['icon'] ?? 'fa-solid fa-plus' }}"></i>
                        {{ $feature['title'] ?? '' }}
                    </li>
                    @endforeach
                </ul>
                @else
                <ul class="bs-about-5-feature wa-list-style-none">
                    <li class="bs-p-4 wa-fadeInUp">
                        <i class="fa-solid fa-plus"></i>
                        Prime Locations
                    </li>
                    <li class="bs-p-4 wa-fadeInUp">
                        <i class="fa-solid fa-plus"></i>
                        Luxury Amenities
                    </li>
                    <li class="bs-p-4 wa-fadeInUp">
                        <i class="fa-solid fa-plus"></i>
                        Modern Design
                    </li>
                    <li class="bs-p-4 wa-fadeInUp">
                        <i class="fa-solid fa-plus"></i>
                        Smart Homes
                    </li>
                    <li class="bs-p-4 wa-fadeInUp">
                        <i class="fa-solid fa-plus"></i>
                        Eco-Friendly
                    </li>
                    <li class="bs-p-4 wa-fadeInUp">
                        <i class="fa-solid fa-plus"></i>
                        Premium Finishes
                    </li>
                    <li class="bs-p-4 wa-fadeInUp">
                        <i class="fa-solid fa-plus"></i>
                        Investment Opportunities
                    </li>
                    <li class="bs-p-4 wa-fadeInUp">
                        <i class="fa-solid fa-plus"></i>
                        Customizable Options
                    </li>
                </ul>
                @endif
            </div>
        </div>
    </div>
</section>
@endif
