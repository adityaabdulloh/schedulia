@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Jadwal Kuliah</h5>
                    <a href="{{ route('jadwal.index') }}" class="btn btn-light btn-sm">
                        Kembali
                    </a>
                </div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('jadwal.update', $jadwal->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="pengampu_id" class="form-label">Pengampu</label>
                            <select name="pengampu_id" id="pengampu_id" class="form-select" required>
                                @foreach($pengampu as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $jadwal->pengampu_id ? 'selected' : '' }}>
                                        {{ $item->matakuliah->nama }} - {{ $item->dosen->pluck('nama')->implode(', ') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="ruang_id" class="form-label">Ruang</label>
                            <select name="ruang_id" id="ruang_id" class="form-select" required>
                                @foreach($ruang as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $jadwal->ruang_id ? 'selected' : '' }}>
                                        {{ $item->nama_ruang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="hari_id" class="form-label">Hari</label>
                            <select name="hari_id" id="hari_id" class="form-select" required>
                                @foreach($hari as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $jadwal->hari_id ? 'selected' : '' }}>
                                        {{ $item->nama_hari }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="jam_mulai" class="form-label">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" value="{{ old('jam_mulai', \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="kelas_id" class="form-label">Kelas</label>
                            <select name="kelas_id" id="kelas_id" class="form-select" required>
                                @foreach($kelas as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $jadwal->kelas_id ? 'selected' : '' }}>
                                        {{ $item->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="tahun_akademik" value="{{ $jadwal->tahun_akademik }}">

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Jadwal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
