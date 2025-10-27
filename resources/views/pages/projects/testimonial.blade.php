@php
    $testimonialSection = $sections['testimonial'] ?? [];
@endphp

@if($testimonialSection['is_active'] ?? true)
<!-- testimonial-start -->
<section class="bs-testimonial-4-area wa-fix wa-p-relative pt-130 pb-50 ">

    <div class="bs-testimonial-4-bg" data-background="{{ asset($testimonialSection['background_image'] ?? 'assets/img/testimonial/t4-bg.png') }}"></div>

    <div class="container bs-container-2">
        <div class="bs-testimonial-4-content">
            <div class="inner-div" style="display: block; text-align: center;">
                <h2 class="bs-sec-title-4 title wa-split-right wa-capitalize" data-cursor="-opaque"
                    style="color: #000; max-width: 100%; margin-left: auto; margin-right: auto;">{{ $testimonialSection['title'] ?? 'Building Dreams Across Horizons' }}</h2>

                <p class="bs-p-4"
                    style="max-width: 800px; margin: 30px auto 0; line-height: 1.8; color: #000; text-align: center;">
                    {{ $testimonialSection['description'] ?? 'Embark on a journey where the serenity of nature meets the elegance of modern living.' }}
                </p>

            </div>
        </div>
    </div>
</section>
<!-- testimonial-end -->
@endif
