{{-- Services Section --}}
@if(($homePage->sections['services']['is_active'] ?? true))
<section class="bs-product-5-area pt-85 pb-85 wa-bg-default wa-p-relative wa-fix">
    <div class="container bs-container-1">

        <!-- section-title -->
        <div class="bs-projects-1-sec-title mb-40">
            <h6 class="bs-subtitle-1 wa-split-clr wa-capitalize">
                <span class="icon">
                    <img src="{{ asset('assets/img/illus/star-shape.png') }}" alt="">
                </span>
                our services
            </h6>
            <h2 class="bs-sec-title-1 wa-split-right wa-capitalize" data-cursor="-opaque">Geometric Development <br> Premium Real Estate Services</h2>
        </div>

        <div class="bs-projects-1-wrap">

            <!-- tabs-btn -->
            <div class="bs-projects-1-tabs-btn" role="tablist">

                <!-- single-btn -->
                <button class="nav-link  wa-fadeInUp" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                    Muroj Villa

                    <span class="year">2025</span>
                    <span class="img">
                        <img src="{{ asset('assets/img/projects/p1-btn-img-1.png') }}" alt="">
                    </span>
                    <span class="right-arrow">
                        <img src="{{ asset('assets/img/illus/long-right-arrow.png') }}" alt="">
                    </span>
                </button>

                <!-- single-btn -->
                <button class="nav-link wa-fadeInUp active" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">
                    Mina Marina
                    <span class="year">2025</span>
                    <span class="img">
                        <img src="{{ asset('assets/img/projects/p1-btn-img-1.png') }}" alt="">
                    </span>
                    <span class="right-arrow">
                        <img src="{{ asset('assets/img/illus/long-right-arrow.png') }}" alt="">
                    </span>
                </button>

                <!-- single-btn -->
                <button class="nav-link wa-fadeInUp" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">
                    Rich Hills
                    <span class="year">2025</span>
                    <span class="img">
                        <img src="{{ asset('assets/img/projects/p1-btn-img-1.png') }}" alt="">
                    </span>
                    <span class="right-arrow">
                        <img src="{{ asset('assets/img/illus/long-right-arrow.png') }}" alt="">
                    </span>
                </button>

                <!-- single-btn -->
                <button class="nav-link wa-fadeInUp" id="nav-contact-tab2" data-bs-toggle="tab" data-bs-target="#nav-contact2" type="button" role="tab" aria-controls="nav-contact2" aria-selected="false">
                    Ras Al Khaimah
                    <span class="year">2025</span>
                    <span class="img">
                        <img src="{{ asset('assets/img/projects/p1-btn-img-1.png') }}" alt="">
                    </span>
                    <span class="right-arrow">
                        <img src="{{ asset('assets/img/illus/long-right-arrow.png') }}" alt="">
                    </span>
                </button>

            </div>


            <!-- tabs-content -->
            <div class="bs-projects-1-tabs-pane tab-content">

                <!-- single-pane -->
                <div class="tab-pane fade " id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="bs-projects-1-tabs-item">
                        <div class="main-img wa-img-cover wa-fix">
                            <a href="{{ route('projects.index') }}"><img data-cursor="-opaque" src="{{ asset('assets/img/projects/p1-img-1.png') }}" alt=""></a>
                        </div>
                        <div class="bs-projects-1-tabs-item-table">
                            <div class="start">
                                <h6 class="bs-h-1 title">start & completed</h6>
                                <div class="wrap">
                                    <p class="bs-p-1 disc">jan 02, 2025</p>
                                    <p class="bs-p-1 disc">aug 02, 2025</p>
                                </div>
                            </div>
                            <div class="location">
                                <h6 class="bs-h-1 title">Location</h6>
                                <p class="bs-p-1 disc">136 North Coast, Egypt</p>
                            </div>
                            <div class="share">
                                <h6 class="bs-h-1 title-2">share project</h6>
                                <div class="bs-social-link-1">
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-facebook-f"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-x-twitter"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-linkedin-in"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-pinterest-p"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- single-pane -->
                <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <div class="bs-projects-1-tabs-item">
                        <div class="main-img wa-img-cover wa-fix">
                            <a href="{{ route('projects.index') }}"><img data-cursor="-opaque" src="{{ asset('assets/img/projects/p1-img-2.png') }}" alt=""></a>
                        </div>
                        <div class="bs-projects-1-tabs-item-table">
                            <div class="start">
                                <h6 class="bs-h-1 title">start & completed</h6>
                                <div class="wrap">
                                    <p class="bs-p-1 disc">jan 02, 2025</p>
                                    <p class="bs-p-1 disc">aug 02, 2025</p>
                                </div>
                            </div>
                            <div class="location">
                                <h6 class="bs-h-1 title">Location</h6>
                                <p class="bs-p-1 disc">New Cairo, Egypt</p>
                            </div>
                            <div class="share">
                                <h6 class="bs-h-1 title-2">share project</h6>
                                <div class="bs-social-link-1">
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-facebook-f"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-x-twitter"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-linkedin-in"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-pinterest-p"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- single-pane -->
                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                    <div class="bs-projects-1-tabs-item">
                        <div class="main-img wa-img-cover wa-fix">
                            <a href="{{ route('projects.index') }}"><img data-cursor="-opaque" src="{{ asset('assets/img/projects/p1-img-3.png') }}" alt=""></a>
                        </div>
                        <div class="bs-projects-1-tabs-item-table">
                            <div class="start">
                                <h6 class="bs-h-1 title">start & completed</h6>
                                <div class="wrap">
                                    <p class="bs-p-1 disc">jan 02, 2025</p>
                                    <p class="bs-p-1 disc">aug 02, 2025</p>
                                </div>
                            </div>
                            <div class="location">
                                <h6 class="bs-h-1 title">Location</h6>
                                <p class="bs-p-1 disc">North Coast, Egypt</p>
                            </div>
                            <div class="share">
                                <h6 class="bs-h-1 title-2">share project</h6>
                                <div class="bs-social-link-1">
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-facebook-f"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-x-twitter"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-linkedin-in"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-pinterest-p"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- single-pane -->
                <div class="tab-pane fade" id="nav-contact2" role="tabpanel" aria-labelledby="nav-contact-tab2">
                    <div class="bs-projects-1-tabs-item">
                        <div class="main-img wa-img-cover wa-fix">
                            <a href="{{ route('projects.index') }}"><img data-cursor="-opaque" src="{{ asset('assets/img/projects/p1-img-4.png') }}" alt=""></a>
                        </div>
                        <div class="bs-projects-1-tabs-item-table">
                            <div class="start">
                                <h6 class="bs-h-1 title">start & completed</h6>
                                <div class="wrap">
                                    <p class="bs-p-1 disc">jan 02, 2025</p>
                                    <p class="bs-p-1 disc">aug 02, 2025</p>
                                </div>
                            </div>
                            <div class="location">
                                <h6 class="bs-h-1 title">Location</h6>
                                <p class="bs-p-1 disc">Hurghada, Egypt</p>
                            </div>
                            <div class="share">
                                <h6 class="bs-h-1 title-2">share project</h6>
                                <div class="bs-social-link-1">
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-facebook-f"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-x-twitter"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-linkedin-in"></i>
                                    </a>
                                    <a href="#" aria-label="name" class="item-link">
                                        <i class="fa-brands fa-pinterest-p"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- bg-img -->
    <div class="bs-projects-1-bg-img">
        <img src="{{ asset('assets/img/projects/p1-bg-img-1.png') }}" alt="">
    </div>
</section>
@endif
