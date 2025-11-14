@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Detail Pengumuman</h4>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <strong>Mata Kuliah:</strong> {{ $pengumuman->jadwalKuliah->pengampu->matakuliah->nama ?? '-' }}
                    </div>
                    <div class="mb-3">
                        <strong>Dosen:</strong> {{ $pengumuman->dosen->nama ?? '-' }}
                    </div>
                    <div class="mb-3">
                        <strong>Tipe:</strong> {{ $pengumuman->tipe }}
                    </div>
                    <div class="mb-3">
                        <strong>Pesan:</strong>
                        <p class="card-text">{{ $pengumuman->pesan }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Waktu:</strong> {{ $pengumuman->created_at->format('d M Y, H:i') }}
                    </div>

                    <hr>

                    <h5>Detail Jadwal Terkait</h5>
                    <p>
                        <strong>Kelas:</strong> {{ $pengumuman->jadwalKuliah->pengampu->kelas->nama_kelas ?? '-' }}<br>
                        <strong>Hari:</strong> {{ $pengumuman->jadwalKuliah->hari->nama_hari ?? '-' }}<br>
                        <strong>Jam:</strong> {{ $pengumuman->jadwalKuliah->jam->jam_mulai ?? '-' }} - {{ $pengumuman->jadwalKuliah->jam->jam_selesai ?? '-' }}<br>
                        <strong>Ruang:</strong> {{ $pengumuman->jadwalKuliah->ruang->nama_ruang ?? '-' }}
                    </p>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('pengumuman.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left-circle-fill"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
