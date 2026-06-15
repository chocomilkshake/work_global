<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <div class="admin-wrapper">

        {{-- Sidebar --}}
        <aside class="sidebar">

            <div class="logo">
                <h3>Admin Panel</h3>
            </div>

            <ul class="sidebar-menu">

                <li>
                    <a href="#">
                        <i class="fa-solid fa-house"></i>
                        Dashboard
                    </a>
                </li>

                <li>
                    <a href="{{ url('/admin/user') }}">
                        <i class="fa-solid fa-users"></i>
                        Users
                    </a>
                </li>

                <li>
                    <a href="{{ url('/admin/employer') }}">
                        <i class="fa-solid fa-building"></i>
                        Employers
                    </a>
                </li>

                <li></li>
                    <a href="#">
                        <i class="fa-solid fa-briefcase"></i>
                        Jobs
                    </a>
                </li>

                <li>
                    <a href="{{ url('/admin/activity-logs') }}">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        Activity Logs
                    </a>
                </li>

                <li>
                    <a href="#">
                        <i class="fa-solid fa-gear"></i>
                        Settings
                    </a>
                </li>

            </ul>

        </aside>

        {{-- Main Content --}}
        <main class="main-content">

            {{-- Navbar --}}
            <div class="top-navbar">

                <div>
                    <h4>@yield('page-title')</h4>
                </div>

                <div class="admin-profile d-flex align-items-center gap-3">

                    <i class="fa-solid fa-user"></i>
                    <span>Admin</span>

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

    </div>

</body>

</html>