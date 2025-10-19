<!Doctype html>
<html class="no-js" lang="zxx">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>@yield('title', 'Geometric Development - Real Estate in Egypt & Emirates')</title>
        <meta name="description" content="@yield('meta-description', 'Geometric Development - Leading Saudi real estate company providing comprehensive residential and commercial solutions in Egypt and Emirates. Discover our premium projects in Ras El Hekma.')">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/logo/favicon.png') }}">

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

    <body class="@yield('body-class', 'bs-home-4')">
        <div class="main-wrapper wa-fix">

            @include('partials.preloader')

            @include('partials.page-transition')

            <!-- main-content-wrapper -->
            <div class="main-content-wrapper">

                <!-- No navbar/header included for project details page -->

                @yield('content')

                @include('partials.footer')

                @include('partials.offcanvas')

            </div>
            <!-- end main-content-wrapper -->

            @include('partials.back-to-top')

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

        @stack('scripts')

    </body>
</html>
