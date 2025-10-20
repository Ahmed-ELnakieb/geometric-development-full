{{-- Blog Section --}}
@php
    $blogSection = $homePage->sections['blog'] ?? [];
    $blogIsActive = $blogSection['is_active'] ?? true;
    $sectionSubtitle = $blogSection['section_subtitle'] ?? 'recent blog';
    $sectionTitle = $blogSection['section_title'] ?? 'news & ideas';
    $postLimit = $blogSection['post_limit'] ?? 3;
    $showButton = $blogSection['show_button'] ?? true;
    $buttonText = $blogSection['button_text'] ?? 'view all blogs';
    $showDate = $blogSection['show_date'] ?? true;
    $showExcerpt = $blogSection['show_excerpt'] ?? true;
    $iconImage = !empty($blogSection['icon_image'])
        ? (str_starts_with($blogSection['icon_image'], 'http')
            ? $blogSection['icon_image']
            : (str_starts_with($blogSection['icon_image'], 'assets/')
                ? asset($blogSection['icon_image'])
                : asset('storage/' . $blogSection['icon_image'])))
        : asset('assets/img/hero/h3-start.png');
@endphp

@if($blogIsActive)
<section class="bs-blog-3-area pt-85 pb-185">
    <div class="container bs-container-1">
        <div class="bs-blog-3-wrap wa-skew-1">
            <!-- section-title -->
            <div class="bs-blog-3-sec-title wa-p-relative text-center mb-35">
                <h6 class="bs-subtitle-1 wa-capitalize">
                    <span class="icon">
                        <img src="{{ $iconImage }}" alt="">
                    </span>
                    <span class="text wa-split-clr ">
                        {{ $sectionSubtitle }}
                    </span>
                </h6>
                <h2 class="bs-sec-title-3  wa-split-right wa-capitalize" data-cursor="-opaque">{{ $sectionTitle }}</h2>
            </div>

            <!-- blog-item -->
            <div class="bs-blog-3-item mb-85">
                @foreach($blogPosts->take($postLimit) as $post)
                <!-- single-item -->
                <div class="bs-blog-3-item-single wa-skew-1">
                    <div class="item-img wa-fix wa-img-cover">
                        <a href="{{ route('blog.show', $post->slug) }}" aria-label="{{ $post->title }}" data-cursor-text="View">
                            <img src="{{ $post->getFirstMediaUrl('featured_image') ?: asset('assets/img/random/random (10).png') }}" alt="{{ $post->title }}">
                        </a>
                    </div>
                    @if($showDate)
                    <p class="bs-p-1 item-date">{{ $post->published_at->format('d M Y') }}</p>
                    @endif
                    <h4 class="bs-h-1 item-title">
                        <a href="{{ route('blog.show', $post->slug) }}" aria-label="{{ $post->title }}">{{ $post->title }}</a>
                    </h4>
                    @if($showExcerpt && $post->excerpt)
                    <p class="bs-p-3 item-disc">{{ $post->excerpt }}</p>
                    @endif
                </div>
                @endforeach
            </div>

            <!-- all-btn -->
            @if($showButton)
            <div class="bs-blog-3-all-btn text-center wa-fadeInUp">
                <a href="{{ route('blog.index') }}" aria-label="{{ $buttonText }}" class="bs-btn-1 text-capitalize">
                    <span class="text">
                        {{ $buttonText }}
                    </span>
                    <span class="icon">
                        <i class="fa-solid fa-right-long"></i>
                        <i class="fa-solid fa-right-long"></i>
                    </span>
                    <span class="shape" ></span>
                </a>
            </div>
            @endif
        </div>
    </div>
</section>
@endif
