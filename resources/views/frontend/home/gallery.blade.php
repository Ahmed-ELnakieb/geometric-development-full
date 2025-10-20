{{-- Gallery Section --}}
@php
    $gallery = $homePage->sections['gallery'] ?? [];
    $galleryIsActive = $gallery['is_active'] ?? true;
    $galleryItems = $gallery['items'] ?? [];
    $sectionSubtitle = $gallery['section_subtitle'] ?? 'Stay Inspired with Instagram';
    $sectionTitle = $gallery['section_title'] ?? '<i class="fa-brands fa-instagram"></i> Instagram';
    $buttonText = $gallery['button_text'] ?? 'Follow Us';
    $buttonLink = $gallery['button_link'] ?? 'https://instagram.com/geometric_development';
    $starIcon = $gallery['star_icon'] ?? 'assets/img/illus/star-shape.png';
@endphp

@if($galleryIsActive && !empty($galleryItems))
<section class="bs-gallery-2-area pt-100 pb-145">
    <div class="container bs-container-1">
        <div class="bs-gallery-2-wrap">

            <!-- left -->
            <div class="left">

                <!-- section-title -->
                <div class="bs-gallery-2-sec-title wa-fix ">
                    <h6 class="bs-subtitle-1 wa-split-clr wa-capitalize">
                        <span class="icon">
                            <img src="{{ asset($starIcon) }}" alt="">
                        </span>
                        <span class="text">
                            {{ $sectionSubtitle }}
                        </span>
                    </h6>
                    <h2 class="bs-sec-title-1 wa-split-right wa-capitalize">
                        {!! $sectionTitle !!}
                    </h2>
                </div>

                @foreach($galleryItems as $index => $item)
                    @if($index < 2)
                        @php
                            // Handle image path properly
                            if (!empty($item['image'])) {
                                if (str_starts_with($item['image'], 'http')) {
                                    $imageUrl = $item['image']; // Full URL
                                } elseif (str_starts_with($item['image'], 'assets/')) {
                                    $imageUrl = asset($item['image']); // Asset path
                                } else {
                                    $imageUrl = asset('storage/' . $item['image']); // Storage path (uploaded)
                                }
                            } else {
                                $imageUrl = asset('assets/img/gallery/g2-img-' . ($index + 1) . '.png');
                            }
                            $instagramUrl = $item['instagram_url'] ?? '#';
                            $size = $item['size'] ?? 'normal';
                        @endphp
                        <!-- img -->
                        <a href="{{ $instagramUrl }}" 
                           target="_blank"
                           aria-label="Instagram post" 
                           class="bs-gallery-2-img {{ $size !== 'normal' ? $size : '' }} popup-img wa-img-cover wa-fix">
                            <img src="{{ $imageUrl }}" alt="Gallery image {{ $index + 1 }}">
                        </a>
                    @endif
                @endforeach

            </div>

            <!-- middle -->
            <div class="meddle">

                <div class="meddle-row">
                    @foreach($galleryItems as $index => $item)
                        @if($index >= 2 && $index < 4)
                            @php
                                // Handle image path properly
                                if (!empty($item['image'])) {
                                    if (str_starts_with($item['image'], 'http')) {
                                        $imageUrl = $item['image']; // Full URL
                                    } elseif (str_starts_with($item['image'], 'assets/')) {
                                        $imageUrl = asset($item['image']); // Asset path
                                    } else {
                                        $imageUrl = asset('storage/' . $item['image']); // Storage path (uploaded)
                                    }
                                } else {
                                    $imageUrl = asset('assets/img/gallery/g2-img-' . ($index + 1) . '.png');
                                }
                                $instagramUrl = $item['instagram_url'] ?? '#';
                                $size = $item['size'] ?? 'normal';
                            @endphp
                            <!-- img -->
                            <a href="{{ $instagramUrl }}" 
                               target="_blank"
                               aria-label="Instagram post" 
                               class="bs-gallery-2-img {{ $size !== 'normal' ? $size : '' }} popup-img wa-img-cover wa-fix">
                                <img src="{{ $imageUrl }}" alt="Gallery image {{ $index + 1 }}">
                            </a>
                        @endif
                    @endforeach
                </div>

                <div class="meddle-row-2">
                    @foreach($galleryItems as $index => $item)
                        @if($index >= 4 && $index < 6)
                            @php
                                // Handle image path properly
                                if (!empty($item['image'])) {
                                    if (str_starts_with($item['image'], 'http')) {
                                        $imageUrl = $item['image']; // Full URL
                                    } elseif (str_starts_with($item['image'], 'assets/')) {
                                        $imageUrl = asset($item['image']); // Asset path
                                    } else {
                                        $imageUrl = asset('storage/' . $item['image']); // Storage path (uploaded)
                                    }
                                } else {
                                    $imageUrl = asset('assets/img/gallery/g2-img-' . ($index + 1) . '.png');
                                }
                                $instagramUrl = $item['instagram_url'] ?? '#';
                                $size = $item['size'] ?? 'normal';
                            @endphp
                            <!-- img -->
                            <a href="{{ $instagramUrl }}" 
                               target="_blank"
                               aria-label="Instagram post" 
                               class="bs-gallery-2-img {{ $size !== 'normal' ? $size : '' }} popup-img wa-img-cover wa-fix">
                                <img src="{{ $imageUrl }}" alt="Gallery image {{ $index + 1 }}">
                            </a>
                        @endif
                    @endforeach
                </div>

            </div>

            <!-- right -->
            <div class="right">

                @foreach($galleryItems as $index => $item)
                    @if($index >= 6 && $index < 7)
                        @php
                            // Handle image path properly
                            if (!empty($item['image'])) {
                                if (str_starts_with($item['image'], 'http')) {
                                    $imageUrl = $item['image']; // Full URL
                                } elseif (str_starts_with($item['image'], 'assets/')) {
                                    $imageUrl = asset($item['image']); // Asset path
                                } else {
                                    $imageUrl = asset('storage/' . $item['image']); // Storage path (uploaded)
                                }
                            } else {
                                $imageUrl = asset('assets/img/gallery/g2-img-' . ($index + 1) . '.png');
                            }
                            $instagramUrl = $item['instagram_url'] ?? '#';
                            $size = $item['size'] ?? 'normal';
                        @endphp
                        <!-- img -->
                        <a href="{{ $instagramUrl }}" 
                           target="_blank"
                           aria-label="Instagram post" 
                           class="bs-gallery-2-img {{ $size !== 'normal' ? $size : '' }} popup-img wa-img-cover wa-fix">
                            <img src="{{ $imageUrl }}" alt="Gallery image {{ $index + 1 }}">
                        </a>
                    @endif
                @endforeach

                <div class="link-btn text-center">
                    <a href="{{ $buttonLink }}" target="_blank" aria-label="Follow us on Instagram" class="bs-btn-1">
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

            </div>

        </div>
    </div>
</section>
@endif
