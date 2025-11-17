@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Data Dosen</h4>
                    <button class="btn btn-primary" onclick="window.location.href='{{ route('dosen.create') }}'">
                        <i class="fas fa-plus me-2"></i>Tambah Data
                    </button>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <form action="{{ route('dosen.index') }}" method="GET" class="d-flex justify-content-between flex-wrap">
                            <div class="d-flex">
                                {{-- Filter by Prodi --}}
                                <select class="form-select me-2" id="filterProdi" name="prodi_id" style="min-width: 200px;" onchange="this.form.submit()">
                                    <option value="">Semua Prodi</option>
                                    @foreach($prodi as $p)
                                        <option value="{{ $p->id }}" {{ (string)$prodiFilter === (string)$p->id ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                                    @endforeach
                                </select>
                                @if(request('prodi_id') || request('search'))
                                    <a href="{{ route('dosen.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-filter-slash me-1"></i>Clear Filters
                                    </a>
                                @endif
                            </div>
                            <div class="input-group" style="width: 300px;">
                                <input type="text" name="search" id="searchInput" class="form-control" placeholder="Cari dosen..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fas fa-search me-1"></i>Cari
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('dosen.index', ['prodi_id' => request('prodi_id')]) }}" class="btn btn-outline-danger">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($dosen->isEmpty())
                        <div class="alert alert-info text-center" role="alert">
                            <i class="fas fa-info-circle me-2"></i>Tidak ada data dosen yang tersedia.
                            <br>
                            <a href="{{ route('dosen.create') }}" class="alert-link mt-2 d-inline-block">Tambahkan data dosen baru sekarang!</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col" class="text-center text-white">No</th>
                                        <th scope="col" class="text-white">Foto Profil</th>
                                        <th scope="col" class="text-white">NIP</th>
                                        <th scope="col" class="text-white">Nama</th>
                                        <th scope="col" class="text-white">Email</th>
                                        <th scope="col" class="text-white">Prodi</th>
                                        <th scope="col" class="text-center text-white">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dosen as $index => $d)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 + ($dosen->currentPage() - 1) * $dosen->perPage() }}</td>
                                        <td>
                                            @if($d->foto_profil)
                                                <img src="{{ asset('storage/foto_profil/' . $d->foto_profil) }}" alt="Foto Profil" class="img-thumbnail" width="50">
                                            @else
                                                <img src="{{ asset('images/default-profil.svg') }}" alt="Default Foto Profil" class="img-thumbnail" width="50">
                                            @endif
                                        </td>
                                        <td>{{ $d->nip }}</td>
                                        <td>{{ $d->nama }}</td>
                                        <td>{{ $d->email }}</td>
                                        <td>{{ $d->prodi->nama_prodi ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group" aria-label="Aksi Dosen">
                                                <a href="{{ route('dosen.edit', $d->id) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </a>
                                                <form action="{{ route('dosen.destroy', $d->id) }}" method="POST" class="d-inline" id="delete-form-{{ $d->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(event, 'delete-form-{{ $d->id }}')" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus">
                                                        <i class="fas fa-trash me-1"></i>Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Menampilkan {{ $dosen->firstItem() }} - {{ $dosen->lastItem() }} dari {{ $dosen->total() }} data
                        </div>
                        <nav aria-label="Navigasi Halaman">
                            <ul class="pagination mb-0">
                                {{-- Previous Page Link --}}
                                @if ($dosen->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">Previous</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $dosen->previousPageUrl() }}">Previous</a>
                                    </li>
                                @endif

                                {{-- Page Numbers --}}
                                @foreach(range(1, $dosen->lastPage()) as $page)
                                    @if($page == $dosen->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $dosen->url($page) }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($dosen->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $dosen->nextPageUrl() }}">Next</a>
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
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    function confirmDelete(event, formId) {
        event.preventDefault(); // Prevent the default form submission
        const form = document.getElementById(formId);

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data dosen ini akan dihapus secara permanen!",
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
    }
</script>
@endpush