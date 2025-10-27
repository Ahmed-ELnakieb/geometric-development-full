@php
    $videoSection = $sections['video'] ?? [];
@endphp

@if($videoSection['is_active'] ?? true)
<!-- video-start -->
<div class="bs-video-5-area wa-fix wa-p-relative wa-img-cover" style="margin-top: 150px;">
    <img class="wa-parallax-img" src="{{ asset($videoSection['background_image'] ?? 'assets/img/random/random (27).png') }}" alt="">
    <div class="bs-video-5-btn">
        <a href="{{ $videoSection['video_url'] ?? 'https://www.youtube.com/watch?v=e45TPIcx5CY' }}" aria-label="name"
            class="bs-play-btn-5 video-popup wa-magnetic-btn">
            <i class="fa-solid fa-play"></i>
        </a>
    </div>
</div>
<!-- video-end -->
@endif
