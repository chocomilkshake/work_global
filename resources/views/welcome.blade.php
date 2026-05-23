@extends('layouts.app')

@section('title', 'Home')

@section('content')

@section('body-class', 'home-page')

{{-- ================= HERO SECTION ================= --}}
<section id="hero" class="d-flex align-items-center py-5">

    <div class="container">

        <div class="row align-items-center">

            <!-- LEFT TEXT -->
            <div class="col-lg-6">

                <h1 class="fw-bold">
                    Join Work Global today and unlock a world of opportunities!
                </h1>

                <p class="mt-3 text-muted">
                    Whether you're a job seeker looking to take your career to
                    new heights or an employer seeking exceptional talent,
                    we are here to make your recruitment journey a success.
                </p>

                <div class="d-flex gap-3 mt-4">

                    <a href="{{ url('login') }}" class="btn btn-primary px-4">
                        Get Started
                    </a>

                    <a href="#" class="btn btn-primary px-4 d-flex align-items-center gap-2">
                        <i class="fa fa-play"></i>
                        Watch Video
                    </a>

                </div>

            </div>

            <!-- RIGHT IMAGE -->
            <div class="col-lg-6 text-center">

                <img src="{{ asset('assets/img/hero-img.png') }}"
                    class="img-fluid"
                    alt="Hero Image">

            </div>

        </div>

    </div>

</section>


{{-- ================= ABOUT SECTION ================= --}}
<section id="about" class="about py-5 bg-white">

    <div class="container">

        <div class="text-center mb-5">
            <h2 class="fw-bold">About Us</h2>
        </div>

        <div class="row g-4">

            <!-- LEFT -->
            <div class="col-lg-6">

                <p class="lh-lg">
                    At Work Global, we are passionate about connecting talented individuals with their dream jobs across the globe.
                </p>

                <ul class="list-unstyled mt-3">

                    <li class="mb-2">
                        <i class="ri-check-double-line text-primary"></i>
                        Extensive Global Network: access to worldwide opportunities.
                    </li>

                    <li>
                        <i class="ri-check-double-line text-primary"></i>
                        Dedicated Support: personalized assistance throughout recruitment.
                    </li>

                </ul>

            </div>

            <!-- RIGHT -->
            <div class="col-lg-6">

                <p class="lh-lg">
                    For employers, we provide access to a global talent pool and tools for efficient recruitment.
                </p>

                <div class="d-flex gap-3 mt-3">

                    <a href="{{ url('register') }}"
                        class="btn btn-primary">
                        Sign-up as candidate
                    </a>

                    <a href="{{ url('employer_registration') }}"
                        class="btn btn-outline-primary">
                        Sign-up as employer
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>


{{-- ================= WHY US SECTION ================= --}}
<section id="why-us" class="why-us py-5 bg-light">

    <div class="container">

        <div class="row align-items-center g-5">

            <!-- LEFT CONTENT -->
            <div class="col-lg-7">

                <h3 class="fw-bold mb-4">
                    Why Us? <span class="text-primary">Work Global</span>
                </h3>

                <div class="p-4 bg-white shadow-sm rounded">

                    <p class="mb-0">
                        Work Global has built an extensive network spanning across countries and industries,
                        ensuring both job seekers and employers have access to global opportunities.
                    </p>

                </div>

            </div>

            <!-- RIGHT IMAGE -->
            <div class="col-lg-5 text-center">

                <img src="{{ asset('assets/img/why-us.gif') }}"
                    class="img-fluid"
                    style="max-height: 350px;"
                    alt="Why Us">

            </div>

        </div>

    </div>

</section>

{{-- ================= CONTACT SECTION ================= --}}
<section id="contact" class="contact-section py-5 bg-light">

    <div class="container">

        {{-- TITLE --}}
        <div class="text-center mb-5">
            <h2 class="fw-bold">Contact Us</h2>
            <p class="text-muted">
                Got questions? We’re here to help. Send us a message anytime.
            </p>
        </div>

        <div class="row g-4">

            {{-- LEFT INFO --}}
            <div class="col-lg-5">

                <div class="contact-info-box bg-white p-4 shadow-sm rounded">

                    <div class="contact-item">
                        <i class="fa fa-location-dot"></i>
                        <div>
                            <h5>Location</h5>
                            <p>
                                Unit 1 Eden Townhomes<br>
                                Pedro Gil St., Sta. Ana, Manila
                            </p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <i class="fa fa-envelope"></i>
                        <div>
                            <h5>Email</h5>
                            <p>crempcohrd@gmail.com</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <i class="fa fa-phone"></i>
                        <div>
                            <h5>Phone</h5>
                            <p>+63 912 345 6789</p>
                        </div>
                    </div>

                    <div class="map-box mt-4">
                        <iframe
                            src="https://maps.google.com/maps?q=Manila&t=&z=12&ie=UTF8&iwloc=&output=embed"
                            frameborder="0"
                            allowfullscreen>
                        </iframe>
                    </div>

                </div>

            </div>

            {{-- RIGHT FORM --}}
            <div class="col-lg-7">

                <form class="contact-form bg-white p-4 shadow-sm rounded">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="Your Name" required>
                        </div>

                        <div class="col-md-6">
                            <input type="email" class="form-control" placeholder="Your Email" required>
                        </div>

                        <div class="col-12">
                            <input type="text" class="form-control" placeholder="Subject" required>
                        </div>

                        <div class="col-12">
                            <textarea class="form-control" rows="6" placeholder="Message" required></textarea>
                        </div>

                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                Send Message
                            </button>
                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</section>

@endsection