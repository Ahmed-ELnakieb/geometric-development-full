{{-- Video Section --}}
@php
    $video = $homePage->sections['video'] ?? [];
    $videoIsActive = $video['is_active'] ?? true;
    $youtubeUrl = $video['youtube_url'] ?? 'https://www.youtube.com/watch?v=e45TPIcx5CY';
    $autoplay = $video['autoplay'] ?? true;
    $loop = $video['loop'] ?? true;
    $muted = $video['muted'] ?? true;
    
    // Handle video file path
    $videoFile = null;
    if (!empty($video['video_file'])) {
        if (str_starts_with($video['video_file'], 'http')) {
            $videoFile = $video['video_file'];
        } elseif (str_starts_with($video['video_file'], 'assets/')) {
            $videoFile = asset($video['video_file']);
        } else {
            $videoFile = asset('storage/' . $video['video_file']);
        }
    } else {
        $videoFile = asset('assets/img/video/v2-video-1.mp4');
    }
@endphp

@if($videoIsActive)
<div class="bs-video-1-area wa-fix">
    <div class="bs-video-1-content wa-p-relative">
        <div class="bs-video-1-content-img has-video-2  wa-p-relative wa-fix wa-img-cover">
            <a href="{{ $youtubeUrl }}" aria-label="Play video" class="popup-video" data-cursor-text="play">
                <video class="wa-parallax-img" 
                       src="{{ $videoFile }}" 
                       @if($autoplay) autoplay @endif
                       @if($loop) loop @endif
                       @if($muted) muted @endif>
                </video>
            </a>

            <div class="bs-video-1-play-btn">
                <a href="{{ $youtubeUrl }}" aria-label="Play video" class="bs-play-btn-3 wa-magnetic popup-video">
                    <span class="icon wa-magnetic-btn">
                        <i class="fa-solid fa-play"></i>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>
@endif
