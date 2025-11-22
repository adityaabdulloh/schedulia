@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary">
                    <div class="d-flex align-items-center">
                        <h3 class="me-3 text-white">Pengumuman</h3>
                        <a href="{{ route('jadwaldosen.index') }}" class="btn btn-success btn-sm">Buat Pengumuman</a>
                    </div>
                    <form action="{{ route('pengumuman.index') }}" method="GET" class="d-flex">
                        <input type="text" name="matakuliah" class="form-control" placeholder="Mata Kuliah..." value="{{ request('matakuliah') }}">
                        
                        <select name="tipe" class="form-control ms-2">
                            <option value="">Semua Tipe</option>
                            <option value="perubahan" {{ request('tipe') == 'perubahan' ? 'selected' : '' }}>Perubahan</option>
                            <option value="pembatalan" {{ request('tipe') == 'pembatalan' ? 'selected' : '' }}>Pembatalan</option>
                            <option value="informasi" {{ request('tipe') == 'informasi' ? 'selected' : '' }}>Informasi</option>
                        </select>

                        <button type="submit" class="btn btn-primary ms-2">Search</button>
                    </form>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th class="fw-bold" style="color: black; background-color: #d9edfc;">Mata Kuliah</th>
                                    <th class="fw-bold" style="color: black; background-color: #d9edfc;">Tipe</th>
                                    <th class="fw-bold" style="color: black; background-color: #d9edfc;">Pesan</th>
                                    <th class="fw-bold" style="color: black; background-color: #d9edfc;">Waktu</th>
                                    <th class="fw-bold" style="color: black; background-color: #d9edfc;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengumumans as $pengumuman)
                                <tr>
                                    <td>{{ $pengumuman->jadwalKuliah->pengampu->matakuliah->nama ?? '-' }}</td>
                                    <td>{{ $pengumuman->tipe }}</td>
                                    <td>{{ $pengumuman->pesan }}</td>
                                    <td>{{ $pengumuman->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}</td>
                                    <td>
                                        <a href="{{ route('pengumuman.show', $pengumuman->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data pengumuman yang tersedia.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Menampilkan {{ $pengumumans->firstItem() }} - {{ $pengumumans->lastItem() }} 
                            dari {{ $pengumumans->total() }} data
                        </div>
                        <div>
                            {{ $pengumumans->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
