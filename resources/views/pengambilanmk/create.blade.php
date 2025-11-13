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
    .btn-danger {
        background-color: #e74a3b;
        border-color: #e74a3b;
        transition: all 0.3s ease;
    }
    .btn-danger:hover {
        background-color: #cc3b2d;
        border-color: #cc3b2d;
        transform: translateY(-2px);
    }
    .btn-secondary {
        background-color: #858796;
        border-color: #858796;
        transition: all 0.3s ease;
    }
    .btn-secondary:hover {
        background-color: #6d6e7d;
        border-color: #6d6e7d;
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
                    <h3 class="mb-2 mb-md-0"><i class="fas fa-plus-circle me-2"></i>Ambil Mata Kuliah</h3>
                    <a href="{{ route('krs.index') }}" class="btn btn-secondary mb-2 mb-md-0">Lihat KRS Saya</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-book me-2"></i>Nama Mata Kuliah</th>
                                    <th><i class="fas fa-chalkboard-teacher me-2"></i>Dosen Pengampu</th>
                                    <th><i class="fas fa-university me-2"></i>Kelas</th>
                                    <th><i class="fas fa-credit-card me-2"></i>SKS</th>
                                    <th><i class="fas fa-calendar-alt me-2"></i>Semester</th>
                                    <th class="text-center"><i class="fas fa-cogs me-2"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengampuTersedia as $pengampu)
                                <tr>
                                    <td>
                                        <strong>{{ $pengampu->matakuliah->nama }}</strong><br>
                                        <small class="text-muted">{{ $pengampu->matakuliah->kode_mk }}</small>
                                    </td>
                                    <td>{!! $pengampu->dosen->pluck('nama')->join('<br>') !!}</td>
                                    <td>{{ $pengampu->kelas->nama_kelas }}</td>
                                    <td>{{ $pengampu->matakuliah->sks }}</td>
                                    <td>{{ $pengampu->matakuliah->semester }}</td>
                                    <td class="text-center">
                                        @if(in_array($pengampu->id, $diambilPengampuIds))
                                            {{-- Form untuk Lepas Mata Kuliah --}}
                                            <form action="{{ route('pengambilan-mk.destroy', $pengampu->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin melepas mata kuliah ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Lepas</button>
                                            </form>
                                        @else
                                            {{-- Form untuk Ambil Mata Kuliah --}}
                                            <form action="{{ route('pengambilan-mk.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="pengampu_id" value="{{ $pengampu->id }}">
                                                <button type="submit" class="btn btn-primary btn-sm">Ambil</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="empty-state">
                                        <i class="fas fa-box-open"></i>
                                        <p class="mt-3">Tidak ada kelas yang tersedia untuk program studi dan semester Anda saat ini.</p>
                                        <p>Silakan hubungi bagian akademik untuk informasi lebih lanjut.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection