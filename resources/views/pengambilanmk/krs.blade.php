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
        --table-header-bg: var(--primary-color);
        --table-header-text: #fff;
        --table-row-hover-bg: #e9ecef;
    }

    body {
        background-color: var(--bg-light);
        font-family: 'Nunito', sans-serif;
        color: var(--text-color);
    }

    .card {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        margin-bottom: 2rem;
    }

    .card-header {
        background-color: #fff;
        border-bottom: 1px solid var(--bg-dark);
        padding: 1.5rem;
        border-top-left-radius: var(--border-radius);
        border-top-right-radius: var(--border-radius);
    }

    .card-header h3, .card-header h4 {
        color: var(--text-color);
        font-weight: 700;
        margin-bottom: 0;
    }

    .student-info-card .card-body {
        padding: 2rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .info-item {
        background-color: var(--bg-light);
        padding: 1rem;
        border-radius: 0.5rem;
        border-left: 4px solid var(--primary-color);
    }

    .info-item strong {
        display: block;
        color: var(--text-light);
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
        text-transform: uppercase;
    }

    .info-item span {
        font-weight: 600;
        font-size: 1rem;
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

    .table thead th {
        background-color: var(--table-header-bg);
        color: var(--table-header-text);
        font-weight: 600;
        vertical-align: middle;
        border-bottom: none;
        padding: 1rem;
    }

    .table tbody tr:hover {
        background-color: var(--table-row-hover-bg);
    }

    .table tbody td {
        vertical-align: middle;
        padding: 1rem;
    }

    .table tfoot th {
        font-weight: 700;
    }

    .semester-header {
        padding: 0.75rem 1.5rem;
        background-color: var(--bg-dark);
        color: var(--primary-color);
        font-weight: 700;
        border-radius: 0.5rem;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .empty-state {
        padding: 3rem;
        text-align: center;
        color: var(--text-light);
    }
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
</style>

<div class="container-fluid py-4">

    <!-- Student Info -->
    <div class="card student-info-card">
        <div class="card-header">
            <h4><i class="bi bi-person-badge me-2"></i>Informasi Mahasiswa</h4>
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item">
                    <strong>Nama Mahasiswa</strong>
                    <span>{{ $mahasiswa->nama }}</span>
                </div>
                <div class="info-item">
                    <strong>NIM</strong>
                    <span>{{ $mahasiswa->nim }}</span>
                </div>
                <div class="info-item">
                    <strong>Program Studi</strong>
                    <span>{{ $mahasiswa->prodi->nama_prodi }}</span>
                </div>
                <div class="info-item">
                    <strong>Kelas</strong>
                    <span>{{ $mahasiswa->kelas->nama_kelas ?? 'Belum ada kelas' }}</span>
                </div>
                <div class="info-item">
                    <strong>Semester Akademik</strong>
                    <span>{{ $mahasiswa->semester }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- KRS Card -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h3 class="mb-2 mb-md-0"><i class="bi bi-card-list me-2"></i>Kartu Rencana Studi (KRS)</h3>
            <a href="{{ route('krs.export-pdf') }}" class="btn btn-success"><i class="fas fa-file-pdf me-2"></i>Export ke PDF</a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @forelse($pengambilanMKs->sortKeys() as $semester => $items)
                <h5 class="semester-header">Semester {{ $semester }}</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Kode MK</th>
                                <th>Nama Mata Kuliah</th>
                                <th>SKS</th>
                                <th>Kelas</th>
                                <th>Dosen Pengampu</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $pengambilan)
                            <tr>
                                <td>{{ $pengambilan->matakuliah->kode_mk }}</td>
                                <td>{{ $pengambilan->matakuliah->nama }}</td>
                                <td>{{ $pengambilan->matakuliah->sks }}</td>
                                <td>{{ $pengambilan->pengampu->kelas->nama_kelas ?? '-' }}</td>
                                <td>
                                    @if($pengambilan->pengampu->dosen->isNotEmpty())
                                        {{ $pengambilan->pengampu->dosen->pluck('nama')->join(', ') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($pengambilan->status == 'approved')
                                        <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>Disetujui</span>
                                    @elseif($pengambilan->status == 'pending')
                                        <span class="badge bg-warning text-dark"><i class="bi bi-clock-fill me-1"></i>Menunggu</span>
                                    @else
                                        <span class="badge bg-danger"><i class="bi bi-x-circle-fill me-1"></i>Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <p class="mt-3">Anda belum mengambil mata kuliah apapun.</p>
                    <p>Silakan ke halaman <a href="{{ route('pengambilan-mk.create') }}">Pengambilan MK</a> untuk memilih mata kuliah.</p>
                </div>
            @endforelse

            @if($pengambilanMKs->isNotEmpty())
            <div class="table-responsive">
                <table class="table">
                    <tfoot>
                        <tr>
                            <th class="text-end" colspan="2">Total SKS Diambil:</th>
                            <th style="width:80px;">{{ $totalSks }}</th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection