<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjadwalan</title>
    @vite(['resources/css/app.scss', 'resources/css/dosen-dashboard.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
                    <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); confirmLogout('logout-form-dosen');">Logout</a></li>
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