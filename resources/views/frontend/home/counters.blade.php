{{-- Counters Section --}}
@if(($homePage->sections['counters']['is_active'] ?? true))
<section class="bs-core-feature-5-area">
    <div class="container bs-container-2">
        <div class="bs-core-feature-4-line wa-scaleXInUp"></div>
        <div class="bs-core-feature-4-wrap has-5">

            <!-- single-item -->
            <div class="bs-core-feature-4-item wow fadeInRight">
                <h4 class="bs-h-4 item-title">
                    Properties Sold
                </h4>
                <h5 class="bs-h-4 item-counter" data-cursor="-opaque">
                    <span class="counter wa-counter">2.5</span>
                    k+
                </h5>
                <p class="bs-p-4 item-disc">Successfully helping thousands of clients find their perfect properties across UAE.</p>
            </div>

            <!-- single-item -->
            <div class="bs-core-feature-4-item wow fadeInRight" data-wow-delay="0.2s">
                <h4 class="bs-h-4 item-title">
                    Years in Real Estate
                </h4>
                <h5 class="bs-h-4 item-counter" data-cursor="-opaque">
                    <span class="counter wa-counter">15</span>
                    +
                </h5>
                <p class="bs-p-4 item-disc">Over 15 years of expertise in UAE real estate market and property sales.</p>
            </div>

            <!-- single-item -->
            <div class="bs-core-feature-4-item wow fadeInRight" data-wow-delay="0.4s">
                <h4 class="bs-h-4 item-title">
                    Happy Homeowners
                </h4>
                <h5 class="bs-h-4 item-counter" data-cursor="-opaque">
                    <span class="counter wa-counter">98</span>
                    %
                </h5>
                <p class="bs-p-4 item-disc">98% of our clients are satisfied with their property purchases and our service.</p>
            </div>

            <!-- single-item -->
            <div class="bs-core-feature-4-item wow fadeInRight" data-wow-delay="0.6s">
                <h4 class="bs-h-4 item-title">
                    Market Leadership
                </h4>
                <h5 class="bs-h-4 item-counter" data-cursor="-opaque">
                    <span class="counter wa-counter">1</span>
                    st
                </h5>
                <p class="bs-p-4 item-disc">Leading real estate company specializing in premium Ras Al Khaimah properties.</p>
            </div>

        </div>
        <div class="bs-core-feature-4-line wa-scaleXInUp"></div>
    </div>
</section>
@endif
