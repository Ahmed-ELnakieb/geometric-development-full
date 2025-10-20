{{-- Blog Section --}}
<section class="bs-blog-3-area pt-85 pb-185">
    <div class="container bs-container-1">
        <div class="bs-blog-3-wrap wa-skew-1">
            <!-- section-title -->
            <div class="bs-blog-3-sec-title wa-p-relative text-center mb-35">
                <h6 class="bs-subtitle-1 wa-capitalize">
                    <span class="icon">
                        <img src="{{ asset('assets/img/hero/h3-start.png') }}" alt="">
                    </span>
                    <span class="text wa-split-clr ">
                        recent blog
                    </span>
                </h6>
                <h2 class="bs-sec-title-3  wa-split-right wa-capitalize" data-cursor="-opaque">news & ideas</h2>
            </div>

            <!-- blog-item -->
            <div class="bs-blog-3-item mb-85">
                @foreach($blogPosts as $post)
                <!-- single-item -->
                <div class="bs-blog-3-item-single wa-skew-1">
                    <div class="item-img wa-fix wa-img-cover">
                        <a href="{{ route('blog.show', $post->slug) }}" aria-label="name" data-cursor-text="View">
                            <img src="{{ $post->getFirstMediaUrl('featured_image') ?: asset('assets/img/random/random (10).png') }}" alt="">
                        </a>
                    </div>
                    <p class="bs-p-1 item-date">{{ $post->published_at->format('d M Y') }}</p>
                    <h4 class="bs-h-1 item-title">
                        <a href="{{ route('blog.show', $post->slug) }}" aria-label="name">{{ $post->title }}</a>
                    </h4>
                    <p class="bs-p-3 item-disc">{{ $post->excerpt }}</p>
                </div>
                @endforeach
            </div>

            <!-- all-btn -->
            <div class="bs-blog-3-all-btn text-center wa-fadeInUp">
                <a href="{{ route('blog.index') }}" aria-label="name" class="bs-btn-1 text-capitalize">
                    <span class="text">
                        view all blogs
                    </span>
                    <span class="icon">
                        <i class="fa-solid fa-right-long"></i>
                        <i class="fa-solid fa-right-long"></i>
                    </span>
                    <span class="shape" ></span>
                </a>
            </div>
        </div>
    </div>
</section>
