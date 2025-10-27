@php
    $featuredProjectsSection = $sections['featured_projects'] ?? [];
@endphp

@if($featuredProjectsSection['is_active'] ?? true)
<!-- core-services-start -->
<section class="bs-core-services-2-area wa-bg-default wa-fix"
    data-background="{{ asset($featuredProjectsSection['background_image'] ?? 'assets/img/random/geometric1.png') }}">
    <div class="bs-core-services-2-wrap">
        @php
            $featuredQuery = $allProjects;
            if ($featuredProjectsSection['show_only_featured'] ?? true) {
                $featuredQuery = $allProjects->where('is_featured', true);
            }
            $maxFeatured = $featuredProjectsSection['max_projects'] ?? 4;
        @endphp
        @foreach ($featuredQuery->take($maxFeatured) as $featuredProject)
            <!-- single-item -->
            <div class="bs-core-services-2-item ">
                <h5 class="bs-h-2 item-title ">
                    <span class="wa-split-up wa-capitalize wa-fix">
                        {{ $featuredProject->title }}
                    </span>

                    <span class="small-text">{{ $featuredProject->location }}</span>
                </h5>
                <div class="content-wrap">
                    <h5 class="bs-h-2 item-title">
                        {{ $featuredProject->title }}
                        <span class="small-text">{{ $featuredProject->location }}</span>
                    </h5>

                    <p class="bs-p-1 item-disc">
                        {{ Str::limit($featuredProject->excerpt, 200) }}
                    </p>

                    <div class="item-btn">
                        <a href="{{ route('projects.show', $featuredProject->slug) }}" aria-label="name"
                            class="bs-btn-1">
                            <span class="text">
                                View Project
                            </span>
                            <span class="shape"></span>
                        </a>
                    </div>
                </div>

                <div class="item-img wa-fix wa-img-cover">
                    <img src="{{ $featuredProject->getMedia('gallery')->first()?->getUrl() ?? asset('assets/img/random/random (12).png') }}"
                        alt="">
                </div>
            </div>
        @endforeach
    </div>
</section>
<!-- core-services-end -->
@endif
