@php
    $contactSection = $sections['contact'] ?? [];
@endphp

@if($contactSection['is_active'] ?? true)
<!-- contact-start -->
<section class="bs-contact-1-area pt-130 pb-100 wa-p-relative">

    <div class="bs-contact-1-bg-color"></div>

    <div class="container bs-container-1">

        <!-- section-title -->
        <div class="bs-contact-1-sec-title text-center mb-45">
            <h6 class="bs-subtitle-1 wa-split-clr wa-capitalize ">
                <span class="icon">
                    <img src="{{ asset('assets/img/illus/star-shape.png') }}" alt="">
                </span>
                {{ $contactSection['subtitle'] ?? 'contact us' }}
            </h6>
            <h2 class="bs-sec-title-1 wa-split-right wa-capitalize" data-cursor="-opaque">{{ $contactSection['title'] ?? 'Get in Touch' }}</h2>
        </div>

        <div class="bs-contact-1-wrap">

            <!-- left-content -->
            <div class="bs-contact-1-left">

                <!-- img -->
                <div class="bs-contact-1-img wa-fix wa-img-cover" data-cursor="-opaque">
                    <img class="wa-parallax-img" src="{{ asset($contactSection['left_image'] ?? 'assets/img/random/random (23).png') }}" alt="">
                </div>

                <div class="bs-contact-1-video wa-clip-top-bottom">
                    <div class="bg-img wa-fix wa-img-cover">
                        <img src="{{ asset($contactSection['video_background'] ?? 'assets/img/random/random (2).png') }}" alt="">
                    </div>
                    <a href="{{ $contactSection['video_url'] ?? 'https://www.youtube.com/watch?v=e45TPIcx5CY' }}" aria-label="name" class="bs-play-btn-2 wa-magnetic-btn bs-p-1 popup-video">
                        <span class="icon">
                            <i class="fa-solid fa-play"></i>
                        </span>
                        <span class="text">{{ $contactSection['video_text'] ?? '10 years experience' }}</span>
                    </a>
                </div>
            </div>

            <!-- right-content -->
            <div class="bs-contact-1-right">

                <!-- form -->
                <form action="{{ route('contact.store') }}" method="POST" class="bs-form-1 mb-100">
                    @csrf
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="bs-form-1-item">
                        <label class="bs-form-1-item-label" for="name"><img src="{{ asset('assets/img/contact/c1-star.png') }}" alt="">name*</label>
                        <input id="name" name="name" class="bs-form-1-item-input wa-clip-left-right" type="text" aria-label="name" value="{{ old('name') }}">
                        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="bs-form-1-item">
                        <label class="bs-form-1-item-label" for="email"><img src="{{ asset('assets/img/contact/c1-star.png') }}" alt="">email*</label>
                        <input id="email" name="email" class="bs-form-1-item-input wa-clip-left-right" type="email" aria-label="name" value="{{ old('email') }}">
                        @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="bs-form-1-item">
                        <label class="bs-form-1-item-label" for="phone"><img src="{{ asset('assets/img/contact/c1-star.png') }}" alt="">phone*</label>
                        <input id="phone" name="phone" class="bs-form-1-item-input wa-clip-left-right" type="text" aria-label="phone" value="{{ old('phone') }}">
                        @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="bs-form-1-item has-full-width">
                        <label class="bs-form-1-item-label" for="user-type">Are you Individual, Broker, or Investor?</label>
                        <select id="user-type" name="user_type" class="bs-form-1-item-input wa-clip-left-right" aria-label="name">
                            <option value="">Select an option</option>
                            <option value="individual" {{ old('user_type') == 'individual' ? 'selected' : '' }}>Individual</option>
                            <option value="broker" {{ old('user_type') == 'broker' ? 'selected' : '' }}>Broker</option>
                            <option value="investor" {{ old('user_type') == 'investor' ? 'selected' : '' }}>Investor</option>
                        </select>
                        @error('user_type') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="bs-form-1-item has-full-width">
                        <label class="bs-form-1-item-label" for="message"><img src="{{ asset('assets/img/contact/c1-star.png') }}" alt="">message*</label>
                        <textarea class="bs-form-1-item-input wa-clip-left-right" name="message" id="message">{{ old('message') }}</textarea>
                        @error('message') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <input type="hidden" name="type" value="contact">
                    <input type="hidden" name="subject" value="General Inquiry">

                    <div class="bs-form-1-item has-no-after has-full-width wa-clip-left-right">
                        <div class="bs-form-1-item-checkbox">
                            <input type="checkbox" id="check-1" aria-label="name">
                            <label for="check-1" class="bs-p-1">I agree to the Terms & Conditions and Privacy Policy of Geometric Development real estate services.</label>
                        </div>
                    </div>

                    <div class="bs-form-1-item has-no-after has-full-width wa-clip-left-right">
                        <button class="bs-btn-1" type="submit" aria-label="name">
                            <span class="text">
                                contact us
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
@endif
<!-- contact-end -->
