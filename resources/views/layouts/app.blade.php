<!Doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- SEO Meta Tags -->
    {!! \Artesaos\SEOTools\Facades\SEOMeta::generate() !!}
    {!! \Artesaos\SEOTools\Facades\OpenGraph::generate() !!}
    {!! \Artesaos\SEOTools\Facades\TwitterCard::generate() !!}
    {!! \Artesaos\SEOTools\Facades\JsonLd::generate() !!}

    <!-- Additional SEO Meta Tags -->
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">
    <meta name="bingbot" content="index, follow">
    <meta name="author" content="Geometric Development">
    <meta name="publisher" content="Geometric Development">
    <meta name="language" content="English">
    <meta name="geo.region" content="EG">
    <meta name="geo.country" content="Egypt">
    <meta name="geo.placename" content="Cairo, Egypt">
    <meta name="format-detection" content="telephone=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="Geometric Development">
    <meta name="msapplication-tooltip" content="Leading Engineering & Construction Company in Egypt">
    <meta name="msapplication-starturl" content="{{ route('home') }}">

    <!-- Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="//www.google-analytics.com">
    <link rel="dns-prefetch" href="//fonts.googleapis.com">

    <!-- Additional Link Tags -->
    <link rel="alternate" type="application/rss+xml" title="Geometric Development Blog"
        href="{{ route('blog.index') }}">
    <link rel="search" type="application/opensearchdescription+xml" title="Geometric Development"
        href="{{ asset('opensearch.xml') }}">

    @stack('meta')

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/logo/favicon.png') }}">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#1a1a1a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Geometric Development">
    <meta name="msapplication-TileImage" content="{{ asset('assets/img/logo/favicon.png') }}">
    <meta name="msapplication-TileColor" content="#1a1a1a">
    <link rel="manifest" href="{{ route('pwa.manifest') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/logo/favicon.png') }}">

    <!-- all-CSS-link-here -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">

    @stack('styles')

</head>

<body class="@yield('body-class', 'bs-home-5')">
    <div class="main-wrapper wa-fix">

        @include('partials.preloader')

        @include('partials.page-transition')

        <!-- main-content-wrapper -->
        <div class="main-content">

            @include('partials.header')

            @yield('content')

            @include('partials.footer')

            @include('partials.offcanvas')

        </div>
        <!-- end main-content-wrapper -->

        @include('partials.whatsapp-chat')

        @include('partials.back-to-top')

        <!-- PWA Install Button -->
        @include('components.pwa-install-button')

    </div>


    <!-- all-JS-link-here -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/wow.js') }}"></script>
    <script src="{{ asset('assets/js/text-type.js') }}"></script>
    <script src="{{ asset('assets/js/tilt.js') }}"></script>
    <script src="{{ asset('assets/js/nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/marquee.min.js') }}"></script>
    <script src="{{ asset('assets/js/magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/js/SplitText.min.js') }}"></script>
    <script src="{{ asset('assets/js/gsap.min.js') }}"></script>
    <script src="{{ asset('assets/js/CustomEase.min.js') }}"></script>
    <script src="{{ asset('assets/js/counterup.min.js') }}"></script>
    <script src="{{ asset('assets/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/geometric-preloader.js') }}"></script>
    <script src="{{ asset('assets/js/lenis.min.js') }}"></script>
    <script src="{{ asset('assets/js/ScrollTrigger.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/preloader.js') }}"></script> --}}
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- WhatsApp Chat Widget -->
    <script src="{{ asset('assets/js/whatsapp-chat.js') }}?v={{ time() }}"></script>

    <!-- PWA Advanced Features -->
    <script src="{{ asset('assets/js/sync-manager.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/js/notification-manager.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/js/form-sync.js') }}?v={{ time() }}"></script>

    @stack('scripts')

    <!-- Additional Structured Data -->
    <x-structured-data type="Organization" />
    <x-structured-data type="WebSite" />

    <!-- PWA Service Worker Registration -->
    <script>
        // Only register service worker on non-admin pages
        if ('serviceWorker' in navigator && !window.location.pathname.startsWith('/admin') && !window.location.pathname
            .startsWith('/filament')) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('{{ route('pwa.sw') }}')
                    .then(function(registration) {
                        // Check for updates silently without forcing reload
                        registration.addEventListener('updatefound', function() {
                            const newWorker = registration.installing;
                            newWorker.addEventListener('statechange', function() {
                                if (newWorker.state === 'installed' && navigator.serviceWorker
                                    .controller) {
                                    // Silently activate new service worker without reload
                                    newWorker.postMessage({
                                        type: 'SKIP_WAITING'
                                    });
                                    // No automatic reload - let user continue browsing
                                }
                            });
                        });
                    })
                    .catch(function(error) {
                        console.error('ServiceWorker registration failed: ', error);
                    });
            });
        }
    </script>

</body>

</html>
