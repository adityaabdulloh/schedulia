<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedulia - Sistem Penjadwalan</title>
    <link rel="icon" href="{{ asset('images/SCHEDULIA-Logo.png') }}" type="image/png">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="ably-key" content="{{ config('broadcasting.connections.ably.key') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')
    <style>
        .sidebar-link {
            padding: 12px 20px !important;
            font-size: 1.1em !important;
        }
        .submenu-link {
            padding: 10px 15px 10px 40px !important;
            font-size: 1em !important;
        }
        .main-wrapper-guest {
            margin-left: 0 !important;
        }
        .dropdown-menu {
            z-index: 1050;
        }
        .profile-section {
            margin-right: 20px;
        }
    </style>
</head>
<body>
    @auth
    <!-- Sidebar -->
    @if (Auth::user()->role == 'admin')
        @include('layouts.partials.sidebar-admin')
    @elseif (Auth::user()->role == 'mahasiswa')
        @include('layouts.partials.sidebar-mahasiswa')
    @elseif (Auth::user()->role == 'dosen')
        @include('layouts.partials.sidebar-dosen')
    @endif
    @endauth

    <!-- Header -->
    <div class="header">
        @auth
        <button id="sidebar-toggle">
            <i class="bi bi-list"></i>
        </button>
        @endauth
        @auth
        <div class="ms-auto profile-section d-flex align-items-center">
            @if (Auth::user()->role == 'admin')
                <a class="btn btn-info ms-3" href="{{ route('admin.edit-profile') }}">Edit Profile</a>
            @endif
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-danger ms-3">Logout</a>
        </div>
        @endauth
    </div>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper @guest main-wrapper-guest @endguest">
        <!-- Main Content -->
        <div class="main-content">
            

            @yield('content')
        </div>
    </div>

    <!-- SweetAlert Notifications -->
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: '{{ session('error') }}',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true
        });
    </script>
    @endif

    

    


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@stack('scripts')
</body>
</html>