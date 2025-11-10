@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-color: #4e73df; /* Blue */
        --secondary-color: #1cc88a; /* Green */
        --accent-color: #36b9cc; /* Cyan */
        --warning-color: #f6c23e; /* Yellow */
        --danger-color: #e74a3b; /* Red */
        --text-color: #5a5c69;
        --text-light: #858796;
        --bg-light: #f8f9fc;
        --bg-dark: #e4e6ef;
        --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        --card-shadow-hover: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
        --border-radius: 0.75rem;
    }

    body {
        background-color: var(--bg-light);
        font-family: 'Nunito', sans-serif; /* Assuming Nunito is available or linked in layouts.app */
    }

    .widget-card {
        background-color: #fff;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        margin-bottom: 1.5rem;
        padding: 1.5rem;
        transition: all 0.3s ease;
        border-left: 0.25rem solid transparent; /* Subtle border for emphasis */
    }

    .widget-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-shadow-hover);
    }

    .widget-card.primary-border { border-left-color: var(--primary-color); }
    .widget-card.secondary-border { border-left-color: var(--secondary-color); }
    .widget-card.accent-border { border-left-color: var(--accent-color); }
    .widget-card.warning-border { border-left-color: var(--warning-color); }

    .widget-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        border-bottom: 1px solid var(--bg-dark);
        padding-bottom: 0.75rem;
    }

    .widget-title {
        font-weight: 700;
        color: var(--text-color);
        font-size: 1.2rem;
        display: flex;
        align-items: center;
    }
    .widget-title i {
        color: var(--primary-color); /* Default icon color */
        margin-right: 0.5rem;
    }
    .widget-card.secondary-border .widget-title i { color: var(--secondary-color); }
    .widget-card.accent-border .widget-title i { color: var(--accent-color); }
    .widget-card.warning-border .widget-title i { color: var(--warning-color); }


    /* --- Profile Widget Specific Styles --- */
    .profile-widget {
        text-align: center;
        background: linear-gradient(135deg, #ffffff, var(--bg-light)); /* Subtle gradient background */
        border-left-color: var(--primary-color);
    }

    .profile-widget .profile-img {
        width: 100px; /* Larger image */
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid var(--primary-color); /* Thicker border */
        padding: 3px; /* Space between image and border */
        box-shadow: 0 0 0.8rem rgba(0,0,0,0.15);
        margin-bottom: 1rem; /* Space below image */
        transition: transform 0.3s ease;
    }

    .profile-widget .profile-img:hover {
        transform: scale(1.05);
    }

    .profile-widget .profile-name {
        font-weight: 800; /* Bolder name */
        font-size: 1.6rem; /* Even larger */
        color: var(--text-color);
        margin-bottom: 0.25rem;
    }
    .profile-widget .profile-nim {
        font-size: 1rem;
        color: var(--text-light);
        margin-bottom: 1rem;
    }

    .profile-widget .btn-edit-profile {
        margin-top: 1rem;
        border-radius: 50px; /* Pill-shaped button */
        padding: 0.5rem 1.5rem;
        font-weight: 600;
    }
    /* --- End Profile Widget Specific Styles --- */


    .jadwal-list {
        list-style: none;
        padding-left: 0;
    }

    .jadwal-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.25rem; /* Increased padding */
        border-radius: 0.75rem; /* More rounded */
        margin-bottom: 0.75rem;
        background-color: var(--bg-light);
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .jadwal-item:hover {
        background-color: var(--bg-dark);
        border-color: var(--primary-color);
        transform: translateX(5px);
    }

    .jadwal-item.active {
        background: linear-gradient(45deg, var(--secondary-color), #28a745); /* Gradient background */
        color: #fff;
        box-shadow: 0 0.25rem 0.75rem rgba(28, 200, 138, 0.4);
        border-color: var(--secondary-color);
    }
    .jadwal-item.active .jadwal-time,
    .jadwal-item.active .jadwal-room,
    .jadwal-item.active .jadwal-matkul,
    .jadwal-item.active small {
        color: #fff !important;
    }
    .jadwal-item.active .jadwal-matkul {
        font-weight: 700;
    }

    .jadwal-time {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 1.1rem;
    }
    .jadwal-room {
        font-style: italic;
        color: var(--text-light);
        font-size: 0.9rem;
    }
    .jadwal-info .jadwal-matkul {
        font-weight: 600;
        color: var(--text-color);
    }

    #digital-clock-widget {
        text-align: center;
        background: linear-gradient(135deg, var(--accent-color), #20c997); /* Gradient background */
        color: #fff;
        box-shadow: 0 0.25rem 0.75rem rgba(54, 185, 204, 0.4);
        border-left-color: var(--accent-color);
    }
    #digital-clock-widget .widget-header {
        border-bottom-color: rgba(255,255,255,0.3);
    }
    #digital-clock-widget .widget-title {
        color: #fff;
    }
    #digital-clock-widget .widget-title i {
        color: #fff;
    }

    #digital-clock {
        font-size: 3rem; /* Larger clock */
        font-weight: 800;
        color: #fff;
        letter-spacing: 2px;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
    }

    #digital-date {
        font-size: 1.1rem;
        color: rgba(255,255,255,0.9);
        margin-top: 0.5rem;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #3a5cdb;
        border-color: #3a5cdb;
        transform: translateY(-2px);
    }
    .btn-success {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
        transition: all 0.3s ease;
    }
    .btn-success:hover {
        background-color: #17a673;
        border-color: #17a673;
        transform: translateY(-2px);
    }
    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color: #fff;
    }

    /* Custom progress bar for schedule (optional, requires JS) */
    .schedule-progress-bar {
        height: 5px;
        background-color: var(--bg-dark);
        border-radius: 5px;
        margin-top: 0.5rem;
        overflow: hidden;
    }
    .schedule-progress {
        height: 100%;
        width: 0%;
        background-color: var(--secondary-color);
        border-radius: 5px;
        transition: width 1s linear;
    }
