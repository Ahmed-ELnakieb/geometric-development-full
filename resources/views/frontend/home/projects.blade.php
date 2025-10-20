{{-- Projects Section --}}
@php
    $projectsSection = $homePage->sections['projects'] ?? [];
    $projectsIsActive = $projectsSection['is_active'] ?? true;
    $sectionTitle = $projectsSection['section_title'] ?? 'RESIDENTIAL PROPERTIES';
    $sectionSubtitle = $projectsSection['section_subtitle'] ?? '';
    $projectLimit = $projectsSection['project_limit'] ?? 6;
    $showButton = $projectsSection['show_button'] ?? true;
    $buttonText = $projectsSection['button_text'] ?? 'view all projects';
    $buttonBgImage = !empty($projectsSection['button_bg_image'])
        ? (str_starts_with($projectsSection['button_bg_image'], 'http')
            ? $projectsSection['button_bg_image']
            : (str_starts_with($projectsSection['button_bg_image'], 'assets/')
                ? asset($projectsSection['button_bg_image'])
                : asset('storage/' . $projectsSection['button_bg_image'])))
        : asset('assets/img/projects/p5-btn-bg-shape.png');
@endphp

@if($projectsIsActive)
<section class="bs-project-5-area pt-135 pb-140 wa-fix">
    <div class="container bs-container-2">
        <h2 class="bs-project-5-sec-title wa-split-right wa-capitalize">{{ $sectionTitle }}</h2>
        @if($sectionSubtitle)
        <p class="bs-p-4 text-center mb-40">{{ $sectionSubtitle }}</p>
        @endif

        <div class="bs-project-5-wrap wa-p-relative">
            @foreach($projects->take($projectLimit) as $index => $project)
            @if($index == 0)
            <!-- single-project -->
            <div class="bs-project-5-item">
                <div class="main-img wa-fix wa-img-cover">
                    <a href="{{ route('projects.show', $project->slug) }}" data-cursor-text="View">
                        <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (10).png') }}" alt="">
                    </a>
                </div>
                <h5 class="bs-h-4 title">
                    <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">{{ $project->title }}</a>
                </h5>
                <ul class="item-list wa-list-style-none">
                    <li class="bs-p-4">{{ $project->location }}</li>
                    <li class="bs-p-4">More Details</li>
                </ul>
            </div>
            @elseif($index == 1)
            <!-- single-project -->
            <div class="bs-project-5-item  height-2">
                <div class="main-img wa-fix wa-img-cover">
                    <a href="{{ route('projects.show', $project->slug) }}" data-cursor-text="View">
                        <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (10).png') }}" alt="">
                    </a>
                </div>
                <h5 class="bs-h-4 title">
                    <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">{{ $project->title }}</a>
                </h5>
                <ul class="item-list wa-list-style-none">
                    <li class="bs-p-4">{{ $project->location }}</li>
                    <li class="bs-p-4">More Details</li>
                </ul>
            </div>
            @endif
            @endforeach
        </div>

        <div class="bs-project-5-wrap-2  wa-p-relative">
            @foreach($projects->take($projectLimit) as $index => $project)
            @if($index == 2)
            <!-- single-project -->
            <div class="bs-project-5-item height-3">
                <div class="main-img wa-fix wa-img-cover">
                    <a href="{{ route('projects.show', $project->slug) }}" data-cursor-text="View">
                        <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (10).png') }}" alt="">
                    </a>
                </div>
                <h5 class="bs-h-4 title">
                    <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">{{ $project->title }}</a>
                </h5>
                <ul class="item-list wa-list-style-none">
                    <li class="bs-p-4">{{ $project->location }}</li>
                    <li class="bs-p-4">More Details</li>
                </ul>
            </div>
            @endif
            @endforeach
        </div>

        <div class="bs-project-5-wrap-3 mb-60  wa-p-relative">
            @foreach($projects->take($projectLimit) as $index => $project)
            @if($index == 3)
            <!-- single-project -->
            <div class="bs-project-5-item height-4">
                <div class="main-img wa-fix wa-img-cover">
                    <a href="{{ route('projects.show', $project->slug) }}" data-cursor-text="View">
                        <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (10).png') }}" alt="">
                    </a>
                </div>
                <h5 class="bs-h-4 title">
                    <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">{{ $project->title }}</a>
                </h5>
                <ul class="item-list wa-list-style-none">
                    <li class="bs-p-4">{{ $project->location }}</li>
                    <li class="bs-p-4">More Details</li>
                </ul>
            </div>
            @elseif($index == 4)
            <!-- single-project -->
            <div class="bs-project-5-item height-5">
                <div class="main-img wa-fix wa-img-cover">
                    <a href="{{ route('projects.show', $project->slug) }}" data-cursor-text="View">
                        <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (10).png') }}" alt="">
                    </a>
                </div>
                <h5 class="bs-h-4 title">
                    <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">{{ $project->title }}</a>
                </h5>
                <ul class="item-list wa-list-style-none">
                    <li class="bs-p-4">{{ $project->location }}</li>
                    <li class="bs-p-4">More Details</li>
                </ul>
            </div>
            @elseif($index == 5)
            <!-- single-project -->
            <div class="bs-project-5-item height-5">
                <div class="main-img wa-fix wa-img-cover">
                    <a href="{{ route('projects.show', $project->slug) }}" data-cursor-text="View">
                        <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (10).png') }}" alt="">
                    </a>
                </div>
                <h5 class="bs-h-4 title">
                    <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">{{ $project->title }}</a>
                </h5>
                <ul class="item-list wa-list-style-none">
                    <li class="bs-p-4">{{ $project->location }}</li>
                    <li class="bs-p-4">More Details</li>
                </ul>
            </div>
            @endif
            @endforeach
        </div>
    </div>

    @if($showButton)
    <div class="bs-project-5-all-btn wa-fadeInUp" data-background="{{ $buttonBgImage }}">
        <a href="{{ route('projects.index') }}" aria-label="{{ $buttonText }}" class="bs-pr-btn-3">
            <span class="text">{{ $buttonText }} <i class="fa-solid fa-angle-right"></i></span>
            <span class="text">{{ $buttonText }} <i class="fa-solid fa-angle-right"></i></span>
        </a>
    </div>
    @endif
</section>
@endif
