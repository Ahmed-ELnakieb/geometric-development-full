<!Doctype html>
<html class="no-js" lang="zxx">
    
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>@yield('title', 'Geometric Development - Real Estate in Egypt & Emirates')</title>
        <meta name="description" content="Geometric Development - Leading Saudi real estate company providing comprehensive residential and commercial solutions in Egypt and Emirates. Discover our premium projects in Ras El Hekma.">
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
        <script src="{{ asset('assets/js/lenis.min.js') }}"></script>
        <script src="{{ asset('assets/js/ScrollTrigger.min.js') }}"></script>
        <script src="{{ asset('assets/js/preloader.js') }}"></script>
        <script src="{{ asset('assets/js/main.js') }}"></script>
        
        <!-- PWA Advanced Features -->
        <script src="{{ asset('assets/js/sync-manager.js') }}"></script>
        <script src="{{ asset('assets/js/notification-manager.js') }}"></script>
        <script src="{{ asset('assets/js/form-sync.js') }}"></script>
        
        @stack('scripts')
        
        <!-- PWA Service Worker Registration -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('{{ route("pwa.sw") }}')
                        .then(function(registration) {
                            console.log('ServiceWorker registration successful with scope: ', registration.scope);
                            
                            // Check for updates
                            registration.addEventListener('updatefound', function() {
                                const newWorker = registration.installing;
                                newWorker.addEventListener('statechange', function() {
                                    if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                        // New content is available, refresh the page
                                        if (confirm('New version available! Refresh to update?')) {
                                            window.location.reload();
                                        }
                                    }
                                });
                            });
                        })
                        .catch(function(error) {
                            console.log('ServiceWorker registration failed: ', error);
                        });
                });
            }
        </script>
        
    </body>
</html>