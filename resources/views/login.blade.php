@extends('layouts.app')

@section('title', 'Login')

@section('content')

{{-- ================= LOGIN SECTION ================= --}}
<section id="login" class="py-5 d-flex align-items-center" style="min-height: 80vh;">

    <div class="container">

        <div class="row justify-content-center align-items-center">

            {{-- LEFT SIDE (IMAGE) --}}
            <div class="col-lg-6 text-center mb-4 mb-lg-0">

                <img src="{{ asset('assets/img/hero-img.png') }}"
                     class="img-fluid"
                     alt="Hero Image">

            </div>

            {{-- RIGHT SIDE (FORM) --}}
            <div class="col-lg-5">

                <div class="card shadow-sm border-0 rounded-4 p-4">

                    <h3 class="fw-bold mb-3 text-center">Welcome Back</h3>

                    <p class="text-muted text-center mb-4">
                        Login to access your account
                    </p>

                    <form method="POST" action="{{ url('/login') }}">

                        @csrf

                        {{-- EMAIL --}}
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   placeholder="Enter your email"
                                   required>
                        </div>

                        {{-- PASSWORD --}}
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   placeholder="Enter your password"
                                   required>
                        </div>

                        {{-- ERROR MESSAGE --}}
                        @if ($errors->any())
                            <div class="alert alert-danger py-2">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        {{-- BUTTON --}}
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            Login
                        </button>

                    </form>

                    {{-- OPTIONAL LINKS --}}
                    <div class="text-center mt-3">

                        <small class="text-muted">
                            Don't have an account?
                            <a href="{{ url('register') }}">Register here</a>
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection