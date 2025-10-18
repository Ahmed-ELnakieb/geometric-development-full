@extends('layouts.app')

@section('title', 'Contact Us - Geometric Development')

@section('body-class', '')

@section('content')

<!-- breadcrumb-start -->
<section class="breadcrumb-area wa-p-relative" >
    <div class="breadcrumb-bg-img wa-fix wa-img-cover">
        <!-- Removed missing breadcrumb background image -->
    </div>

    <div class="container bs-container-1">
        <div class="breadcrumb-wrap">
            <h1 class="breadcrumb-title wa-split-right wa-capitalize" data-split-delay="1.1s" >Contact Us</h1>

            <div class="breadcrumb-list " >
                <svg class="breadcrumb-list-shape" width="88" height="91" viewBox="0 0 88 91" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M75.3557 83.4825C51.6516 78.2316 30.2731 65.4227 30.8424 38.6307C29.0856 37.5878 27.3642 36.4078 25.6807 35.1082C15.8629 27.5282 7.34269 15.8295 0.970618 3.77828L0 1.94173L3.67259 0L4.64321 1.83605C10.7341 13.3558 18.8345 24.574 28.2197 31.82C29.1853 32.5658 30.1649 33.2687 31.1564 33.9242C31.7447 28.7351 34.2557 18.3221 41.4244 12.7755C53.1965 3.6676 66.5598 9.52271 70.2762 19.1546C74.5799 30.309 65.1659 39.6328 59.589 41.7844C51.0354 45.0846 42.7385 44.3218 35.01 40.8138C35.681 63.7945 54.9747 74.6677 76.0057 79.3717L77.1209 72.3207L87.9707 83.4999L74.2006 90.7853L75.3557 83.4825ZM35.1147 36.2473C42.2964 39.9314 50.0548 41.0102 58.0934 37.9089C62.3617 36.2618 69.6945 29.1868 66.4003 20.6502C63.5203 13.1858 53.0893 9.00325 43.9669 16.0613C37.698 20.9114 35.7338 30.1584 35.2637 34.5703C35.2034 35.1366 35.1536 35.696 35.1147 36.2473Z" fill="white"/>
                </svg>

                <a href="{{ route('home') }}">Home</a>
                <span>Contact Us</span>
            </div>

        </div>
    </div>
</section>
<!-- breadcrumb-end -->

<!-- core-feature-start -->
<section class="bs-core-feature-6-area  pt-125 ">
    <div class="container bs-container-1">
        <div class="bs-core-feature-4-line wa-scaleXInUp"></div>
        <div class="bs-core-feature-6-wrap">

            <!-- single-item -->
            <div class="bs-core-feature-4-item wow fadeInRight" >
                <h4 class="bs-h-4 item-title">
                    <a href="#" aria-label="name">Address</a>
                </h4>
                <div class="item-icon">
                    <i class="fas fa-map-marker-alt" data-cursor="-opaque" style="font-size: 61px; color: inherit;"></i>
                </div>
                <a href="https://maps.app.goo.gl/Fi718eLMwvU4RF8s6" target="_blank" class="bs-p-4 item-disc" style="display: block;">6 October - Sheikh Zayed, Egypt</a>
            </div>

            <!-- single-item -->
            <div class="bs-core-feature-4-item wow fadeInRight" >
                <h4 class="bs-h-4 item-title">
                    <a href="#" aria-label="name">Emails Address</a>
                </h4>
                <div class="item-icon">
                    <i class="fas fa-envelope" data-cursor="-opaque" style="font-size: 61px; color: inherit;"></i>
                </div>
                <a href="mailto:info@geometric-development.com" class="bs-p-4 item-disc">info@geometric-development.com</a>
            </div>

            <!-- single-item -->
            <div class="bs-core-feature-4-item wow fadeInRight" >
                <h4 class="bs-h-4 item-title">
                    <a href="#" aria-label="name">Call Us</a>
                </h4>
                <div class="item-icon">
                    <i class="fas fa-phone" data-cursor="-opaque" style="font-size: 61px; color: inherit;"></i>
                </div>
                <a href="https://wa.me/201272777919" target="_blank" class="bs-p-4 item-disc">+20 127 2777919</a>
                <a href="https://wa.me/201200111338" target="_blank" class="bs-p-4 item-disc">+20 120 0111338</a>
            </div>

        </div>
        <div class="bs-core-feature-4-line wa-scaleXInUp"></div>
    </div>
