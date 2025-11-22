@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Buat Pengumuman Baru</h4>
                </div>

                <div class="card-body">
                    <div class="alert alert-info">
                        <h5 class="alert-heading">Detail Jadwal</h5>
                        <p>
                            <strong><i class="bi bi-book-fill"></i> Mata Kuliah:</strong> {{ $jadwalKuliah->pengampu->matakuliah->nama }}<br>
                            <strong><i class="bi bi-people-fill"></i> Kelas:</strong> {{ $jadwalKuliah->pengampu->kelas->nama_kelas }}<br>
                            <strong><i class="bi bi-calendar-day-fill"></i> Hari:</strong> {{ $jadwalKuliah->hari->nama_hari }}<br>
                            <strong><i class="bi bi-clock-fill"></i> Jam:</strong> {{ $jadwalKuliah->jam_mulai }} - {{ $jadwalKuliah->jam_selesai }}<br>
                            <strong><i class="bi bi-geo-alt-fill"></i> Ruang:</strong> {{ $jadwalKuliah->ruang->nama_ruang }}
                        </p>
                    </div>
                    <hr>

                    <form action="{{ route('pengumuman.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="jadwal_kuliah_id" value="{{ $jadwalKuliah->id }}">

                        <div class="form-group mb-3">
                            <label for="tipe" class="form-label"><strong>Jenis Pengumuman</strong></label>
                            <select class="form-select" id="tipe" name="tipe" required>
                                <option value="informasi">Informasi</option>
                                <option value="perubahan">Perubahan Jadwal</option>
                                <option value="pembatalan">Pembatalan Kelas</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="pesan" class="form-label"><strong>Pesan</strong></label>
                            <textarea class="form-control @error('pesan') is-invalid @enderror" id="pesan" name="pesan" rows="4" placeholder="Contoh: Kelas hari ini dibatalkan karena ada rapat mendadak."></textarea>
                            @error('pesan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('jadwaldosen.index') }}" class="btn btn-secondary me-2">
                                <i class="bi bi-x-circle-fill"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send-fill"></i> Kirim Pengumuman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection