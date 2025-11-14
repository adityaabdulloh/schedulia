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
    <a href="{{ route('pengumuman.index') }}" class="sidebar-link {{ request()->is('pengumuman*') ? 'active' : '' }}">
        <i class="bi bi-megaphone-fill"></i> <span class="sidebar-link-text">Pengumuman</span>
    </a>
    <a href="{{ url('/pengampu') }}" class="sidebar-link {{ request()->is('pengampu') ? 'active' : '' }}">
        <i class="bi bi-book-fill"></i> <span class="sidebar-link-text">Mata Kuliah</span>
    </a>

    <div class="sidebar-link-wrapper">
        <a href="#" class="sidebar-link dropdown-toggle {{ request()->is(['dosen/mahasiswa*', 'dosen/absensi*']) ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#mahasiswa-submenu-dosen">
            <i class="bi bi-people-fill"></i> <span class="sidebar-link-text">Manajemen Mahasiswa</span>
        </a>
        <div class="collapse" id="mahasiswa-submenu-dosen">
            
            <a href="{{ url('dosen/pengambilan-mk') }}" class="sidebar-link sub-link {{ request()->is('dosen/pengambilan-mk') ? 'active' : '' }}">
                <span class="sidebar-link-text">Ambil MK</span>
            </a>
            <a href="{{ route('dosen.absensi.index') }}" class="sidebar-link sub-link {{ request()->is('dosen/absensi*') ? 'active' : '' }}">
                <span class="sidebar-link-text">Absensi</span>
            </a>
        </div>
    </div>
</div>
