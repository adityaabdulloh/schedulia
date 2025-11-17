@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Pilih Mata Kuliah untuk Absensi</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Mata Kuliah yang Diampu</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Mata Kuliah</th>
                            <th>Kelas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengampuCourses as $pengampu)
                        <tr>
                            <td>{{ $pengampu->matakuliah->nama }}</td>
                            <td>{{ $pengampu->kelas->nama_kelas }}</td>
                            <td>
                                {{-- This button will lead to the attendance taking page for this specific course --}}
                                <a href="{{ route('dosen.absensi.take', $pengampu->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-clipboard-check"></i> Ambil Absensi
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada mata kuliah yang diampu.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
