<div class="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('images/SCHEDULIA-Logo.png') }}" alt="Logo">
        <span class="sidebar-link-text">SCHEDULIA</span>
    </div>
    <a href="{{ url('/dashboard-mahasiswa') }}" class="sidebar-link {{ request()->is('dashboard-mahasiswa') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Dashboard">
        <i class="bi bi-speedometer2"></i> <span class="sidebar-link-text">Dashboard</span>
    </a>
    <a href="{{ route('jadwalmahasiswa.index') }}" class="sidebar-link {{ request()->is('jadwalmahasiswa') ? 'active' : '' }}">
        <i class="bi bi-calendar-event"></i> <span class="sidebar-link-text">Jadwal Kuliah</span>
    </a>
    <a href="{{ route('krs.index') }}" class="sidebar-link {{ request()->is('krs') ? 'active' : '' }}">
        <i class="bi bi-card-list"></i> <span class="sidebar-link-text">KRS</span>
    </a>
    <a href="{{ route('pengambilan-mk.create') }}" class="sidebar-link {{ request()->is('pengambilan-mk') ? 'active' : '' }}">
        <i class="bi bi-plus-circle"></i> <span class="sidebar-link-text">Ambil Mata Kuliah</span>
    </a>
</div>