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
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Mata Kuliah</th>
                                        <th>Prodi</th>
                                        <th>Kelas</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengampu as $p)
                                        <tr>
                                            <td>{{ $p->matakuliah->nama_matakuliah }}</td>
                                            <td>{{ $p->prodi->nama_prodi }}</td>
                                            <td>{{ $p->kelas->nama_kelas }}</td>
                                            <td>
                                                <a href="{{ route('dosen.absensi.show', $p->id) }}" class="btn btn-primary">Pilih</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
