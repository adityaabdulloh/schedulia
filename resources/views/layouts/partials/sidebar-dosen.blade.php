<div class="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('images/SCHEDULIA-Logo.png') }}" alt="Logo">
        <span class="sidebar-link-text">SCHEDULIA</span>
    </div>
    <a href="{{ url('/dashboard-dosen') }}" class="sidebar-link {{ request()->is('dashboard-dosen') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Dashboard">
        <i class="bi bi-speedometer2"></i> <span class="sidebar-link-text">Dashboard</span>
    </a>
    <a href="{{ route('dosen.profil') }}" class="sidebar-link {{ request()->is('dosen/profil') ? 'active' : '' }}">
        <i class="bi bi-person-circle"></i> <span class="sidebar-link-text">Profil</span>
    </a>
    <a href="{{ url('/jadwaldosen') }}" class="sidebar-link {{ request()->is('jadwaldosen') ? 'active' : '' }}">
        <i class="bi bi-calendar-event"></i> <span class="sidebar-link-text">Jadwal Mengajar</span>
    </a>
    <a href="{{ url('/pengampu') }}" class="sidebar-link {{ request()->is('pengampu') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Mata Kuliah Diampu">
        <i class="bi bi-book-fill"></i> <span class="sidebar-link-text">Mata Kuliah Diampu</span>
    </a>

    <a href="{{ route('dosen.mahasiswa.index') }}" class="sidebar-link {{ request()->is('dosen/mahasiswa*') ? 'active' : '' }}">
        <i class="bi bi-people-fill"></i> <span class="sidebar-link-text">Manajemen Mahasiswa</span>
    </a>
</div>
