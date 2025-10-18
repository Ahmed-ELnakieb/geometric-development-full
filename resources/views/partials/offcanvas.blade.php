<!-- offcanvas-start -->
<div class="wa-offcanvas-area has-home-2 offcanvas_box_active lenis lenis-smooth">
    <div class="wa-offcanvas-wrap">

        <!-- top -->
        <div class="wa-offcanvas-top">

            <!-- logo -->
            <a href="{{ route('home') }}" aria-label="name" class="wa-offcanvas-top-logo">
                <img src="{{ asset('assets/img/logo/logo_dark.png') }}" alt="">
            </a>

            <!-- close-btn -->
            <button class="wa-offcanvas-close offcanvas_box_close" aria-label="name">
                <span></span>
                <span></span>
            </button>

        </div>

        <!-- mobile-menu-list -->
        <nav class="mobile-main-navigation mb-50 d-block d-lg-none">
            <ul class="navbar-nav">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('page.show', 'about') }}">About</a></li>
                <li><a href="{{ route('projects.index') }}">Projects</a></li>
                <li><a href="{{ route('careers.index') }}">Vacancies</a></li>
                <li><a href="{{ route('blog.index') }}">Blogs</a></li>
            </ul>
        </nav>

        <div class="wa-offcanvas-gallery">
            <h6 class="wa-offcanvas-gallery-title bs-h-1">Stay Inspired with Instagram</h6>

            <div class="wa-offcanvas-gallery-grid">
                <a href="#" aria-label="name" class="popup-img wa-offcanvas-gallery-item wa-img-cover wa-fix">
                    <img src="{{ asset('assets/img/gallery/g2-img-1.png') }}" alt="">
                </a>
                <a href="#" aria-label="name" class="popup-img wa-offcanvas-gallery-item wa-img-cover wa-fix">
                    <img src="{{ asset('assets/img/gallery/g2-img-2.png') }}" alt="">
                </a>
                <a href="#" aria-label="name" class="popup-img wa-offcanvas-gallery-item wa-img-cover wa-fix">
                    <img src="{{ asset('assets/img/gallery/g2-img-3.png') }}" alt="">
                </a>
                <a href="#" aria-label="name" class="popup-img wa-offcanvas-gallery-item wa-img-cover wa-fix">
                    <img src="{{ asset('assets/img/gallery/g2-img-4.png') }}" alt="">
                </a>
            </div>
        </div>

        <!-- social -->
        <div class="wa-offcanvas-social">
            <h6 class="wa-offcanvas-social-title bs-h-1">we're on social media:</h6>

            <div class="wa-offcanvas-social-flex d-flex flex-wrap">
                <a href="#" class="wa-offcanvas-social-link" aria-label="name">
                    <i class="fa-brands fa-linkedin-in"></i>
                </a>
                <a href="#" class="wa-offcanvas-social-link" aria-label="name">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
                <a href="#" class="wa-offcanvas-social-link" aria-label="name">
                    <i class="fa-brands fa-x-twitter"></i>
                </a>
                <a href="#" class="wa-offcanvas-social-link" aria-label="name">
                    <i class="fa-brands fa-instagram"></i>
                </a>
            </div>

        </div>

    </div>

</div>
<div class="wa-overly"></div>
<!-- offcanvas-end -->