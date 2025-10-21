{{-- Core Features Section --}}
@php
    $coreFeaturesSection = $aboutPage->sections['core_features'] ?? [];
    $isActive = $coreFeaturesSection['is_active'] ?? true;
    $features = $coreFeaturesSection['items'] ?? [];
@endphp

@if($isActive && !empty($features))
<!-- core-features-start -->
<section class="bs-core-features-1-area pt-120">
    <div class="container bs-container-1">
        <div class="bs-core-features-1-wrap">
            @foreach($features as $index => $feature)
            <!-- single-item -->
            <div class="bs-core-features-1-item">
                <div class="icon">
                    @php
                        $iconImage = !empty($feature['icon'])
                            ? (str_starts_with($feature['icon'], 'http')
                                ? $feature['icon']
                                : (str_starts_with($feature['icon'], 'assets/')
                                    ? asset($feature['icon'])
                                    : asset('storage/' . $feature['icon'])))
                            : asset('assets/img/core-features/cf-icon-1.png');
                    @endphp
                    <img src="{{ $iconImage }}" alt="{{ $feature['title'] ?? '' }}">
                </div>
                <div class="content">
                    <h5 class="bs-h-1 item-title">
                        <a href="{{ $feature['link'] ?? '#' }}" aria-label="{{ $feature['title'] ?? '' }}">{{ $feature['title'] ?? '' }}</a>
                    </h5>
                    <p class="bs-p-1 item-disc">{{ $feature['description'] ?? '' }}</p>
                </div>
            </div>

            @if($index < count($features) - 1)
            <div class="shape">
                <img src="{{ asset('assets/img/illus/star-shape.png') }}" alt="">
            </div>
            @endif
            @endforeach
        </div>
    </div>
</section>
<!-- core-features-end -->
@endif
