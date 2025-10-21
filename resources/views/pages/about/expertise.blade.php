{{-- Expertise Section --}}
@php
    $expertiseSection = $aboutPage->sections['expertise'] ?? [];
    $isActive = $expertiseSection['is_active'] ?? true;
    $title = $expertiseSection['title'] ?? 'Geometric Development';
    $tags = $expertiseSection['tags'] ?? [];
@endphp

@if($isActive && !empty($tags))
<!-- expertise-start -->
<section class="bs-expertise-4-area wa-fix pt-100">
    <div class="container bs-container-2">

        <div class="bs-expertise-4-wrap">
            <h3 class="bs-h-4 bs-expertise-4-title">
                @foreach(explode(' ', $title) as $index => $word)
                <span class="wa-split-up wa-capitalize-hidden" @if($index > 0) data-split-delay="{{ $index }}s" @endif>{{ $word }}</span>
                @endforeach
            </h3>


            <div class="bs-expertise-4-box" txa-matter-scene="true">
                @foreach($tags as $tag)
                <div class="single-tag" txa-matter-item>
                    <span class="single-tag-item bs-p-4">
                        @if($tag['icon_position'] ?? 'left' === 'left')
                        <span class="icon {{ $tag['icon_color'] ?? 'has-clr-3' }}">
                            <i class="{{ $tag['icon'] ?? 'flaticon-check' }} flaticon"></i>
                        </span>
                        @endif
                        <span class="text">{{ $tag['text'] ?? '' }}</span>
                        @if($tag['icon_position'] ?? 'left' === 'right')
                        <span class="icon {{ $tag['icon_color'] ?? 'has-clr-3' }}">
                            <i class="{{ $tag['icon'] ?? 'flaticon-check' }} flaticon"></i>
                        </span>
                        @endif
                    </span>
                </div>
                @endforeach
             </div>
        </div>

    </div>
</section>
<!-- expertise-end -->
@endif
