@extends('layouts.app')

@section('title', 'Get Started')

@section('content')

<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="fw-bold">Get Started</h1>
            <p class="text-muted mx-auto" style="max-width: 640px;">
                Choose your path below to continue as either an employer or a candidate.
            </p>
        </div>

        <div class="row justify-content-center g-4">
            <div class="col-md-5">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center py-5">
                        <i class="fa-solid fa-user-tie fa-3x text-primary mb-4"></i>
                        <h3 class="mb-3">Employer</h3>
                        <p class="text-muted mb-4">
                            Post job openings, manage candidates, and grow your team.
                        </p>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <a href="{{ url('employer_login') }}" class="btn btn-primary px-4">
                                Employer Login
                            </a>
                            <a href="{{ url('employer_register') }}" class="btn btn-outline-primary px-4">
                                Employer Register
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center py-5">
                        <i class="fa-solid fa-user fa-3x text-primary mb-4"></i>
                        <h3 class="mb-3">Candidate</h3>
                        <p class="text-muted mb-4">
                            Search jobs, submit applications, and advance your career.
                        </p>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <a href="{{ url('login') }}" class="btn btn-primary px-4">
                                Candidate Login
                            </a>
                            <a href="{{ url('register') }}" class="btn btn-outline-primary px-4">
                                Candidate Register
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
