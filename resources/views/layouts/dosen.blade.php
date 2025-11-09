<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjadwalan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        /* Header Styling */
        .header {
            background-color: #fff;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        .header h4 {
            margin: 0;
            font-weight: bold;
            color: #343a40;
        }
        .profile-section {
            display: flex;
            align-items: center;
        }
        .profile-section i {
            font-size: 24px;
            margin-right: 10px;
            color: #495057;
        }

       /* Layout Wrapper */
       .layout {
            display: flex;
            margin-top: 75px; /* Space for the fixed header */
            height: calc(100vh - 75px); /* Adjust height to fill remaining viewport */
            overflow: hidden; /* Prevent overall layout from scrolling */
        }

        .sidebar {
            width: 200px;
            background-color: #343a40;
            color: #fff;
            padding: 20px 15px;
            position: fixed; /* Make sidebar fixed */
            top: 75px; /* Align with header */
            bottom: 0;
            overflow-y: auto; /* Allow sidebar to scroll if content is too long */
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            background-color: #f8f9fa;
            margin-left: 200px; /* Match sidebar width */
            overflow-y: auto; /* Enable scrolling for main content */
            height: calc(100vh - 75px); /* Full height minus header */
        }
        .sidebar a {
            display: block;
            color: #cfd8dc;
            font-weight: 500;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .sidebar a.active,
        .sidebar a:hover {
            background-color: #495057;
            color: #fff;
        }
        .sidebar a.buat-jadwal {
            background-color: #0471ff;
        }
        .sidebar a i {
            margin-right: 10px;
        }


        /* Card Styling */
        .card {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: none;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .sidebar {
                display: none; /* Hide sidebar on smaller screens */
            }
        }
        .title-with-underline {
            border-bottom: 2px solid #000; /* Ganti #000 dengan warna yang diinginkan */
            width: 27%; /* Agar border hanya muncul di bawah teks */
            padding-bottom: 5px; /* Jarak antara teks dan garis */
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
    <!-- Header -->
    <div class="header d-flex justify-content-between align-items-center">
        <div class="logo-section">
            <img src="{{ asset('images/SCHEDULIA-Logo.png') }}" alt="Logo" style="height: 50px;">
            <span>SCHEDULIA</span>
        </div>
        @auth
        <div class="profile-section">
            <i class="bi bi-person-circle"></i>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-dosen').submit();">Logout</a></li>
<form id="logout-form-dosen" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
                </ul>
            </div>
        </div>
        @endauth
    </div>

    <!-- Layout Wrapper -->
    <div class="layout @guest main-wrapper-guest @endguest">
        @auth
        <!-- Sidebar -->
        <div class="sidebar">
            <a href="{{ route('jadwaldosen.index') }}" class="buat-jadwal"><i class="bi bi-calendar-event"></i>Jadwal Dosen</a>
        </div>
        @endauth

        <!-- Main Content -->
        <div class="main-content @guest main-wrapper-guest @endguest">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- SweetAlert Notifications -->
    @if(session('success'))
    <script>
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true
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

    
</body>
</html>
