@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h3 text-gray-800">Ambil Absensi</h1>
        <a href="{{ route('jadwal.create') }}" class="btn btn-primary">Buat Jadwal Kuliah</a>
    </div>
    <p class="mb-4">
        Mata Kuliah: <strong>{{ $pengampu->matakuliah->nama_matakuliah }}</strong> ({{ $pengampu->kelas->nama_kelas }})
    </p>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Mahasiswa</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('dosen.absensi.store', $pengampu->id) }}" method="POST">
                @csrf
                <div class="form-group row">
                    <label for="pertemuan" class="col-sm-2 col-form-label">Pertemuan Ke-</label>
                    <div class="col-sm-4">
                        <select class="form-control @error('pertemuan') is-invalid @enderror" id="pertemuan" name="pertemuan" required>
                            @for ($i = 1; $i <= 16; $i++)
                                <option value="{{ $i }}" {{ $i == $nextPertemuan ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        @error('pertemuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Jadwal Kuliah</label>
                    <div class="col-sm-10">
                        @if ($selectedJadwalKuliah)
                            <p class="form-control-static">
                                @if ($selectedJadwalKuliah->jam && $selectedJadwalKuliah->hari && $selectedJadwalKuliah->ruang)
                                    <strong>{{ $selectedJadwalKuliah->hari->nama_hari }}</strong>,
                                    {{ \Carbon\Carbon::parse($selectedJadwalKuliah->jam->jam_mulai)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($selectedJadwalKuliah->jam->jam_selesai)->format('H:i') }}
                                    (Ruang: {{ $selectedJadwalKuliah->ruang->nama_ruang }})
                                @else
                                    <span class="text-danger">Informasi jadwal tidak lengkap.</span>
                                @endif
                            </p>
                            <input type="hidden" name="jadwal_kuliah_id" value="{{ $selectedJadwalKuliah->id }}">
                        @else
                            <p class="form-control-static text-danger">Tidak ada jadwal kuliah yang cocok untuk saat ini.</p>
                            <input type="hidden" name="jadwal_kuliah_id" value="">
                        @endif
                        @error('jadwal_kuliah_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Program Studi</th>
                                <th>Status Absensi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mahasiswas as $mahasiswa)
                            <tr>
                                <td class="d-flex align-items-center">
                                    <img src="{{ $mahasiswa->foto_profil ? asset('storage/foto_profil/' . $mahasiswa->foto_profil) : asset('images/default-profil.svg') }}"
                                         alt="Foto Profil" class="img-thumbnail rounded-circle mr-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    {{ $mahasiswa->nim }}
                                </td>
                                <td>{{ $mahasiswa->nama }}</td>
                                <td>{{ $mahasiswa->prodi->nama_prodi ?? 'N/A' }}</td>
                                <td>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="absensi[{{ $mahasiswa->id }}]" id="hadir_{{ $mahasiswa->id }}" value="hadir" checked>
                                        <label class="form-check-label" style="color: black !important;" for="hadir_{{ $mahasiswa->id }}">Hadir</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="absensi[{{ $mahasiswa->id }}]" id="izin_{{ $mahasiswa->id }}" value="izin">
                                        <label class="form-check-label" style="color: black !important;" for="izin_{{ $mahasiswa->id }}">Izin</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="absensi[{{ $mahasiswa->id }}]" id="sakit_{{ $mahasiswa->id }}" value="sakit">
                                        <label class="form-check-label" style="color: black !important;" for="sakit_{{ $mahasiswa->id }}">Sakit</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="absensi[{{ $mahasiswa->id }}]" id="alpha_{{ $mahasiswa->id }}" value="alpha">
                                        <label class="form-check-label" style="color: black !important;" for="alpha_{{ $mahasiswa->id }}">Alpha</label>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada mahasiswa yang mengambil mata kuliah ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-success mt-3">Simpan Absensi</button>
            </form>
        </div>
    </div>
</div>
@endsection

