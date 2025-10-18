@extends('layouts.app')

@section('title', 'FAQs - Geometric Development')

@section('body-class', '')

@section('content')
<!-- Breadcrumb Section -->
<section class="breadcrumb-area breadcrumb-bg-1" data-background="{{ asset('assets/img/breadcrumb/breadcrumb-img.png') }}">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content">
                    <h2 class="title">FAQs</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">FAQs</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQs Section -->
<section class="faq-area pt-120 pb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="faq-wrap">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="faq-img">
                                <img src="{{ asset('assets/img/faq/f6-img-1.png') }}" alt="FAQ Image">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="faq-content">
                                <div class="section-title mb-50">
                                    <span class="sub-title">
                                        <img src="{{ asset('assets/img/icons/star-shape.png') }}" alt="Icon">
                                        Frequently Asked Questions
                                    </span>
                                    <h2 class="title">Get Answers to Your Questions</h2>
                                </div>
                                <div class="accordion" id="accordionExample_31">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button active" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                How do I know if I need an architect for my project?
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample_31">
                                            <div class="accordion-body">
                                                If you're planning a construction or renovation project, an architect can help ensure your vision is realized safely and efficiently. Consider hiring one if your project involves complex designs, structural changes, or if you need professional guidance on building codes and permits.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTwo">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                What are the typical fees for architectural services?
                                            </button>
                                        </h2>
                                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample_31">
                                            <div class="accordion-body">
                                                Architectural fees vary based on project size and complexity. Typically, they range from 5-15% of the total project cost, including design, documentation, and site supervision. We offer competitive rates tailored to your needs.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingThree">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                How involved will I be in the design process?
                                            </button>
                                        </h2>
                                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample_31">
                                            <div class="accordion-body">
                                                You'll be involved throughout the process. We collaborate closely, incorporating your feedback at every stage from initial concepts to final plans. Regular meetings ensure your vision is maintained.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingFour">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                                How long does an architectural design project typically take?
                                            </button>
                                        </h2>
                                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample_31">
                                            <div class="accordion-body">
                                                Project timelines vary, but a typical residential project takes 4-8 weeks for design and documentation. Larger commercial projects may take longer. We provide detailed timelines upfront.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingFive">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                                Can an architect help with interior design and landscaping?
                                            </button>
                                        </h2>
                                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample_31">
                                            <div class="accordion-body">
                                                Yes, many architects offer comprehensive services including interior design and landscaping coordination. We can integrate these elements seamlessly into your overall project design.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingSix">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                                How do architects handle building permits and approvals?
                                            </button>
                                        </h2>
                                        <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample_31">
                                            <div class="accordion-body">
                                                We handle all permit applications and coordinate with local authorities. Our team ensures compliance with building codes and regulations, streamlining the approval process for you.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingSeven">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                                What should I bring to my first meeting with an architect?
                                            </button>
                                        </h2>
                                        <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#accordionExample_31">
                                            <div class="accordion-body">
                                                Bring sketches, photos of inspiration, site measurements, and your project budget. Also include any specific requirements or constraints. This helps us understand your vision better.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingEight">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                                What is the difference between an architect and a designer?
                                            </button>
                                        </h2>
                                        <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#accordionExample_31">
                                            <div class="accordion-body">
                                                Architects are licensed professionals who design buildings and handle structural, safety, and code compliance. Designers focus on aesthetics and may not have the same legal responsibilities or technical expertise.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingNine">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                                                What are the benefits of hiring an architect for sustainable design?
                                            </button>
                                        </h2>
                                        <div id="collapseNine" class="accordion-collapse collapse" aria-labelledby="headingNine" data-bs-parent="#accordionExample_31">
                                            <div class="accordion-body">
                                                Architects can incorporate energy-efficient materials, passive solar design, and sustainable practices that reduce environmental impact and long-term operating costs while maintaining aesthetic appeal.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form Section -->
<section class="contact-area pt-120 pb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="contact-wrap">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="contact-img">
                                <img src="{{ asset('assets/img/contact/c1-img-1.png') }}" alt="Contact Image">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="contact-form">
                                <div class="section-title mb-50">
                                    <span class="sub-title">
                                        <img src="{{ asset('assets/img/icons/star-shape.png') }}" alt="Icon">
                                        Get In Touch
                                    </span>
                                    <h2 class="title">Have Questions? Contact Us</h2>
                                </div>
                                @if(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <form action="{{ route('contact.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="phone" value="N/A">
                                    <input type="hidden" name="user_type" value="individual">
                                    <input type="hidden" name="subject" value="FAQ Inquiry">
                                    <div class="form-group">
                                        <input type="text" name="name" placeholder="Your Name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input type="email" name="email" placeholder="Your Email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <textarea name="message" placeholder="Your Message" required>{{ old('message') }}</textarea>
                                        @error('message')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary">Send Message</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Counter Section -->
<section class="counter-area pt-120 pb-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="counter-item">
                    <h2 class="counter">28</h2>
                    <p>Years Experience</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="counter-item">
                    <h2 class="counter">1.5k</h2>
                    <p>Projects Completed</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
