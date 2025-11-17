@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Kartu Rencana Studi (KRS) - {{ $mahasiswa->nama }}</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Mata Kuliah yang Diambil</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Kode MK</th>
                            <th>Nama Mata Kuliah</th>
                            <th>SKS</th>
                            <th>Dosen Pengampu</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($krs as $item)
                        <tr>
                            <td>{{ $item->pengampu->matakuliah->kode_mk }}</td>
                            <td>{{ $item->pengampu->matakuliah->nama }}</td>
                            <td>{{ $item->pengampu->matakuliah->sks }}</td>
                            <td>{{ $item->pengampu->dosen->nama ?? 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $item->status == 'Disetujui' ? 'success' : ($item->status == 'Ditolak' ? 'danger' : 'warning') }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada mata kuliah yang diambil.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <a href="{{ route('dosen.mahasiswa.index') }}" class="btn btn-secondary mt-3">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Mahasiswa
            </a>
        </div>
    </div>
</div>
@endsection
