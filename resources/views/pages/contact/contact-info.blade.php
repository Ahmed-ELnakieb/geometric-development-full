{{-- Contact Info Section --}}
@php
    $contactInfoSection = $contactPage->sections['contact_info'] ?? [];
    $isActive = $contactInfoSection['is_active'] ?? true;
    $items = $contactInfoSection['items'] ?? [];
@endphp

@if($isActive && !empty($items))
<!-- core-feature-start -->
<section class="bs-core-feature-6-area pt-125">
    <div class="container bs-container-1">
        <div class="bs-core-feature-4-line wa-scaleXInUp"></div>
        <div class="bs-core-feature-6-wrap">

            @foreach($items as $index => $item)
            <!-- single-item -->
            <div class="bs-core-feature-4-item wow fadeInRight" data-wow-delay="{{ $index * 0.2 }}s">
                <h4 class="bs-h-4 item-title">
                    <a href="#" aria-label="{{ $item['title'] ?? '' }}">{{ $item['title'] ?? '' }}</a>
                </h4>
                <div class="item-icon">
                    <i class="{{ $item['icon'] ?? 'fas fa-info-circle' }}" data-cursor="-opaque" style="font-size: 61px; color: inherit;"></i>
                </div>
                
                @if(!empty($item['links']))
                    @foreach($item['links'] as $link)
                        @if($link['type'] ?? 'link' === 'link')
                            <a href="{{ $link['url'] ?? '#' }}" 
                               @if($link['new_tab'] ?? false) target="_blank" @endif
                               class="bs-p-4 item-disc" 
                               @if(($link['url'] ?? '#') !== '#') style="display: block;" @endif>
                                {{ $link['text'] ?? '' }}
                            </a>
                        @else
                            <p class="bs-p-4 item-disc">{{ $link['text'] ?? '' }}</p>
                        @endif
                    @endforeach
                @endif
            </div>
            @endforeach

        </div>
        <div class="bs-core-feature-4-line wa-scaleXInUp"></div>
    </div>
</section>
<!-- core-feature-end -->
@endif
