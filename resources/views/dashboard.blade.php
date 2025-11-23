@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <h1 class="h3 mb-0">Selamat Datang Kembali, {{ Auth::user()->name }}!</h1>
        <p class="mb-0">Semoga harimu menyenangkan dalam mengelola sistem penjadwalan.</p>
    </div>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Admin</h1>
    </div>

    <!-- Stat Cards Row -->
    <div class="row">
        <!-- Total Dosen Card -->
        <div class="col-xl-3 col-md-6 mb-4 stat-card">
            <a href="{{ url('/dosen') }}" class="text-decoration-none">
                <div class="card text-white shadow" style="background: linear-gradient(45deg, #4e73df, #7a9bff); border-radius: 15px; transition: all 0.3s ease;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase fw-bold small">Total Dosen</div>
                            <div class="h3 mb-0 fw-bold">{{ $dosenCount }}</div>
                        </div>
                        <i class="bi bi-person-badge-fill" style="font-size: 3rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Total Mahasiswa Card -->
        <div class="col-xl-3 col-md-6 mb-4 stat-card">
            <a href="{{ url('/mahasiswa') }}" class="text-decoration-none">
                <div class="card text-white shadow" style="background: linear-gradient(45deg, #1cc88a, #69e0b3); border-radius: 15px; transition: all 0.3s ease;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase fw-bold small">Total Mahasiswa</div>
                            <div class="h3 mb-0 fw-bold">{{ $mahasiswaCount }}</div>
                        </div>
                        <i class="bi bi-people-fill" style="font-size: 3rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Total Mata Kuliah Card -->
        <div class="col-xl-3 col-md-6 mb-4 stat-card">
            <a href="{{ url('/matakuliah') }}" class="text-decoration-none">
                <div class="card text-white shadow" style="background: linear-gradient(45deg, #36b9cc, #7ed6e6); border-radius: 15px; transition: all 0.3s ease;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase fw-bold small">Total Mata Kuliah</div>
                            <div class="h3 mb-0 fw-bold">{{ $matakuliahCount }}</div>
                        </div>
                        <i class="bi bi-book-half" style="font-size: 3rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Total User Card -->
        <div class="col-xl-3 col-md-6 mb-4 stat-card">
            <a href="{{ url('/users') }}" class="text-decoration-none">
                <div class="card text-white shadow" style="background: linear-gradient(45deg, #f6c23e, #ffd77a); border-radius: 15px; transition: all 0.3s ease;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase fw-bold small">Total User</div>
                            <div class="h3 mb-0 fw-bold">{{ $userCount }}</div>
                        </div>
                        <i class="bi bi-person-fill-gear" style="font-size: 3rem; opacity: 0.7;"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Quick Actions & Pending Approvals Row -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-white">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-6 mb-3">
                            <a href="{{ route('dosen.create') }}" class="quick-action-btn text-decoration-none">
                                <i class="bi bi-person-plus-fill"></i>
                                <span>Tambah Dosen</span>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6 mb-3">
                            <a href="{{ route('mahasiswa.create') }}" class="quick-action-btn text-decoration-none">
                                <i class="bi bi-person-vcard"></i>
                                <span>Tambah Mahasiswa</span>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6">
                            <a href="{{ route('jadwal.create') }}" class="quick-action-btn text-decoration-none">
                                <i class="bi bi-calendar-plus"></i>
                                <span>Buat Jadwal</span>
                            </a>
                        </div>
                        <div class="col-12 col-sm-6">
                            <a href="{{ route('admin.pengambilanmk.validation.index') }}" class="quick-action-btn text-decoration-none">
                                <i class="bi bi-check2-circle"></i>
                                <span>Validasi KRS</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-white">Persetujuan KRS Tertunda</h6>
                    <a href="{{ route('admin.pengambilanmk.validation.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($pendingKrs->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($pendingKrs as $krs)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $krs->mahasiswa->foto_profil ? asset('storage/foto_profil/' . $krs->mahasiswa->foto_profil) : asset('images/default-profil.svg') }}" alt="Foto Profil" class="img-fluid rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                        <div>
                                            <strong>{{ $krs->mahasiswa->nama }}</strong>
                                            <small class="d-block text-muted">{{ $krs->mahasiswa->prodi->nama_prodi ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.mahasiswa.krs.show', ['mahasiswa' => $krs->mahasiswa_id]) }}" class="btn btn-outline-primary btn-sm">
                                        Validasi <span class="badge bg-danger ms-1">{{ $krs->total_mk }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center p-4">
                            <i class="bi bi-check2-all" style="font-size: 3rem; color: #1cc88a;"></i>
                            <p class="mt-2">Tidak ada persetujuan yang tertunda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-white">Jumlah Mahasiswa per Program Studi</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="prodiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-white">Distribusi Role User</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="userRoleChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
@vite(['resources/css/custom-admin.scss'])
@endpush

@push('scripts')
<script>
    var prodiData = {!! json_encode($prodiData) !!};

    // Bar Chart - Mahasiswa per Prodi
    var ctxProdi = document.getElementById('prodiChart').getContext('2d');
    var prodiChart = new Chart(ctxProdi, {
        type: 'bar',
        data: {
            labels: {!! json_encode($prodiLabels) !!},
            datasets: [{
                label: 'Jumlah Mahasiswa',
                data: {!! json_encode($prodiMahasiswaCounts) !!},
                backgroundColor: ['rgba(78, 115, 223, 0.8)'],
                borderColor: ['rgba(78, 115, 223, 1)'],
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
                        stepSize: 1
                    }
                }
            },
            onClick: function(evt) {
                var activePoints = prodiChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
                if (activePoints.length > 0) {
                    var firstPoint = activePoints[0];
                    var label = prodiChart.data.labels[firstPoint.index];
                    var prodi = prodiData.find(p => p.nama_prodi === label);
                    if(prodi) {
                        window.location.href = '{{ url("/prodi") }}/' + prodi.id;
                    }
                }
            }
        }
    });

    // Doughnut Chart - User Roles
    var ctxRoles = document.getElementById('userRoleChart').getContext('2d');
    var userRoleChart = new Chart(ctxRoles, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($userRoleLabels) !!},
            datasets: [{
                data: {!! json_encode($userRoleCounts) !!},
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            },
            onClick: function(evt) {
                var activePoints = userRoleChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
                if (activePoints.length > 0) {
                    // Redirect to the users page. A more advanced version could filter by role.
                    window.location.href = '{{ url("/users") }}';
                }
            }
        }
    });
</script>
@endpush