<header class="bs-header-5-area ">
    <div class="container bs-container-2">
        <div class="bs-header-5-row d-flex align-items-center justify-content-between">

            <!-- logo -->
            <a href="{{ route('home') }}" aria-label="name" class="bs-header-5-logo">
                <img src="{{ asset(settings('logo_dark', 'assets/img/logo/logo_dark.png')) }}" alt="{{ settings('site_name', 'Geometric Development') }}">
            </a>

            <nav class="bs-main-navigation  d-none d-lg-block">
                <ul id="main-nav" class="nav navbar-nav ">
                    @foreach(getNavbarMenu() as $menuItem)
                        <li class="{{ request()->fullUrlIs(getMenuUrl($menuItem)) || (request()->routeIs($menuItem->route) ?? false) ? 'active' : '' }}">
                            <a href="{{ getMenuUrl($menuItem) }}" 
                               @if($menuItem->open_in_new_tab) target="_blank" @endif>
                                @if($menuItem->icon)
                                    <i class="{{ $menuItem->icon }}"></i>
                                @endif
                                {{ $menuItem->title }}
                            </a>
                            
                            @if($menuItem->children->count() > 0)
                                <ul class="dropdown">
                                    @foreach($menuItem->children as $child)
                                        <li>
                                            <a href="{{ getMenuUrl($child) }}"
                                               @if($child->open_in_new_tab) target="_blank" @endif>
                                                {{ $child->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </nav>

            <!-- action-link -->
            <div class="bs-header-5-action-link d-flex align-items-center ">
                
                <!-- pr-btn -->
                <a href="{{ route(settings('navbar_button_link', 'contact.index')) }}" aria-label="name" class="bs-pr-btn-3">
                    <span class="text">{{ settings('navbar_button_text_1', 'request a quote') }}</span>
                    <span class="text">{{ settings('navbar_button_text_2', 'Contact us') }}</span>
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
