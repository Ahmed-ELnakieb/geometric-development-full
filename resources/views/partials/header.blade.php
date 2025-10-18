<header class="bs-header-5-area ">
    <div class="container bs-container-2">
        <div class="bs-header-5-row d-flex align-items-center justify-content-between">

            <!-- logo -->
            <a href="{{ route('home') }}" aria-label="name" class="bs-header-5-logo">
                <img src="{{ asset('assets/img/logo/logo_dark.png') }}" alt="">
            </a>

            <nav class="bs-main-navigation  d-none d-lg-block">
                <ul id="main-nav" class="nav navbar-nav ">
                    <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Home</a></li>
                    <li class="{{ (request()->routeIs('page.show') && request()->route()->parameter('slug') === 'about') ? 'active' : '' }}"><a href="{{ route('page.show', 'about') }}">About</a></li>
                    <li class="{{ request()->routeIs('projects.*') ? 'active' : '' }}"><a href="{{ route('projects.index') }}">Projects</a></li>
                    <li class="{{ request()->routeIs('careers.*') ? 'active' : '' }}"><a href="{{ route('careers.index') }}">Vacancies</a></li>
                    <li class="{{ request()->routeIs('blog.*') ? 'active' : '' }}"><a href="{{ route('blog.index') }}">Blogs</a></li>
                </ul>
            </nav>

            <!-- action-link -->
            <div class="bs-header-5-action-link d-flex align-items-center ">
                
                <!-- pr-btn -->
                <a href="{{ route('contact.index') }}" aria-label="name" class="bs-pr-btn-3">
                    <span class="text">request a quote</span>
                    <span class="text">Contact us</span>
                </a>

                <!-- offcanvas-btn -->
                <button type="button" aria-label="name" class="bs-offcanvas-btn-1  offcanvas_toggle">
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                </button>

            </div>
        </div>
    </div>
</header>
