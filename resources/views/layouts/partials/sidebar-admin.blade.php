<div class="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('images/SCHEDULIA-Logo.png') }}" alt="Logo">
        <span class="sidebar-link-text">SCHEDULIA</span>
    </div>
    <a href="{{ route('dashboard.admin') }}" class="sidebar-link {{ request()->is('dashboard/admin') ? 'active' : '' }}" data-bs-toggle="tooltip" title="Dashboard">
        <i class="bi bi-speedometer2"></i> <span class="sidebar-link-text">Dashboard</span>
    </a>
    <a href="{{ url('/matakuliah') }}" class="sidebar-link {{ request()->is('matakuliah*') ? 'active' : '' }}">
        <i class="bi bi-book"></i> <span class="sidebar-link-text">Mata Kuliah</span>
    </a>
    <a href="{{ url('/dosen') }}" class="sidebar-link {{ request()->is('dosen*') ? 'active' : '' }}">
        <i class="bi bi-person"></i> <span class="sidebar-link-text">Dosen</span>
    </a>
    <a href="{{ url('/prodi') }}" class="sidebar-link {{ request()->is('prodi*') ? 'active' : '' }}">
        <i class="bi bi-diagram-2"></i> <span class="sidebar-link-text">Program Studi</span>
    </a>
    <a href="{{ url('/ruang') }}" class="sidebar-link {{ request()->is('ruang*') ? 'active' : '' }}">
        <i class="bi bi-building"></i> <span class="sidebar-link-text">Ruangan</span>
    </a>
    <a href="{{ url('/kelas') }}" class="sidebar-link {{ request()->is('kelas*') ? 'active' : '' }}">
        <i class="bi bi-person-lines-fill"></i> <span class="sidebar-link-text">Kelas</span>
    </a>
    <a href="{{ url('/mahasiswa') }}" class="sidebar-link {{ request()->is('mahasiswa*') ? 'active' : '' }}">
        <i class="bi bi-person-vcard"></i> <span class="sidebar-link-text">Mahasiswa</span>
    </a>
    
    <a href="{{ url('/pengampu') }}" class="sidebar-link {{ request()->is('pengampu*') ? 'active' : '' }}">
        <i class="bi bi-person-check"></i> <span class="sidebar-link-text">Pengampu</span>
    </a>
    <a href="{{ url('/users') }}" class="sidebar-link {{ request()->is('users*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> <span class="sidebar-link-text">Users</span>
    </a>

    <a href="{{ route('jadwal.index') }}" class="buat-jadwal mt-4" data-bs-toggle="tooltip" title="Buat Jadwal">
        <i class="bi bi-calendar-event"></i> <span class="sidebar-link-text">Buat Jadwal</span>
    </a>
</div>