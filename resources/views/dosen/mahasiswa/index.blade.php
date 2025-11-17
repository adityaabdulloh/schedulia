@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Daftar Mahasiswa</div>

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <form action="{{ route('dosen.mahasiswa.index') }}" method="GET" class="form-inline">
                            <div class="form-group mr-2">
                                <label for="matakuliah_id" class="mr-2">Filter berdasarkan Mata Kuliah:</label>
                                <select name="matakuliah_id" id="matakuliah_id" class="form-control" onchange="this.form.submit()">
                                    <option value="">Semua Mata Kuliah</option>
                                    @foreach ($mataKuliahDosen as $mk)
                                        @if ($mk->nama) {{-- Add this check --}}
                                            <option value="{{ $mk->id }}" {{ request('matakuliah_id') == $mk->id ? 'selected' : '' }}>
                                                {{ $mk->nama }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </form>
                        <a href="{{ route('dosen.absensi.index') }}" class="btn btn-success">Absensi</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Foto Profil</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Prodi</th>
                                    <th>Kelas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($mahasiswa as $mhs)
                                <tr>
                                    <td>
                                        @if ($mhs->foto_profil)
                                            <img src="{{ asset('storage/foto_profil/' . $mhs->foto_profil) }}" alt="Foto Profil" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/default-profil.svg') }}" alt="Foto Profil Default" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                        @endif
                                    </td>
                                    <td>{{ $mhs->nim }}</td>
                                    <td>{{ $mhs->nama }}</td>
                                    <td>{{ $mhs->prodi->nama_prodi ?? 'N/A' }}</td>
                                    <td>{{ $mhs->kelas->nama_kelas ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('dosen.mahasiswa.absensi', $mhs->id) }}" class="btn btn-warning btn-sm">Absensi</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data mahasiswa.</td>
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
