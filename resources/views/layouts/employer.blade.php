<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/employer.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    @php
        $employer = Auth::guard('employer')->user();
        $employerPending = $employer && $employer->status === 'Pending';
        $disabledClass = $employerPending ? 'disabled text-muted' : '';
    @endphp

    <div class="admin-wrapper">

        {{-- Sidebar --}}
        <aside class="sidebar">

            <div class="logo">
                <h3>Employer Panel</h3>
            </div>

            <ul class="sidebar-menu">

                <li>
                    <a href="{{ route('employer.dashboard') }}">
                        <i class="fa-solid fa-house"></i>
                        Dashboard
                    </a>
                </li>

                <li>
                    <a href="{{ $employerPending ? 'javascript:void(0);' : route('employer.job') }}"
                        class="{{ $employerPending ? 'disabled text-muted' : '' }}"
                        {{ $employerPending ? 'aria-disabled=true tabindex=-1' : '' }}>
                        <i class="fa-solid fa-briefcase"></i>
                        Job Postings
                    </a>
                </li>

                <li>
                    <a href="#"
                        class="{{ $employerPending ? 'disabled text-muted' : '' }}"
                        {{ $employerPending ? 'aria-disabled=true tabindex=-1' : '' }}>
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Candidates
                    </a>
                </li>

                <li>
                    <a href="#"
                        class="{{ $employerPending ? 'disabled text-muted' : '' }}"
                        {{ $employerPending ? 'aria-disabled=true tabindex=-1' : '' }}>
                        <i class="fa-solid fa-file-import"></i>
                        Encoded Candidates
                    </a>
                </li>

                <li>
                    <a href="#"
                        class="{{ $employerPending ? 'disabled text-muted' : '' }}"
                        {{ $employerPending ? 'aria-disabled=true tabindex=-1' : '' }}>
                        <i class="fa-solid fa-user-check"></i>
                        Submitted Applications
                    </a>
                </li>

                <li>
                    <a href="{{ route('employer.account') }}"
                        class="{{ $employerPending ? '' : '' }}">
                        <i class="fa-solid fa-gear"></i>
                        Account Settings
                    </a>
                </li>

            </ul>

        </aside>

        {{-- Main Content --}}
        <main class="main-content">

            {{-- Navbar --}}
            <div class="top-navbar">

                <div class="page-title-wrapper">
                    <button id="sidebarToggle" class="sidebar-toggle btn btn-outline-primary d-lg-none" type="button" aria-label="Toggle sidebar">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <h4 class="mb-0">@yield('page-title')</h4>
                </div>

@php
        $employer = Auth::guard('employer')->user();
        $employerPending = $employer && $employer->status === 'Pending';
        $disabledClass = $employerPending ? 'disabled text-muted' : '';
    @endphp

    <div class="admin-profile d-flex align-items-center gap-3">
        <div class="profile-identity d-flex align-items-center gap-2">
            <i class="fa-solid fa-user"></i>
            <span>{{ $employer->name ?? 'Employer' }}</span>
                    </div>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Logout
                        </button>
                    </form>

                </div>

            </div>

            {{-- Page Content --}}
            <div class="content-area">
                @yield('content')
            </div>

        </main>

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

    </div>

    <script>
        window.addEventListener('pageshow', function (event) {
            if (event.persisted || (window.performance && window.performance.getEntriesByType('navigation').pop()?.type === 'back_forward')) {
                window.location.reload();
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');
            const wrapper = document.querySelector('.admin-wrapper');
            const navLinks = document.querySelectorAll('.sidebar-menu a');

            if (toggle && overlay && wrapper) {
                toggle.addEventListener('click', function () {
                    wrapper.classList.toggle('sidebar-open');
                });

                overlay.addEventListener('click', function () {
                    wrapper.classList.remove('sidebar-open');
                });

                navLinks.forEach(function (link) {
                    link.addEventListener('click', function () {
                        if (window.innerWidth <= 992) {
                            wrapper.classList.remove('sidebar-open');
                        }
                    });
                });

                // Action dropdown menus
                const actionDropdowns = document.querySelectorAll('.action-dropdown');
                actionDropdowns.forEach(function (dropdown) {
                    const toggle = dropdown.querySelector('.action-dropdown-toggle');
                    toggle.addEventListener('click', function (event) {
                        event.stopPropagation();
                        const isOpen = dropdown.classList.contains('open');
                        actionDropdowns.forEach(function (item) {
                            item.classList.remove('open');
                        });
                        if (!isOpen) {
                            dropdown.classList.add('open');
                        }
                    });
                });

                document.addEventListener('click', function () {
                    actionDropdowns.forEach(function (dropdown) {
                        dropdown.classList.remove('open');
                    });
                });
            }
        });
    </script>
</body>

</html>
