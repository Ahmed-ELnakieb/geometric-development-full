{{-- Projects Portfolio Section --}}
@php
    $portfolioSection = $aboutPage->sections['portfolio'] ?? [];
    $isActive = $portfolioSection['is_active'] ?? true;
    $subtitle = $portfolioSection['subtitle'] ?? 'projects';
    $title = $portfolioSection['title'] ?? 'Our Development Portfolio';
    $featuredImage = !empty($portfolioSection['featured_image'])
        ? (str_starts_with($portfolioSection['featured_image'], 'http') ? $portfolioSection['featured_image'] : (str_starts_with($portfolioSection['featured_image'], 'assets/') ? asset($portfolioSection['featured_image']) : asset('storage/' . $portfolioSection['featured_image'])))
        : asset('assets/img/award/a5-img-1.png');
    $useRealProjects = $portfolioSection['use_real_projects'] ?? true;
    $projectLimit = $portfolioSection['project_limit'] ?? 6;
@endphp

@if($isActive)
<!-- award-start -->
<section class="bs-award-5-area pt-135 wa-fix">
    <div class="container bs-container-2">
        <div class="bs-award-5-wrap">

            <!-- left-content -->
            <div class="bs-award-5-content">

                <!-- section-title -->
                <div class="bs-award-5-sec-title mb-50">
                    <h6 class="bs-subtitle-5 wa-capitalize">
                        <span class="wa-split-right">{{ $subtitle }}</span>
                    </h6>
                    <h2 class="bs-sec-title-4 wa-split-right wa-capitalize" data-cursor="-opaque">{{ $title }}</h2>
                </div>

                <!-- img -->
                <div class="bs-award-5-img wa-fix wa-img-cover wa-clip-top-bottom" data-cursor="-opaque" data-split-duration="1.5s">
                    <img src="{{ $featuredImage }}" alt="">
                </div>

            </div>

            <!-- right-list -->
            <div class="bs-award-5-item">
                @if($useRealProjects)
                    @php
                        $projects = \App\Models\Project::where('is_published', true)
                            ->where('is_featured', true)
                            ->orderBy('display_order')
                            ->limit($projectLimit)
                            ->get();
                    @endphp
                    @foreach($projects as $project)
                    <!-- single-item -->
                    <a href="{{ route('projects.show', $project->slug) }}" class="bs-award-5-item-single wa-fadeInUp">
                        <h3 class="bs-h-4 year">{{ $project->completion_date ? $project->completion_date->format('Y') : date('Y') }}</h3>
                        <h4 class="bs-h-4 title">{{ $project->title }}</h4>
                        <span class="icon">
                            <i class="flaticon-next-1 flaticon"></i>
                        </span>
                        <span class="item-img cursor-follow">
                            <img src="{{ $project->getFirstMediaUrl('gallery') ?: asset('assets/img/award/a5-item-img-1.png') }}" alt="{{ $project->title }}">
                        </span>
                    </a>
                    @endforeach
                @else
                    @foreach($portfolioSection['projects'] ?? [] as $project)
                    @php
                        $projectImage = !empty($project['image'])
                            ? (str_starts_with($project['image'], 'http') ? $project['image'] : (str_starts_with($project['image'], 'assets/') ? asset($project['image']) : asset('storage/' . $project['image'])))
                            : asset('assets/img/award/a5-item-img-1.png');
                    @endphp
                    <!-- single-item -->
                    <a href="{{ $project['link'] ?? '#' }}" class="bs-award-5-item-single wa-fadeInUp">
                        <h3 class="bs-h-4 year">{{ $project['year'] ?? date('Y') }}</h3>
                        <h4 class="bs-h-4 title">{{ $project['title'] ?? '' }}</h4>
                        <span class="icon">
                            <i class="flaticon-next-1 flaticon"></i>
                        </span>
                        <span class="item-img cursor-follow">
                            <img src="{{ $projectImage }}" alt="{{ $project['title'] ?? '' }}">
                        </span>
                    </a>
                    @endforeach
                @endif
            </div>


        </div>
    </div>
</section>
<!-- award-end -->
@endif
