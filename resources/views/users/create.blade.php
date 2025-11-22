@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah User Baru</h5>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
            </select>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div id="mahasiswa-fields" style="display: {{ old('role') == 'mahasiswa' ? 'block' : 'none' }};">
            <div class="mb-3">
                <label for="nim" class="form-label">NIM</label>
                <input type="text" class="form-control @error('nim') is-invalid @enderror" id="nim" name="nim" value="{{ old('nim') }}">
                @error('nim')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="prodi_id" class="form-label">Program Studi</label>
                <select class="form-select @error('prodi_id') is-invalid @enderror" id="prodi_id" name="prodi_id">
                    <option value="">Pilih Prodi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option>
                    @endforeach
                </select>
                @error('prodi_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="semester" class="form-label">Semester</label>
                <input type="number" class="form-control @error('semester') is-invalid @enderror" id="semester" name="semester" value="{{ old('semester') }}" min="1">
                @error('semester')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="kelas_id" class="form-label">Kelas</label>
                <select class="form-select @error('kelas_id') is-invalid @enderror" id="kelas_id" name="kelas_id">
                    <option value="">Pilih Kelas</option>
                    @foreach($kelases as $kelas)
                        <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
                @error('kelas_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div id="dosen-fields" style="display: {{ old('role') == 'dosen' ? 'block' : 'none' }};">
            <div class="mb-3">
                <label for="nip" class="form-label">NIP</label>
                <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip" value="{{ old('nip') }}">
                @error('nip')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="prodi_id_dosen" class="form-label">Program Studi</label>
                <select class="form-select @error('prodi_id_dosen') is-invalid @enderror" id="prodi_id_dosen" name="prodi_id_dosen">
                    <option value="">Pilih Prodi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}" {{ old('prodi_id_dosen') == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option>
                    @endforeach
                </select>
                @error('prodi_id_dosen')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                        <div class="d-grid mt-2">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('role').addEventListener('change', function () {
        var mahasiswaFields = document.getElementById('mahasiswa-fields');
        var dosenFields = document.getElementById('dosen-fields');

        if (this.value === 'mahasiswa') {
            mahasiswaFields.style.display = 'block';
            dosenFields.style.display = 'none';
        } else if (this.value === 'dosen') {
            mahasiswaFields.style.display = 'none';
            dosenFields.style.display = 'block';
        } else {
            mahasiswaFields.style.display = 'none';
            dosenFields.style.display = 'none';
        }
    });

    // Initial check on page load
    document.addEventListener('DOMContentLoaded', function() {
        var roleSelect = document.getElementById('role');
        var mahasiswaFields = document.getElementById('mahasiswa-fields');
        var dosenFields = document.getElementById('dosen-fields');

        if (roleSelect.value === 'mahasiswa') {
            mahasiswaFields.style.display = 'block';
            dosenFields.style.display = 'none';
        } else if (roleSelect.value === 'dosen') {
            mahasiswaFields.style.display = 'none';
            dosenFields.style.display = 'block';
        } else {
            mahasiswaFields.style.display = 'none';
            dosenFields.style.display = 'none';
        }
    });

    document.getElementById('prodi_id').addEventListener('change', function() {
        var prodiName = this.options[this.selectedIndex].text;
        var kelasSelect = document.getElementById('kelas_id');
        var kelases = {!! json_encode($kelases->toArray()) !!};
        var oldKelasId = '{{ old('kelas_id') }}';

        // Clear existing options
        kelasSelect.innerHTML = '<option value="">Pilih Kelas</option>';

        if (prodiName) {
            var prefix = '';
            if (prodiName === 'Teknik Informatika') {
                prefix = 'TI';
            } else if (prodiName === 'Sistem Informasi') {
                prefix = 'SI';
            } else if (prodiName === 'Pendidikan Teknologi Informasi') {
                prefix = 'PTI';
            }

            if (prefix) {
                kelases.forEach(function(kelas) {
                    if (kelas.nama_kelas.startsWith(prefix)) {
                        var option = document.createElement('option');
                        option.value = kelas.id;
                        option.text = kelas.nama_kelas;
                        if (oldKelasId == kelas.id) {
                            option.selected = true;
                        }
                        kelasSelect.add(option);
                    }
                });
            }
        }
    });

    // Trigger change event on page load if prodi is already selected (e.g., on validation error)
    document.addEventListener('DOMContentLoaded', function() {
        var prodiSelect = document.getElementById('prodi_id');
        if (prodiSelect.value) {
            prodiSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush