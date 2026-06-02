<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="@yield('body-class')">

    {{-- HEADER --}}
    <header class="main-header">

        <div class="logo">
            Work Global
        </div>

        <nav class="nav-links">

            <a href="/">Home</a>

            <a href="/jobs">Jobs</a>

            <a href="{{ url('login') }}" class="btn btn-outline-primary px-4">
                Get Started
            </a>

        </nav>

    </header>

    {{-- PAGE CONTENT --}}
    @yield('content')

    {{-- FOOTER --}}
    <footer class="bg-dark text-white mt-auto py-4">

        <div class="container">

            <div class="row gy-4">

                {{-- Company --}}
                <div class="col-md-4">

                    <h5 class="fw-bold mb-3">
                        JobPortal
                    </h5>

                    <p class="text-light opacity-75 mb-0">
                        Connecting employers and applicants through a modern
                        recruitment platform.
                    </p>

                </div>

                {{-- Quick Links --}}
                <div class="col-md-4">

                    <h5 class="fw-bold mb-3">
                        Quick Links
                    </h5>

                    <ul class="list-unstyled">

                        <li class="mb-2">
                            <a href="/" class="text-decoration-none text-light opacity-75">
                                Home
                            </a>
                        </li>

                        <li class="mb-2">
                            <a href="/jobs" class="text-decoration-none text-light opacity-75">
                                Jobs
                            </a>
                        </li>

                    </ul>

                </div>

                {{-- Contact --}}
                <div class="col-md-4">

                    <h5 class="fw-bold mb-3">
                        Contact
                    </h5>

                    <p class="mb-1 text-light opacity-75">
                        Email: info@jobportal.com
                    </p>

                    <p class="mb-1 text-light opacity-75">
                        Phone: +63 912 345 6789
                    </p>

                    <p class="mb-0 text-light opacity-75">
                         UNIT 1 EDEN TOWNHOMES 2001 EDEN ST. <br>
                         COR. PEDRO GIL ST. STA. ANA MANILA 
                    </p>

                </div>

            </div>

            <hr class="border-secondary my-4">

            <div class="text-center text-light opacity-75">
                © {{ date('Y') }} JobPortal. All rights reserved.
            </div>

        </div>

    </footer>

    {{-- JS --}}
    <script src="{{ asset('js/jobs.js') }}"></script>
    <script src="{{ asset('js/profile.js') }}"></script>

</body>

</html>