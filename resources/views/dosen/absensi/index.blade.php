@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="float-left">Pilih Mata Kuliah untuk Absensi</h3>
                </div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($pengampu->isEmpty())
                        <div class="alert alert-info">
                            Anda tidak mengampu mata kuliah apapun saat ini.
                        </div>
                    @else
                        <div class="row">
                            @foreach($pengampu as $p)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $p->matakuliah->nama }}</h5>
                                            <p class="card-text">
                                                <strong>Kode:</strong> {{ $p->matakuliah->kode_mk }} <br>
                                                <strong>Prodi:</strong> {{ $p->prodi->nama_prodi }} <br>
                                                <strong>Kelas:</strong> {{ $p->kelas->nama_kelas }}
                                            </p>
                                        </div>
                                        <div class="card-footer bg-transparent border-top-0">
                                            <a href="{{ route('dosen.absensi.show', $p->id) }}" class="btn btn-primary w-100">Pilih Mata Kuliah</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
