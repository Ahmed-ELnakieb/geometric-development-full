{{-- Contact Form Section --}}
@php
    $formSection = $contactPage->sections['contact_form'] ?? [];
    $isActive = $formSection['is_active'] ?? true;
    $subtitle = $formSection['subtitle'] ?? 'Contact us';
    $title = $formSection['title'] ?? 'Find Your Perfect Property Today!';
    $sideImage = !empty($formSection['side_image'])
        ? (str_starts_with($formSection['side_image'], 'http')
            ? $formSection['side_image']
            : (str_starts_with($formSection['side_image'], 'assets/')
                ? asset($formSection['side_image'])
                : asset('storage/' . $formSection['side_image'])))
        : null;
    $formAction = $formSection['form_action'] ?? route('contact.store');
    $showUserType = $formSection['show_user_type'] ?? true;
@endphp

@if($isActive)
<!-- contact-form-start -->
<section class="bs-contact-6-area pt-130 pb-160">
    <div class="container bs-container-1">
        <div class="bs-contact-6-wrap">

            @if($sideImage)
            <div class="bs-contact-6-img wa-fix wa-img-cover">
                <img src="{{ $sideImage }}" alt="">
            </div>
            @endif

            <div class="bs-contact-6-content">

                <!-- section-title -->
                <div class="bs-about-1-sec-title mb-30">
                    <h6 class="bs-subtitle-1 wa-split-clr wa-capitalize">
                        <span class="icon">
                            <i class="fas fa-star" style="font-size: 20px;"></i>
                        </span>
                        {{ $subtitle }}
                    </h6>
                    <h2 class="bs-sec-title-1 wa-split-right wa-capitalize" data-cursor="-opaque">{{ $title }}</h2>
                </div>

                <form id="contact-form" action="{{ $formAction }}" method="POST" class="bs-form-1 bs-career-single-form">
                    @csrf
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="form-messages"></div>

                    <div class="bs-form-1-item">
                        <label class="bs-form-1-item-label" for="name">Full Name</label>
                        <input id="name" class="bs-form-1-item-input" type="text" name="name" value="{{ old('name') }}" placeholder="Ahmed Elnakieb" required>
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="bs-form-1-item">
                        <label class="bs-form-1-item-label" for="phone">Phone Number</label>
                        <input id="phone" class="bs-form-1-item-input" type="tel" name="phone" value="{{ old('phone') }}" placeholder="+20 10 XXXXXXXX" required>
                        @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="bs-form-1-item">
                        <label class="bs-form-1-item-label" for="email">Email Address</label>
                        <input id="email" class="bs-form-1-item-input" type="email" name="email" value="{{ old('email') }}" placeholder="info@gmail.com" required>
                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="bs-form-1-item">
                        <label class="bs-form-1-item-label" for="subject">Subject</label>
                        <input id="subject" class="bs-form-1-item-input" type="text" name="subject" value="{{ old('subject') }}" placeholder="Subject" required>
                        @error('subject') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    @if($showUserType)
                    <div class="bs-form-1-item">
                        <label class="bs-form-1-item-label" for="user-type">Are you a Customer or a Broker?</label>
                        <select id="user-type" class="bs-form-1-item-input" name="user_type" required>
                            <option value="">Select an option</option>
                            <option value="individual" {{ old('user_type') == 'individual' ? 'selected' : '' }}>Individual</option>
                            <option value="broker" {{ old('user_type') == 'broker' ? 'selected' : '' }}>Broker</option>
                            <option value="investor" {{ old('user_type') == 'investor' ? 'selected' : '' }}>Investor</option>
                        </select>
                        @error('user_type') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <div class="bs-form-1-item">
                        <label class="bs-form-1-item-label" for="message">Your Message</label>
                        <textarea class="bs-form-1-item-input" name="message" id="message" placeholder="Tell us about your property needs or inquiries..." required>{{ old('message') }}</textarea>
                        @error('message') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="bs-form-1-item">
                        <button class="bs-btn-1" type="submit">
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
@endif
