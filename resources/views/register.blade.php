@extends('layouts.app')

@section('title', 'Applicant Registration')

@section('content')

{{-- ================= REGISTER HERO ================= --}}
<section class="py-5 bg-light">

    <div class="container">

        <div class="row align-items-center g-5">

            {{-- LEFT SIDE INFO --}}
            <div class="col-lg-5">

                <h1 class="fw-bold">
                    Start your journey with <span class="text-primary">Work Global</span>
                </h1>

                <p class="text-muted mt-3">
                    Create your profile and get matched with global employers.
                    Fill in your details carefully to increase your hiring chances.
                </p>

                <img src="{{ asset('assets/img/hero-img.png') }}"
                    class="img-fluid mt-4"
                    alt="Register">

            </div>

            {{-- RIGHT SIDE FORM --}}
            <div class="col-lg-7">

                <div class="card border-0 shadow-sm rounded-4 p-4">

                    <h3 class="fw-bold mb-4">Applicant Registration</h3>



                    <form form action="{{ route('applicant.store') }}" method="POST" enctype="multipart/form-data">     
                        @csrf
                        {{-- ================= PROFILE CARD ================= --}}
                        <div class="profile-card">

                            {{-- COVER --}}
                            <div class="profile-cover"></div>

                            {{-- BODY --}}
                            <div class="profile-body">

                                <div class="profile-avatar-wrapper">

                                    <img id="profilePreview"
                                        src="{{ asset('assets/img/people.png') }}"
                                        data-default="{{ asset('assets/img/people.png') }}"
                                        class="profile-avatar"
                                        alt="Profile Image">

                                    <label for="profileImageInput" class="profile-camera">
                                        <i class="fa fa-camera"></i>
                                    </label>

                                    <input type="file"
                                        id="profileImageInput"
                                        name="profile_image"
                                        accept="image/*"
                                        hidden>

                                </div>

                                <h5 class="mt-3 mb-0">Add Profile Photo</h5>
                                <small class="text-muted">Make your profile stand out</small>

                                <div class="mt-3">
                                    <button type="button"
                                        id="profileRemoveBtn"
                                        class="btn btn-outline-danger btn-sm">
                                        Remove
                                    </button>
                                </div>

                            </div>

                        </div>
                        <br>
                        {{-- ================= NAME ================= --}}
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label">First Name<span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control" placeholder="Juan" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text"  name="middle_name" class="form-control" placeholder="Santos">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Last Name<span class="text-danger">*</span></label>
                                <input type="text"  name="last_name" class="form-control" placeholder="Dela Cruz" required>
                            </div>

                        </div>

                        {{-- ================= ADDRESS ================= --}}
                        <hr class="my-4">

                        <h6 class="fw-bold mb-3">Address Information</h6>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Street No.<span class="text-danger">*</span></label>
                                <input type="text" name="street_no" class="form-control" placeholder="123" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Full Address<span class="text-danger">*</span></label>
                                <input type="text" name="full_address" class="form-control" placeholder="Blk 5 Lot 10..." required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Region</label>
                                <select class="form-select" name="region">
                                    <option>Select Region</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" placeholder="City">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Barangay</label>
                                <input type="text" name="barangay" class="form-control" placeholder="Barangay">
                            </div>

                        </div>

                        {{-- ================= CONTACT ================= --}}
                        <hr class="my-4">

                        <h6 class="fw-bold mb-3">Contact Details</h6>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Contact Number</label>
                                <input type="text" name="contact_number" class="form-control" placeholder="09XXXXXXXXX">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email (optional)</label>
                                <input type="email" name="email" class="form-control" placeholder="email@gmail.com">
                            </div>

                        </div>

                        {{-- ================= RESUME ================= --}}
                        <hr class="my-4">

                        <h6 class="fw-bold mb-3">Documents</h6>

                        <div class="mb-3">
                            <label class="form-label">Upload Resume (optional)</label>
                            <input type="file" name="resume" class="form-control">
                            <small class="text-muted">PDF, DOC, DOCX only</small>
                        </div>

                        {{-- ================= BUTTON ================= --}}
                        <button type="submit" class="btn btn-primary w-100 py-2 mt-3">
                            Create Account
                        </button>

                        <p class="text-center mt-3 text-muted">
                            Already have an account?
                            <a href="{{ url('/login') }}">Login here</a>
                        </p>

                    </form>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection