@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Mahasiswa</h1>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Daftar Mahasiswa</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Mahasiswa Mengambil MK</div>
                            <p class="mt-2 text-gray-600">Lihat daftar mahasiswa yang mengambil mata kuliah yang Anda ampu.</p>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-check-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="{{ route('dosen.pengambilan-mk.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Kehadiran</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Absensi</div>
                            <p class="mt-2 text-gray-600">Kelola dan rekam kehadiran mahasiswa untuk setiap sesi perkuliahan.</p>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-card-checklist fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="{{ route('dosen.absensi.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
