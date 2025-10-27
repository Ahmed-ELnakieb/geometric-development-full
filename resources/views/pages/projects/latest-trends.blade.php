@php
    $latestTrendsSection = $sections['latest_trends'] ?? [];
@endphp

@if($latestTrendsSection['is_active'] ?? true)
<!-- project-start -->
<section id="projects" class="bs-project-4-area pt-125 wa-fix">
    <div class="container bs-container-2">

        <div class="bs-project-4-height">

            <div class="bs-project-4-content wa-fix ">
                <h3 class="bs-h-4 big-title title ">{{ $latestTrendsSection['title'] ?? 'Latest' }}</h3>
                <h3 class="bs-h-4 title title-2">
                    @php
                        $subtitle = $latestTrendsSection['subtitle'] ?? 'TRENDS';
                        $midPoint = ceil(strlen($subtitle) / 2);
                        $leftText = substr($subtitle, 0, $midPoint);
                        $rightText = substr($subtitle, $midPoint);
                    @endphp
                    <span class="left-text">{{ $leftText }}</span>
                    <span class="right-text">{{ $rightText }}</span>
                </h3>
            </div>

            <div class="bs-project-4-card-pin">
                <div class="bs-project-4-card ">
                    @foreach ($allProjects->take($latestTrendsSection['max_projects'] ?? 4) as $index => $project)
                        <!-- single-card -->
                        <div class="bs-project-4-card-single has-card-{{ $index + 1 }}">
                            <div class="card-img wa-fix wa-img-cover">
                                <a href="{{ route('projects.show', $project->slug) }}" aria-label="name"
                                    data-cursor-text="View" class="popup-img">
                                    <img src="{{ $project->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (' . (10 + $index) . ').png') }}"
                                        alt="">
                                </a>
                            </div>
                            <div class="content">
                                <h5 class="bs-h-4 title">
                                    <a href="{{ route('projects.show', $project->slug) }}"
                                        aria-label="name">{{ $project->title }}</a>
                                </h5>
                                <ul class="card-details wa-list-style-none">
                                    <li class="bs-p-4">
                                        <span>Location:</span>
                                        {{ $project->location }}
                                    </li>
                                    <li class="bs-p-4">
                                        <span>Type:</span>
                                        {{ $project->categories->pluck('name')->join(', ') ?: $project->type }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>


    </div>
</section>
<!-- project-end -->
@endif
