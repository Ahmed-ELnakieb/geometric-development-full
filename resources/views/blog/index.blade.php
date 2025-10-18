@extends('layouts.app')

@section('title', 'Blogs and News - Geometric Development')

@section('body-class', '')

@section('content')
    <!-- breadcrumb-start -->
    <section class="breadcrumb-area wa-p-relative">
        <div class="breadcrumb-bg-img wa-fix wa-img-cover">
            <img class="wa-parallax-img" src="{{ asset('assets/img/breadcrumb/breadcrumb-img.png') }}" alt="">
        </div>

        <div class="container bs-container-1">
            <div class="breadcrumb-wrap">
                <h1 class="breadcrumb-title wa-split-right wa-capitalize" data-split-delay="1.1s">
                    @if(isset($category))
                        Category: {{ $category->name }}
                    @else
                        Blogs and News
                    @endif
                </h1>

                <div class="breadcrumb-list">
                    <svg class="breadcrumb-list-shape" width="88" height="91" viewBox="0 0 88 91" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M75.3557 83.4825C51.6516 78.2316 30.2731 65.4227 30.8424 38.6307C29.0856 37.5878 27.3642 36.4078 25.6807 35.1082C15.8629 27.5282 7.34269 15.8295 0.970618 3.77828L0 1.94173L3.67259 0L4.64321 1.83605C10.7341 13.3558 18.8345 24.574 28.2197 31.82C29.1853 32.5658 30.1649 33.2687 31.1564 33.9242C31.7447 28.7351 34.2557 18.3221 41.4244 12.7755C53.1965 3.6676 66.5598 9.52271 70.2762 19.1546C74.5799 30.309 65.1659 39.6328 59.589 41.7844C51.0354 45.0846 42.7385 44.3218 35.01 40.8138C35.681 63.7945 54.9747 74.6677 76.0057 79.3717L77.1209 72.3207L87.9707 83.4999L74.2006 90.7853L75.3557 83.4825ZM35.1147 36.2473C42.2964 39.9314 50.0548 41.0102 58.0934 37.9089C62.3617 36.2618 69.6945 29.1868 66.4003 20.6502C63.5203 13.1858 53.0893 9.00325 43.9669 16.0613C37.698 20.9114 35.7338 30.1584 35.2637 34.5703C35.2034 35.1366 35.1536 35.696 35.1147 36.2473Z" fill="white"/>
                    </svg>

                    <a href="{{ route('home') }}">Home</a>
                    <span>
                        @if(isset($category))
                            Category: {{ $category->name }}
                        @else
                            Blogs and News
                        @endif
                    </span>
                </div>

            </div>
        </div>
    </section>
    <!-- breadcrumb-end -->

    <!-- blog-start -->
    <section class="bs-blog-1-area wa-p-relative pt-140 pb-80">

        <div class="bs-blog-1-bg-color"></div>

        <div class="container bs-container-1">

            <!-- section-title -->
            <div class="bs-blog-1-sec-title text-center mb-40">
                <h6 class="bs-subtitle-1 wa-split-clr wa-capitalize">
                    <span class="icon">
                        <img src="{{ asset('assets/img/illus/star-shape.png') }}" alt="">
                    </span>
                    Latest Real Estate Insights
                </h6>
                <h2 class="bs-sec-title-1 wa-split-right wa-capitalize" data-cursor="-opaque">
                    @if(isset($category))
                        Posts in {{ $category->name }}
                    @else
                        Market Updates & News
                    @endif
                </h2>
            </div>

            <div class="bs-blog-1-wrap">
                @foreach($featuredPosts as $post)
                    <!-- single-blog -->
                    <div class="bs-blog-1-item wa-3dUp">
                        <div class="item-img wa-fix wa-img-cover">
                            <a href="{{ route('blog.show', $post->slug) }}" aria-label="name" data-cursor-text="View">
                                <img src="{{ $post->featuredImage ? $post->featuredImage->getUrl() : asset('assets/img/random/random (10).png') }}" alt="">
                            </a>
                        </div>
                        <p class="item-date bs-p-1">
                            <span>{{ $post->published_at->format('d') }}</span>
                            <span>{{ $post->published_at->format('M') }}</span>
                        </p>
                        <div class="content">
                            <h5 class="bs-h-1 item-title">
                                <a href="{{ route('blog.show', $post->slug) }}" aria-label="name">{{ $post->title }}</a>
                            </h5>
                            <p class="blog-meta bs-p-1">
                                <span>
                                    <i class="fa-regular fa-user"></i>
                                    by {{ $post->author->name }}
                                </span>
                                <span>
                                    <i class="fa-regular fa-comment"></i>
                                    {{ $post->approvedComments->count() }} Comments
                                </span>
                            </p>
                            <p class="bs-p-1 item-disc">{{ Str::limit($post->excerpt, 150) }}</p>

                            <div class="item-btn">
                                <a href="{{ route('blog.show', $post->slug) }}" aria-label="name" class="bs-btn-1">
                                    <span class="text">
                                        read more
                                    </span>
                                    <span class="icon">
                                        <i class="fa-solid fa-right-long"></i>
                                        <i class="fa-solid fa-right-long"></i>
                                    </span>
                                    <span class="shape"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>
    <!-- blog-end -->

    <!-- blog-item-start -->
    <section class="bs-blog-6-area pt-50 pb-50">
        <div class="container bs-container-1">
            
            <div class="bs-blog-4-wrap mb-50">
                @foreach($posts as $post)
                    <!-- single-blog -->
                    <div class="bs-blog-4-item wow fadeInRight">
                        <div class="item-img wa-fix wa-img-cover">
                            <a href="{{ route('blog.show', $post->slug) }}" aria-label="name" data-cursor-text="View">
                                <img src="{{ $post->featuredImage ? $post->featuredImage->getUrl() : asset('assets/img/random/random (12).png') }}" alt="">
                            </a>
                        </div>
                        <div class="content">
                            <a href="{{ route('blog.show', $post->slug) }}" aria-label="name" class="item-btn">
                                <i class="flaticon-top-right flaticon"></i>
                            </a>
                            <p class="bs-p-4 author">{{ $post->author->name }}</p>
                            <h4 class="title bs-h-1">
                                <a href="{{ route('blog.show', $post->slug) }}" aria-label="name">{{ $post->title }}</a>
                            </h4>
                            <p class="item-meta bs-p-4">
                                @foreach($post->categories as $category)
                                    <span class="categories">{{ $category->name }}</span>
                                @endforeach
                                <span class="date">{{ $post->published_at->format('M d, Y') }}</span>
                                <span class="read">{{ $post->read_time }} min read</span>
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($posts->isEmpty())
                <p>No blog posts available.</p>
            @endif

            {{ $posts->links('vendor.pagination.custom') }}
        </div>
    </section>
    <!-- blog-item-end -->
@endsection
