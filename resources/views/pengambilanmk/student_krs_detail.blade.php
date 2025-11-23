@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white d-flex flex-column flex-md-row justify-content-md-between align-items-md-center">
                    <h3 class="mb-2 mb-md-0">KRS Mahasiswa: {{ $mahasiswa->nama }} ({{ $mahasiswa->nim }})</h3>
                    <div class="d-flex flex-column flex-md-row">
                        <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary w-100 w-md-auto mb-2 mb-md-0">Kembali ke Daftar Mahasiswa</a>
                        <form action="{{ route('admin.pengambilanmk.approveAll', $mahasiswa->id) }}" method="POST" class="w-100 w-md-auto ms-md-2" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui semua mata kuliah yang tertunda untuk mahasiswa ini?');">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-all"></i> Setujui Semua Mata Kuliah
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Kode MK</th>
                                    <th>Nama Mata Kuliah</th>
                                    <th>SKS</th>
                                    <th>Semester</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengambilanMKs as $pengambilanMK)
                                <tr>
                                    <td>{{ $pengambilanMK->matakuliah->kode_mk }}</td>
                                    <td>{{ $pengambilanMK->matakuliah->nama }}</td>
                                    <td>{{ $pengambilanMK->matakuliah->sks }}</td>
                                    <td>{{ $pengambilanMK->matakuliah->semester }}</td>
                                    <td>
                                        @if($pengambilanMK->status == 'approved')
                                            <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i><strong>{{ ucfirst('Disetujui') }}</strong></span>
                                        @elseif($pengambilanMK->status == 'pending')
                                            <span class="badge bg-warning text-dark"><i class="bi bi-clock-fill me-1"></i><strong>{{ ucfirst('Menunggu Persetujuan') }}</strong></span>
                                        @else
                                            <span class="badge bg-danger"><i class="bi bi-x-circle-fill me-1"></i><strong>{{ ucfirst('Ditolak') }}</strong></span>
                                        @endif
                                    </td>
                                    <td class="d-flex flex-column flex-md-row align-items-center justify-content-md-start">
                                        @if($pengambilanMK->status == 'pending')
                                            <form action="{{ route('admin.pengambilanmk.validation.updateStatus', $pengambilanMK->id) }}" method="POST" class="w-100 w-md-auto mb-2 mb-md-0 me-md-2">
                                                @csrf
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-success btn-icon-split btn-sm w-100">
                                                    <span class="icon text-white-50">
                                                        <i class="bi bi-check-lg"></i>
                                                    </span>
                                                    <span class="text">Setujui</span>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.pengambilanmk.validation.updateStatus', $pengambilanMK->id) }}" method="POST" class="w-100 w-md-auto">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-danger btn-icon-split btn-sm w-100">
                                                    <span class="icon text-white-50">
                                                        <i class="bi bi-x-lg"></i>
                                                    </span>
                                                    <span class="text">Tolak</span>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">Sudah divalidasi</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Mahasiswa ini belum mengambil mata kuliah apapun.</td>
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