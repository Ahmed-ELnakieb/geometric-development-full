<footer class="bs-footer-4-area wa-p-relative wa-fix pt-130">
    <div class="bs-footer-4-bg wa-fix">
        <img src="{{ asset('assets/img/footer/f4-bg-1.png') }}" alt="">
    </div>

    <div class="container bs-container-2">
        <div class="bs-footer-4-wrap mb-125">
            <!-- left-side -->
            <div class="bs-footer-4-left">
                <a href="{{ route('home') }}" aria-label="name" class="bs-footer-4-logo">
                    <img src="{{ asset('assets/img/logo/logo_light.png') }}" alt="" style="width: 39%;">
                </a>

                <div class="bs-footer-4-widget">
                    <!-- single-widget -->
                    <div class="bs-footer-4-widget-single">
                        <ul class="bs-footer-4-menu wa-list-style-none">
                            <li>
                                <a href="{{ route('home') }}" aria-label="name">home</a>
                            </li>
                            <li>
                                <a href="{{ route('page.show', 'about') }}" aria-label="name">about us</a>
                            </li>
                            <li>
                                <a href="{{ route('careers.index') }}" aria-label="name">vacancies</a>
                            </li>
                            <li>
                                <a href="{{ route('projects.index') }}" aria-label="name">projects</a>
                            </li>
                            <li>
                                <a href="{{ route('blog.index') }}" aria-label="name">blog</a>
                            </li>
                            <li>
                                <a href="{{ route('contact.index') }}" aria-label="name">contact us</a>
                            </li>
                        </ul>
                    </div>

                    <!-- single-widget -->
                    <div class="bs-footer-4-widget-single">
                        <ul class="bs-footer-4-menu wa-list-style-none">
                            @php
                                $featuredProjects = \App\Models\Project::featured()->published()->take(4)->get();
                            @endphp
                            @foreach($featuredProjects as $project)
                            <li>
                                <a href="{{ route('projects.show', $project->slug) }}" aria-label="name">{{ $project->title }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- right -->
            <div class="bs-footer-4-contact">
                <h4 class="bs-h-1 title wa-split-up wa-capitalize wa-fix">contact us</h4>
                <div class="bs-footer-4-contact-link">
                    <p class="bs-p-4 link-title">Email</p>
                    <a href="mailto:{{ \App\Models\Setting::get('email', 'info@geometric-development.com') }}" target="_blank" class="link-elm bs-p-4 wa-clip-left-right">{{ \App\Models\Setting::get('email', 'info@geometric-development.com') }}</a>
                    <p class="bs-p-4 link-title">phone</p>
                    <a href="https://wa.me/201272777919" target="_blank" class="link-elm bs-p-4 wa-clip-left-right" style="display: block;">+20 127 2777919</a>
                    <a href="https://wa.me/201200111338" target="_blank" class="link-elm bs-p-4 wa-clip-left-right" style="display: block;">+20 120 0111338</a>
                    <p class="bs-p-4 link-title">Address</p>
                    <a href="https://maps.google.com/?q=6+October+Sheikh+Zayed+Egypt" target="_blank" class="link-elm bs-p-4 wa-clip-left-right" style="display: block;">6 October - Sheikh Zayed</a>
                </div>
            </div>
        </div>
    </div>

    <div class="bs-footer-4-copyright">
        <div class="container bs-container-2">
            <div class="bs-footer-4-copyright-flex">
                <p class="bs-p-4 bs-footer-4-copyright-text">
                    &copy;{{ date('Y') }}, <a href="#">Geometric-Development</a> All Rights Reserved.
                </p>

                <div class="bs-footer-4-copyright-social">
                    <a href="{{ \App\Models\Setting::get('facebook_url', '#') }}" aria-label="name" class="elm-link bs-p-4">
                        <i class="fa-brands fa-facebook-f"></i>
                        facebook
                    </a>
                    <a href="{{ \App\Models\Setting::get('instagram_url', '#') }}" aria-label="name" class="elm-link bs-p-4">
                        <i class="fa-brands fa-instagram"></i>
                        instagram
                    </a>
                    <a href="{{ \App\Models\Setting::get('twitter_url', '#') }}" aria-label="name" class="elm-link bs-p-4">
                        <i class="fa-brands fa-x-twitter"></i>
                        twitter
                    </a>
                    <a href="{{ \App\Models\Setting::get('linkedin_url', '#') }}" aria-label="name" class="elm-link bs-p-4">
                        <i class="fa-brands fa-linkedin-in"></i>
                        linkedin
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
