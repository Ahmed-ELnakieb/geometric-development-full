@php
    $countersSection = $sections['counters'] ?? [];
@endphp

@if($countersSection['is_active'] ?? true)
<!-- counter-start -->
<section class="bs-counter-2-area pt-150 pb-50">
    <div class="container bs-container-1">
        <div class="bs-counter-2-wrap" style="flex-wrap: nowrap; gap: 40px;">
            @foreach(($countersSection['items'] ?? []) as $index => $counter)
                @if($index > 0)
                <!-- shape -->
                <h6 class="bs-h-2 shape">*</h6>
                @endif

                <!-- single-item -->
                <div class="bs-counter-2-item">
                    <h4 class="bs-h-2 item-number counter wa-counter" data-cursor="-opaque">{{ $counter['number'] ?? '0' }}</h4>
                    <p class="item-disc bs-p-1">{{ $counter['label'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
<!-- counter-end -->
@endif
