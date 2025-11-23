@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Mahasiswa</h1>
    </div>

    <!-- Welcome Message -->
    <div class="welcome-message-box mb-4">
        <div class="icon">
            <i class="fas fa-sun"></i>
        </div>
        <div class="text">
            <h4>Selamat Datang di Schedulia!</h4>
            <p>Semoga kamu selalu bahagia dan tetap semangat menjalani hari.</p>
        </div>
    </div>

    <div class="row">
        <!-- Kolom Kiri (Konten Utama) -->
        <div class="col-lg-8">
            <!-- Widget Jadwal Hari Ini -->
            <div class="widget-card secondary-border mb-4">
                <div class="widget-header">
                    <h2 class="widget-title"><i class="fas fa-calendar-day"></i>Jadwal Kuliah Hari Ini</h2>
                    <span class="badge bg-success text-white">Hari Ini</span>
                </div>
                <ul class="jadwal-list">
                    @forelse($jadwalHariIni as $jadwal)
                        <li class="jadwal-item" data-start="{{ $jadwal->jam_mulai }}" data-end="{{ $jadwal->jam_selesai }}">
                            <div class="jadwal-info">
                                <div class="jadwal-matkul">{{ $jadwal->pengampu->matakuliah->nama }}</div>
                                <small class="text-muted">
                                    @forelse($jadwal->pengampu->dosen as $dosen)
                                        {!! $dosen->nama !!}{!! !$loop->last ? '<br>' : '' !!}
                                    @empty
                                        Dosen belum ditentukan
                                    @endforelse
                                </small>
                            </div>
                            <div class="text-right">
                                <div class="jadwal-time">{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</div>
                                <div class="jadwal-room"><i class="fas fa-map-marker-alt fa-fw"></i>{{ $jadwal->ruang->nama_ruang }}</div>
                            </div>
                        </li>
                    @empty
                        <li class="jadwal-item text-center d-block">
                            <i class="fas fa-check-circle text-success me-2"></i>Tidak ada jadwal kuliah hari ini. Waktunya produktif!
                        </li>
                    @endforelse
                </ul>
            </div>

            <!-- Widget Jadwal Minggu Ini (BARU) -->
            <div class="widget-card primary-border">
                <div class="widget-header">
                    <h2 class="widget-title"><i class="fas fa-calendar-week"></i>Jadwal Minggu Ini</h2>
                </div>
                
                <ul class="nav nav-tabs nav-fill mb-3" id="jadwalMingguIniTab" role="tablist">
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ strtolower($hari) }}-tab" data-bs-toggle="tab" data-bs-target="#{{ strtolower($hari) }}" type="button" role="tab">{{ $hari }}</button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content" id="jadwalMingguIniTabContent">
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ strtolower($hari) }}" role="tabpanel">
                            <ul class="jadwal-list">
                                @forelse($jadwalSeminggu[$hari] ?? [] as $jadwal)
                                    <li class="jadwal-item">
                                        <div class="jadwal-info">
                                            <div class="jadwal-matkul">{{ $jadwal->pengampu->matakuliah->nama }}</div>
                                            <small class="text-muted">
                                    @forelse($jadwal->pengampu->dosen as $dosen)
                                        {!! $dosen->nama !!}{!! !$loop->last ? '<br>' : '' !!}
                                    @empty
                                        Dosen belum ditentukan
                                    @endforelse
                                </small>
                                        </div>
                                        <div class="text-right">
                                            <div class="jadwal-time">{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</div>
                                            <div class="jadwal-room"><i class="fas fa-map-marker-alt fa-fw"></i>{{ $jadwal->ruang->nama_ruang }}</div>
                                        </div>
                                    </li>
                                @empty
                                    <li class="jadwal-item text-center d-block">
                                        <i class="fas fa-coffee me-2"></i>Tidak ada jadwal kuliah.
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Kolom Kanan (Sidebar) -->
        <div class="col-lg-4">
            <!-- Widget Jam Digital Atas -->
            <div class="digital-clock-widget-top mb-4">
                <div id="digital-clock-top"></div>
                <div id="digital-date-top"></div>
            </div>

            <!-- Widget Profil -->
            <div class="widget-card primary-border profile-widget mb-4">
                <img src="{{ asset($mahasiswa->foto_profil ? 'storage/foto_profil/' . $mahasiswa->foto_profil : 'images/default-profil.svg') }}" alt="Foto Profil" class="profile-img">
                <h3 class="profile-name">{{ Auth::user()->name }}</h3>
                <p class="profile-nim">{{ $mahasiswa->nim }}</p>
                <p class="mb-1 text-muted"><strong>Prodi:</strong> {{ $mahasiswa->prodi->nama_prodi }}</p>
                <p class="mb-3 text-muted"><strong>Semester:</strong> {{ $mahasiswa->semester }}</p>
                <a href="{{ route('mahasiswa.profil.edit') }}" class="btn btn-primary btn-sm btn-edit-profile"><i class="fas fa-user-edit me-1"></i>Edit Profil</a>
            </div>

            
        </div>
    </div>
    
    <div class="row mt-2">
        <div class="col-12">
            <!-- Widget Pengumuman -->
            <div class="widget-card warning-border">
                <div class="widget-header">
                    <h2 class="widget-title"><i class="fas fa-bullhorn"></i>Pengumuman Penting</h2>
                </div>
                <div class="pengumuman-list">
                    @forelse($pengumumans as $item)
                        @php
                            $cardClass = 'info-type'; $iconClass = 'fas fa-info-circle'; $badgeClass = 'info';
                            if ($item->tipe == 'perubahan') { $cardClass = 'warning-type'; $iconClass = 'fas fa-exclamation-triangle'; $badgeClass = 'warning'; }
                            elseif ($item->tipe == 'pembatalan') { $cardClass = 'danger-type'; $iconClass = 'fas fa-times-circle'; $badgeClass = 'danger'; }
                        @endphp
                        <div class="announcement-card {{ $cardClass }}">
                            <div class="announcement-header">
                                <i class="announcement-icon {{ $iconClass }}"></i>
                                <div>
                                    <h5 class="announcement-title">{{ $item->jadwalKuliah->pengampu->matakuliah->nama }}</h5>
                                    <div class="announcement-meta">
                                        <span class="announcement-type-badge {{ $badgeClass }}">{{ ucfirst($item->tipe) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="announcement-content">
                                <p>{{ $item->pesan }}</p>
                            </div>
                            <div class="announcement-footer">
                                <span>Oleh: {{ $item->dosen->nama }}</span>
                                <span>{{ $item->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">Tidak ada pengumuman penting untuk saat ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk mengupdate jam digital
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        const clockElTop = document.getElementById('digital-clock-top');
        if (clockElTop) {
            clockElTop.textContent = `${hours}:${minutes}:${seconds}`;
        }

        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateElTop = document.getElementById('digital-date-top');
        if (dateElTop) {
            dateElTop.textContent = now.toLocaleDateString('id-ID', options);
        }
    }

    // Fungsi untuk menandai jadwal yang sedang berlangsung
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

    // Inisialisasi dan interval
    updateClock();
    updateScheduleHighlight();
    setInterval(updateClock, 1000);
    setInterval(updateScheduleHighlight, 60000); // Cek setiap menit

    // Bootstrap Tab
    var triggerTabList = [].slice.call(document.querySelectorAll('#jadwalMingguIniTab button'))
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)

        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    });

    // Listen for new announcements
    if (window.Echo) {
        const mahasiswa_id = {{ Auth::id() }};
        if (mahasiswa_id) {
            window.Echo.private(`mahasiswa.${mahasiswa_id}`)
                .listen('.pengumuman-baru', (e) => {
                    console.log('Pengumuman baru diterima:', e);
                    
                    const pengumuman = e.pengumuman;
                    const mataKuliah = e.mataKuliah;
                    const dosen = e.dosen;

                    // Determine icon and color based on tipe
                    let icon = 'info';
                    let title = 'Informasi Baru';
                    let borderColor = '#36a2cd';
                    if (pengumuman.tipe === 'perubahan') {
                        icon = 'warning';
                        title = 'Perubahan Jadwal';
                        borderColor = '#ffc107';
                    } else if (pengumuman.tipe === 'pembatalan') {
                        icon = 'error';
                        title = 'Pembatalan Kelas';
                        borderColor = '#dc3545';
                    }

                    // Play notification sound
                    // const audio = new Audio('path/to/notification.mp3');
                    // audio.play();

                    Swal.fire({
                        title: `<strong>${title}</strong>`,
                        icon: icon,
                        html: `
                            <div class="text-start">
                                <p class="mb-2"><strong>Mata Kuliah:</strong> ${mataKuliah}</p>
                                <p class="mb-2"><strong>Dosen:</strong> ${dosen}</p>
                                <p class="mb-0">${pengumuman.pesan}</p>
                            </div>
                        `,
                        showCloseButton: true,
                        showCancelButton: true,
                        focusConfirm: false,
                        confirmButtonText: '<i class="fas fa-eye"></i> Lihat',
                        confirmButtonAriaLabel: 'Lihat pengumuman',
                        cancelButtonText: 'Tutup',
                        cancelButtonAriaLabel: 'Tutup',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: true,
                        timer: 15000,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'swal2-toast-custom',
                            header: 'swal2-toast-header-custom',
                            title: 'swal2-toast-title-custom',
                            htmlContainer: 'swal2-toast-html-container-custom',
                        },
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        },
                        willClose: () => {
                            // Optional: remove the toast from the DOM
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to the announcement page
                            // window.location.href = `/pengumuman/${pengumuman.id}`;
                            
                            // For now, just reload the page
                            window.location.reload();
                        }
                    });

                    // Optional: refresh the page to show the new announcement in the list
                    // This is now handled by the "Lihat" button
                    // setTimeout(() => {
                    //     window.location.reload();
                    // }, 5000); 
                });
        } else {
            console.log('Echo listener: User ID not found.');
        }
    } else {
        console.log('Laravel Echo not found. Real-time updates disabled.');
    }
});
</script>
@endpush

