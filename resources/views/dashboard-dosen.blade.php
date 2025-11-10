@extends('layouts.app')

@section('content')


<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Selamat Datang, {{ Auth::user()->name }}!</h1>
        
    </div>

    <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-lg-8">
            <!-- Widget Semua Jadwal Mengajar -->
            <div class="widget-card primary-border">
                <div class="widget-header">
                    <h2 class="widget-title"><i class="fas fa-calendar-alt mr-2"></i>Semua Jadwal Mengajar Anda</h2>
                </div>
                <div class="schedule-timeline">
                    @if(isset($semuaJadwal) && $semuaJadwal->count() > 0)
                        @foreach($semuaJadwal as $jadwal)
                            <div class="timeline-item jadwal-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="jadwal-info">
                                            <div class="jadwal-matkul">{{ $jadwal->pengampu->matakuliah->nama }}</div>
                                            <h6 class="text-success font-weight-bold"><i class="fas fa-calendar-day mr-1"></i>{{ $jadwal->hari->nama_hari }}</h6>
                                            <h6 class="text-primary"><i class="fas fa-users mr-1"></i>Kelas: {{ $jadwal->kelas->nama_kelas }}</h6>
                                            <h6 class="text-info d-block"><i class="fas fa-map-marker-alt mr-1"></i>Ruang: {{ $jadwal->ruang->nama_ruang }}</h6>
                                            <div class="jadwal-actions mt-2">
                                                <a href="{{ route('pengumuman.create', ['jadwalKuliah' => $jadwal->id]) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-bullhorn mr-1"></i> Buat Pengumuman
                                                </a>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="jadwal-time d-inline-block">{{ date('H:i', strtotime($jadwal->jam->jam_mulai)) }} - {{ date('H:i', strtotime($jadwal->jam->jam_selesai)) }}</div>
                                            <span class="badge badge-sks text-primary d-inline-block ml-2">{{ $jadwal->pengampu->matakuliah->sks }} SKS</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center empty-state">
                            <i class="fas fa-info-circle mr-2 fa-3x mb-3"></i>
                            <p class="lead">Tidak ada jadwal mengajar yang terdaftar untuk Anda.</p>
                            <p class="text-muted">Silakan hubungi admin jika ini adalah kesalahan.</p>
                        </div>
                    @endif
                </div>
            </div>

            
        </div>

        <!-- Kolom Kanan -->
        <div class="col-lg-4">
            <!-- Widget Profil -->
            <div class="widget-card profile-widget secondary-border">
                <img src="{{ $dosen->foto_profil ? asset('storage/foto_profil/' . $dosen->foto_profil) : asset('images/default-profil.svg') }}" alt="Foto Profil" class="profile-img">
                <div class="profile-info">
                    <h3 class="profile-name">{{ Auth::user()->name }}</h3>
                    <p class="mb-0 text-muted">{{ $dosen->nip }}</p>
                    @if($dosen->prodi)
                        <p class="mb-0 text-muted">{{ $dosen->prodi->nama_prodi }}</p>
                    @endif
                    <p class="mb-0 text-muted">{{ Auth::user()->email }}</p>
                    <a href="{{ route('dosen.edit-profile') }}" class="btn btn-primary mt-3">Edit Profile</a>
                </div>
            </div>

            
        </div>
    </div>

    <!-- Info Cards Row -->
    <div class="row">
        

        

        
    </div>
    <div class="row">
        
        
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    

    function updateScheduleHighlight() {
        const now = new Date();
        const currentTime = now.getHours() * 60 + now.getMinutes();

        document.querySelectorAll('.jadwal-item').forEach(item => {
            const startTimeStr = item.dataset.start;
            const endTimeStr = item.dataset.end;
            const liveBadge = item.querySelector('.live-badge'); // Get the live badge

            if (startTimeStr && endTimeStr) {
                const start = parseInt(startTimeStr.split(':')[0]) * 60 + parseInt(startTimeStr.split(':')[1]);
                const end = parseInt(endTimeStr.split(':')[0]) * 60 + parseInt(endTimeStr.split(':')[1]);

                if (currentTime >= start && currentTime < end) {
                    item.classList.add('active');
                    if (liveBadge) liveBadge.style.display = 'inline-block'; // Show badge
                } else {
                    item.classList.remove('active');
                    if (liveBadge) liveBadge.style.display = 'none'; // Hide badge
                }
            } else {
                if (liveBadge) liveBadge.style.display = 'none'; // Hide badge if no start/end time
            }
        });
    }

    updateScheduleHighlight();
    setInterval(updateScheduleHighlight, 60000); // Check every minute

    // Chart for Jumlah Mahasiswa Per Kelas
    const mahasiswaPerKelasData = @json($mahasiswaPerKelas ?? []);

    if (mahasiswaPerKelasData && Object.keys(mahasiswaPerKelasData).length > 0) {
        const chartElement = document.getElementById('mahasiswaPerKelasChart');
        if (chartElement) {
            const labels = Object.keys(mahasiswaPerKelasData);
            const data = Object.values(mahasiswaPerKelasData);

            const ctx = chartElement.getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Mahasiswa',
                        data: data,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    } else {
        const chartContainer = document.getElementById('mahasiswaPerKelasChart').parentNode;
        chartContainer.innerHTML = '<div class="text-center empty-state"><i class="fas fa-info-circle mr-2 fa-3x mb-3"></i><p class="lead">Tidak ada mahasiswa bimbingan.</p></div>';
    }
});
</script>
@endsection