</style>

<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Mahasiswa</h1>
    </div>

    <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-lg-8">
            <!-- Widget Jadwal Hari Ini -->
            <div class="widget-card secondary-border">
                <div class="widget-header">
                    <h2 class="widget-title"><i class="fas fa-calendar-day mr-2"></i>Jadwal Kuliah Hari Ini</h2>
                </div>
                <ul class="jadwal-list">
                    @if(isset($jadwalHariIni) && $jadwalHariIni->count() > 0)
                        @foreach($jadwalHariIni as $jadwal)
                            <li class="jadwal-item" data-start="{{ $jadwal->jam->jam_mulai }}" data-end="{{ $jadwal->jam->jam_selesai }}">
                                <div class="jadwal-info">
                                    <div class="jadwal-matkul">{{ $jadwal->pengampu->matakuliah->nama }}</div>
                                    <small class="text-muted">{{ $jadwal->pengampu->dosen->first()->nama ?? 'N/A' }}</small>
                                </div>
                                <div class="text-right">
                                    <div class="jadwal-time">{{ date('H:i', strtotime($jadwal->jam->jam_mulai)) }} - {{ date('H:i', strtotime($jadwal->jam->jam_selesai)) }}</div>
                                    <div class="jadwal-room"><i class="fas fa-map-marker-alt mr-1"></i>{{ $jadwal->ruang->nama_ruang }}</div>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <li class="jadwal-item text-center d-block">
                            <i class="fas fa-check-circle mr-2 text-success"></i>Tidak ada jadwal kuliah hari ini. Nikmati harimu!
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Widget Pengumuman -->
            <div class="widget-card warning-border">
                <div class="widget-header">
                    <h2 class="widget-title"><i class="fas fa-bullhorn mr-2"></i>Pengumuman Terbaru</h2>
                </div>
                <div id="announcement-list" class="pengumuman-list">
                    @if(isset($pengumuman) && $pengumuman->count() > 0)
                        @foreach($pengumuman as $item)
                            @php
                                $alertClass = 'alert-info'; // Default
                                if ($item->tipe == 'perubahan') {
                                    $alertClass = 'alert-warning';
                                } elseif ($item->tipe == 'pembatalan') {
                                    $alertClass = 'alert-danger';
                                }
                            @endphp
                            <div class="alert {{ $alertClass }}" role="alert">
                                <h5 class="alert-heading">
                                    {{ $item->jadwalKuliah->pengampu->matakuliah->nama }}
                                    <span class="badge badge-secondary">{{ ucfirst($item->tipe) }}</span>
                                </h5>
                                <p>{{ $item->pesan }}</p>
                                <hr>
                                <p class="mb-0 text-right">
                                    <small>
                                        Oleh: {{ $item->dosen->nama }} | {{ $item->created_at->diffForHumans() }}
                                    </small>
                                </p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted" id="no-announcement-message">Tidak ada pengumuman penting untuk saat ini.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-lg-4">
            <!-- Widget Profil -->
            <div class="widget-card primary-border profile-widget">
                <img src="{{ asset($mahasiswa->foto_profil ? 'storage/foto_profil/' . $mahasiswa->foto_profil : 'images/default-profil.svg') }}" alt="Foto Profil" class="profile-img">
                <h3 class="profile-name">{{ Auth::user()->name }}</h3>
                <p class="profile-nim">{{ $mahasiswa->nim }}</p>
                <p class="mb-1 text-muted"><strong>Prodi:</strong> {{ $mahasiswa->prodi->nama_prodi }}</p>
                <p class="mb-3 text-muted"><strong>Semester:</strong> {{ $mahasiswa->semester }}</p>
                <a href="{{ route('mahasiswa.profil.edit') }}" class="btn btn-primary btn-sm btn-edit-profile"><i class="fas fa-user-edit mr-1"></i>Edit Profil</a>
            </div>

            <!-- Widget Jam & Tanggal -->
            <div id="digital-clock-widget" class="widget-card accent-border">
                <div class="widget-header">
                    <h2 class="widget-title"><i class="fas fa-clock mr-2"></i>Waktu Saat Ini</h2>
                </div>
                <div id="digital-clock"></div>
                <div id="digital-date"></div>
            </div>

            
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Pass kelas_id to JavaScript
    const KELAS_ID = {{ $mahasiswa->kelas_id ?? 'null' }};
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Existing scripts...
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const clockElement = document.getElementById('digital-clock');
        if (clockElement) {
            clockElement.textContent = `${hours}:${minutes}:${seconds}`;
        }

        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateElement = document.getElementById('digital-date');
        if (dateElement) {
            dateElement.textContent = now.toLocaleDateString('id-ID', options);
        }
    }

    function updateScheduleHighlight() {
        const now = new Date();
        const currentTimeMinutes = now.getHours() * 60 + now.getMinutes();

        document.querySelectorAll('.jadwal-item').forEach(item => {
            const startTimeStr = item.dataset.start;
            const endTimeStr = item.dataset.end;

            if (startTimeStr && endTimeStr) {
                const startMinutes = parseInt(startTimeStr.split(':')[0]) * 60 + parseInt(startTimeStr.split(':')[1]);
                const endMinutes = parseInt(endTimeStr.split(':')[0]) * 60 + parseInt(endTimeStr.split(':')[1]);

                if (currentTimeMinutes >= startMinutes && currentTimeMinutes < endMinutes) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            }
        });
    }

    // Initial calls
    updateClock();
    updateScheduleHighlight();

    // Set intervals
    setInterval(updateClock, 1000);
    setInterval(updateScheduleHighlight, 60000);

    // Listen for new announcements
    if (KELAS_ID) {
        window.Echo.private(`kelas.${KELAS_ID}`)
            .listen('pengumuman.created', (e) => {
                console.log('Event received:', e); // For debugging

                const pengumuman = e.pengumuman;

                // Show a toast notification
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: `Pengumuman Baru: ${pengumuman.matakuliah}`,
                    html: `<strong>Tipe:</strong> ${pengumuman.tipe.charAt(0).toUpperCase() + pengumuman.tipe.slice(1)}<br>
                           <strong>Pesan:</strong> ${pengumuman.pesan}<br>
                           <strong>Oleh:</strong> ${pengumuman.dosen}<br>
                           <small>${pengumuman.created_at}</small>`,
                    showConfirmButton: false,
                    timer: 7000, // Increased timer for more info
                    timerProgressBar: true
                });

                // Create the new announcement element
                const newAnnouncement = document.createElement('div');
                let alertClass = 'alert-info';
                if (pengumuman.tipe === 'perubahan') {
                    alertClass = 'alert-warning';
                } else if (pengumuman.tipe === 'pembatalan') {
                    alertClass = 'alert-danger';
                }
                newAnnouncement.className = `alert ${alertClass}`;
                newAnnouncement.setAttribute('role', 'alert');

                newAnnouncement.innerHTML = `
                    <h5 class="alert-heading">
                        ${pengumuman.matakuliah}
                        <span class="badge badge-secondary">${pengumuman.tipe.charAt(0).toUpperCase() + pengumuman.tipe.slice(1)}</span>
                    </h5>
                    <p>${pengumuman.pesan}</p>
                    <hr>
                    <p class="mb-0 text-right">
                        <small>
                            Oleh: ${pengumuman.dosen} | Baru saja
                        </small>
                    </p>
                `;

                // Prepend to the list
                const pengumumanList = document.getElementById('announcement-list');
                if (pengumumanList) {
                    const noAnnouncement = document.getElementById('no-announcement-message');
                    if (noAnnouncement) {
                        noAnnouncement.remove();
                    }
                    pengumumanList.prepend(newAnnouncement);
                }
            });
    }
});
</script>
@endpush