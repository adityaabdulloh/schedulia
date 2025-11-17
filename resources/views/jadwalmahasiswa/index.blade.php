@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-color: #4e73df;
        --secondary-color: #1cc88a;
        --accent-color: #36b9cc;
        --text-color: #5a5c69;
        --text-light: #858796;
        --bg-light: #f8f9fc;
        --bg-dark: #e4e6ef;
        --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        --border-radius: 0.75rem;

        /* Table specific colors */
        --table-header-bg: var(--primary-color);
        --table-header-text: #fff;
        --table-row-hover-bg: #e9ecef;

        /* Day specific colors (subtle) */
        --day-senin-bg: #e6f7ff; /* Light Blue */
        --day-selasa-bg: #e6ffe6; /* Light Green */
        --day-rabu-bg: #fff7e6; /* Light Yellow */
        --day-kamis-bg: #ffe6f7; /* Light Pink */
        --day-jumat-bg: #f0e6ff; /* Light Purple */
        --day-sabtu-bg: #fff0e6; /* Light Orange */
        --day-minggu-bg: #ffe6e6; /* Light Red */
    }

    body {
        background-color: var(--bg-light);
        font-family: 'Nunito', sans-serif;
    }

    .card {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
    }

    .card-header {
        background-color: #fff;
        border-bottom: 1px solid var(--bg-dark);
        padding: 1.5rem;
        border-top-left-radius: var(--border-radius);
        border-top-right-radius: var(--border-radius);
    }

    .card-header h3 {
        color: var(--text-color);
        font-weight: 700;
        margin-bottom: 0;
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

    .form-control {
        border-radius: 0.5rem;
        border-color: var(--bg-dark);
        padding: 0.75rem 1rem;
    }
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background-color: var(--table-header-bg);
        color: var(--table-header-text);
        font-weight: 600;
        vertical-align: middle;
        border-bottom: none;
        padding: 1rem;
    }

    .table tbody tr {
        transition: background-color 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: var(--table-row-hover-bg);
    }

    .table tbody td {
        vertical-align: middle;
        color: var(--text-color);
        padding: 1rem;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: var(--bg-light);
    }

    /* Day specific row styling */
    .day-Senin { background-color: var(--day-senin-bg); }
    .day-Selasa { background-color: var(--day-selasa-bg); }
    .day-Rabu { background-color: var(--day-rabu-bg); }
    .day-Kamis { background-color: var(--day-kamis-bg); }
    .day-Jumat { background-color: var(--day-jumat-bg); }
    .day-Sabtu { background-color: var(--day-sabtu-bg); }
    .day-Minggu { background-color: var(--day-minggu-bg); }

    .sks-badge {
        display: inline-block;
        padding: 0.3em 0.6em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.375rem;
        color: #fff;
        background-color: var(--accent-color);
        margin-left: 0.5rem;
    }

    .pagination .page-item .page-link {
        color: var(--primary-color);
        border: 1px solid var(--bg-dark);
        border-radius: 0.5rem;
        margin: 0 0.25rem;
        transition: all 0.2s ease;
    }
    .pagination .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: #fff;
    }
    .pagination .page-item.disabled .page-link {
        color: var(--text-light);
    }
    .pagination .page-item .page-link:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: #fff;
    }

    .empty-state {
        padding: 3rem;
        text-align: center;
        color: var(--text-light);
    }
    .empty-state i {
        font-size: 3rem;
        color: var(--bg-dark);
        margin-bottom: 1rem;
    }
</style>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h3 class="mb-2 mb-md-0"><i class="fas fa-calendar-alt me-2"></i>Jadwal Kuliah Mahasiswa</h3>
                    <div class="d-flex align-items-center flex-wrap">
                        
                        <a href="{{ route('jadwalmahasiswa.exportPDF', ['search' => request('search'), 'day_filter' => request('day_filter')]) }}" class="btn btn-success mb-2 mb-md-0"><i class="fas fa-file-pdf me-2"></i>Ekspor PDF</a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-calendar-day me-2"></i>Hari</th>
                                    <th><i class="fas fa-clock me-2"></i>Jam</th>
                                    <th><i class="fas fa-book me-2"></i>Mata Kuliah</th>
                                    <th><i class="fas fa-chalkboard-teacher me-2"></i>Dosen</th>
                                    <th><i class="fas fa-door-open me-2"></i>Ruang</th>
                                    <th><i class="fas fa-users me-2"></i>Kelas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jadwalKuliah as $j)
                                <tr class="day-{{ $j->hari->nama_hari ?? '' }}">
                                    <td>{{ $j->hari->nama_hari ?? '-' }}</td>
                                    <td>
                                        @if ($j->jam_mulai && $j->jam_selesai)
                                            <span class="badge bg-primary text-white">{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        {{ $j->pengampu->matakuliah->nama ?? '-' }}
                                        @if ($j->pengampu->matakuliah->sks ?? false)
                                            <span class="sks-badge">{{ $j->pengampu->matakuliah->sks }} SKS</span>
                                        @endif
                                    </td>
                                    <td>
                                        @forelse ($j->pengampu->dosen as $dosen)
                                            <span class="badge bg-info text-dark mb-1">{{ $dosen->nama }}</span><br>
                                        @empty
                                            -
                                        @endforelse
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $j->ruang->nama_ruang ?? '-' }}</span></td>
                                    <td><span class="badge bg-warning text-dark">{{ $j->pengampu->kelas->nama_kelas ?? '-' }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="empty-state">
                                        <i class="fas fa-box-open"></i>
                                        <p class="mt-3">Tidak ada jadwal kuliah yang ditemukan untuk kriteria ini.</p>
                                        <p>Coba sesuaikan filter atau pencarian Anda.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="d-flex justify-content-between align-items-center mt-3 px-3">
                        <div>
                            Menampilkan {{ $jadwalKuliah->firstItem() }} - {{ $jadwalKuliah->lastItem() }} 
                            dari {{ $jadwalKuliah->total() }} data
                        </div>
                        <div>
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @if ($jadwalKuliah->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">Previous</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $jadwalKuliah->previousPageUrl() }}">Previous</a>
                                    </li>
                                @endif

                                {{-- Nomor Halaman --}}
                                @for ($i = 1; $i <= $jadwalKuliah->lastPage(); $i++)
                                    <li class="page-item {{ $jadwalKuliah->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $jadwalKuliah->appends(['search' => request('search'), 'day_filter' => request('day_filter')])->url($i) }}">
                                            {{ $i }}
                                        </a>
                                    </li>
                                @endfor

                                {{-- Tombol Next --}}
                                @if ($jadwalKuliah->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $jadwalKuliah->nextPageUrl() }}">Next</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">Next</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection