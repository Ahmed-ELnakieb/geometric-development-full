{{-- Counters Section --}}
@php
    $countersSection = $aboutPage->sections['counters'] ?? [];
    $isActive = $countersSection['is_active'] ?? true;
    $items = $countersSection['items'] ?? [];
@endphp

@if($isActive && !empty($items))
<!-- counter-start -->
<section class="bs-core-feature-5-area mb-160">
    <div class="container bs-container-2">
        <div class="bs-core-feature-4-line wa-scaleXInUp"></div>
        <div class="bs-core-feature-4-wrap has-5">
            @foreach($items as $index => $item)
            <!-- single-item -->
            <div class="bs-core-feature-4-item wow fadeInRight" data-wow-delay="{{ $index * 0.2 }}s">
                <h4 class="bs-h-4 item-title">
                    {{ $item['title'] ?? '' }}
                </h4>
                <h5 class="bs-h-4 item-counter" data-cursor="-opaque">
                    <span class="counter wa-counter">{{ $item['value'] ?? '0' }}</span>
                    {{ $item['suffix'] ?? '' }}
                </h5>
                <p class="bs-p-4 item-disc">{{ $item['description'] ?? '' }}</p>
            </div>
            @endforeach
        </div>
        <div class="bs-core-feature-4-line wa-scaleXInUp"></div>
    </div>
</section>
<!-- counter-end -->
@endif
