@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Absensi Mata Kuliah: {{ $pengampu->matakuliah->nama_matakuliah }}</h3>
                    <p>Kelas: {{ $pengampu->kelas->nama_kelas }} | Prodi: {{ $pengampu->prodi->nama_prodi }}</p>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('dosen.absensi.store', $pengampu->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="pertemuan" class="form-label">Pertemuan Ke:</label>
                            <input type="number" name="pertemuan" id="pertemuan" class="form-control" required min="1" max="16">
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>NIM</th>
                                        <th>Nama Mahasiswa</th>
                                        <th class="text-center">Status Kehadiran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($mahasiswa as $m)
                                    <tr>
                                        <td>{{ $m->mahasiswa->nim }}</td>
                                        <td>{{ $m->mahasiswa->nama }}</td>
                                        <td>
                                            <input type="hidden" name="mahasiswa_id[]" value="{{ $m->mahasiswa->id }}">
                                            <div class="d-flex justify-content-around">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="status[{{ $m->mahasiswa->id }}]" id="hadir_{{ $m->mahasiswa->id }}" value="hadir" checked>
                                                    <label class="form-check-label" for="hadir_{{ $m->mahasiswa->id }}">Hadir</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="status[{{ $m->mahasiswa->id }}]" id="izin_{{ $m->mahasiswa->id }}" value="izin">
                                                    <label class="form-check-label" for="izin_{{ $m->mahasiswa->id }}">Izin</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="status[{{ $m->mahasiswa->id }}]" id="sakit_{{ $m->mahasiswa->id }}" value="sakit">
                                                    <label class="form-check-label" for="sakit_{{ $m.mahasiswa->id }}">Sakit</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="status[{{ $m->mahasiswa->id }}]" id="alpha_{{ $m->mahasiswa->id }}" value="alpha">
                                                    <label class="form-check-label" for="alpha_{{ $m->mahasiswa->id }}">Alpha</label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada mahasiswa yang mengambil mata kuliah ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan Absensi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