</section>
<!-- core-feature-end -->

<!-- contact-form-start -->
<section class="bs-contact-6-area pt-130 pb-160">
    <div class="container bs-container-1">
        <div class="bs-contact-6-wrap">

            <div class="bs-contact-6-img wa-fix wa-img-cover">
                <!-- Removed missing random image -->
            </div>

            <div class="bs-contact-6-content">

                <!-- section-title -->
                <div class="bs-about-1-sec-title mb-30">
                    <h6 class="bs-subtitle-1 wa-split-clr wa-capitalize">
                        <span class="icon">
                            <i class="fas fa-star" style="font-size: 20px;"></i>
                        </span>
                        Contact us
                    </h6>
                    <h2 class="bs-sec-title-1  wa-split-right wa-capitalize" data-cursor="-opaque">Find Your Perfect Property Today!</h2>
                </div>


                <form id="contact-form" action="{{ route('contact.store') }}" method="POST" class="bs-form-1 bs-career-single-form">
                    @csrf
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="form-messages"></div>

                    <div class="bs-form-1-item">
                        <label class="bs-form-1-item-label" for="name">Full Name</label>
                        <input id="name" class="bs-form-1-item-input "  type="text" name="name" value="{{ old('name') }}" placeholder="Ahmed Elnakieb">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="bs-form-1-item">
                        <label class="bs-form-1-item-label" for="phone">Phone Number</label>
                        <input id="phone" class="bs-form-1-item-input " type="tel" name="phone" value="{{ old('phone') }}" placeholder="+20 10 XXXXXXXX">
                        @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="bs-form-1-item">
                        <label class="bs-form-1-item-label" for="email">Email Address</label>
                        <input id="email" class="bs-form-1-item-input " type="email" name="email" value="{{ old('email') }}" placeholder="info@gmail.com">
                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="bs-form-1-item">
                        <label class="bs-form-1-item-label" for="subject">Subject</label>
                        <input id="subject" class="bs-form-1-item-input" type="text" name="subject" value="{{ old('subject') }}" placeholder="Subject">
                        @error('subject') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="bs-form-1-item">
                        <label class="bs-form-1-item-label" for="user-type">Are you a Customer or a Broker?</label>
                        <select id="user-type" class="bs-form-1-item-input" name="user_type">
                            <option value="">Select an option</option>
                            <option value="individual" {{ old('user_type') == 'individual' ? 'selected' : '' }}>Individual</option>
                            <option value="broker" {{ old('user_type') == 'broker' ? 'selected' : '' }}>Broker</option>
                            <option value="investor" {{ old('user_type') == 'investor' ? 'selected' : '' }}>Investor</option>
                        </select>
                        @error('user_type') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="bs-form-1-item">
                        <label class="bs-form-1-item-label" for="message">Your Message</label>
                        <textarea class="bs-form-1-item-input" name="message" id="message" placeholder="Tell us about your property needs or inquiries...">{{ old('message') }}</textarea>
                        @error('message') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="bs-form-1-item  ">
                        <button class="bs-btn-1" type="submit" >
                            <span class="text">
                                 Send Message
                            </span>
                            <span class="icon">
                                <i class="fa-solid fa-right-long"></i>
                                <i class="fa-solid fa-right-long"></i>
                            </span>
                            <span class="shape"></span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>
<!-- contact-form-end -->

<!-- map-start -->
<div class="bs-contact-page-map">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3453.0757788567597!2d30.9502945!3d30.0774592!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1458596ebd719e87%3A0xdff54007e2e42e6a!2sGeometric%20Development!5e0!3m2!1sen!2seg!4v1738045355332!5m2!1sen!2seg" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
<!-- map-end -->

@endsection
