@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mahasiswa yang Mengambil Mata Kuliah Anda</h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <!-- Widget Daftar Mahasiswa -->
            <div class="widget-card primary-border">
                <div class="widget-header">
                    <h2 class="widget-title"><i class="fas fa-users mr-2"></i>Daftar Mahasiswa</h2>
                </div>
                <div class="widget-body">
                    @if(isset($pengambilanMk) && $pengambilanMk->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>NPM</th>
                                        <th>Program Studi</th>
                                        <th>Mata Kuliah</th>
                                        <th>SKS</th>
                                        <th>Semester</th>
                                        <th>Kelas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengambilanMk as $index => $pengambilan)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $pengambilan->mahasiswa->nama }}</td>
                                            <td>{{ $pengambilan->mahasiswa->npm }}</td>
                                            <td>{{ $pengambilan->mahasiswa->prodi->nama_prodi }}</td>
                                            <td>{{ $pengambilan->matakuliah->nama }}</td>
                                            <td>{{ $pengambilan->matakuliah->sks }}</td>
                                            <td>{{ $pengambilan->matakuliah->semester }}</td>
                                            <td>{{ $pengambilan->mahasiswa->kelas->nama_kelas }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center empty-state">
                            <i class="fas fa-info-circle mr-2 fa-3x mb-3"></i>
                            <p class="lead">Tidak ada mahasiswa yang mengambil mata kuliah Anda saat ini.</p>
                            <p class="text-muted">Data akan muncul di sini setelah mahasiswa melakukan pengambilan mata kuliah dan disetujui.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection