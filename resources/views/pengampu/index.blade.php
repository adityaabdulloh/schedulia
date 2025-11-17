@extends('layouts.app')

@push('styles')
<link href="{{ asset('css/jadwal.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Data Pengampu</h4>
            <a href="{{ route('pengampu.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div></div> <!-- Placeholder for alignment -->
                <form action="{{ route('pengampu.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Cari berdasarkan Dosen atau Matakuliah..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary">Cari</button>
                </form>
            </div>
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('warning'))
                        <div class="alert alert-warning" role="alert">
                            {{ session('warning') }}
                        </div>
                    @endif

                    @if($pengampus->isEmpty())
                        <div class="alert alert-info text-center" role="alert">
                            Belum ada data pengampu yang tersedia.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="pengampuTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mata Kuliah</th>
                                        <th>Program Studi</th>
                                        <th>Kelas</th>
                                        <th>Dosen Pengampu</th>
                                        <th>Tahun Akademik</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengampus as $index => $p)
                                    <tr>
                                        <td>{{ $index + $pengampus->firstItem() }}</td>
                                        <td>
                                            {{ $p->matakuliah->nama }}
                                            <span class="d-block text-muted small">({{ $p->matakuliah->sks }} SKS)</span>
                                        </td>
                                        <td>{{ $p->prodi->nama_prodi ?? 'Prodi tidak ditemukan' }}</td>
                                        <td>{{ $p->kelas->nama_kelas }}</td>
                                        <td>
                                            @foreach ($p->dosen as $dosen)
                                                <span class="d-block">{{ $dosen->nama }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ $p->tahun_akademik }}</td>
                                        <td class="text-nowrap">
                                            <a href="{{ route('pengampu.edit', $p->id) }}" class="btn btn-sm btn-action-edit me-1" data-bs-toggle="tooltip" title="Ubah">
                                                Ubah
                                            </a>
                                            <form action="{{ route('pengampu.destroy', $p->id) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-action-delete" data-bs-toggle="tooltip" title="Hapus">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                @if($pengampus->isNotEmpty())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Menampilkan {{ $pengampus->firstItem() }} - {{ $pengampus->lastItem() }} 
                            dari {{ $pengampus->total() }} data
                        </div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination mb-0">
                                {{-- Previous Page Link --}}
                                @if ($pengampus->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">Previous</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $pengampus->previousPageUrl() }}">Previous</a>
                                    </li>
                                @endif

                                {{-- Page Numbers --}}
                                @foreach(range(1, $pengampus->lastPage()) as $page)
                                    @if($page == $pengampus->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $pengampus->url($page) }}">{{ $page }}</a>
                                    </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($pengampus->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $pengampus->nextPageUrl() }}">Next</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">Next</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        const deleteForms = document.querySelectorAll('.delete-form');

        deleteForms.forEach(form => {
            form.addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent the default form submission

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Submit the form if confirmed
                    }
                });
            });
        });
    });
</script>
@endpush