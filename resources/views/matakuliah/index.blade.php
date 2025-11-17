@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Data Mata Kuliah</h4>
                    <button class="btn btn-primary" onclick="window.location.href='{{ route('matakuliah.create') }}'">
                        <i class="fas fa-plus me-2"></i>Tambah Data
                    </button>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <form action="{{ route('matakuliah.index') }}" method="GET" class="d-flex justify-content-between flex-wrap">
                            <div class="d-flex">
                                {{-- Filter by Prodi --}}
                                <select class="form-select me-2" id="filterProdi" name="prodi_id" style="min-width: 200px;" onchange="this.form.submit()">
                                    <option value="">Semua Prodi</option>
                                    @foreach($prodi as $p)
                                        <option value="{{ $p->id }}" {{ (string)$prodiFilter === (string)$p->id ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                                    @endforeach
                                </select>
                                {{-- Filter by Semester --}}
                                <select class="form-select me-2" id="filterSemester" name="semester" style="min-width: 200px;" onchange="this.form.submit()">
                                    <option value="">Semua Semester</option>
                                    @for ($i = 1; $i <= 8; $i++)
                                        <option value="{{ $i }}" {{ (string)$semesterFilter === (string)$i ? 'selected' : '' }}>Semester {{ $i }}</option>
                                    @endfor
                                </select>
                                @if(request('prodi_id') || request('semester') || request('search'))
                                    <a href="{{ route('matakuliah.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-filter-slash me-1"></i>Clear Filters
                                    </a>
                                @endif
                            </div>
                            <div class="input-group" style="width: 300px;">
                                <input type="text" name="search" id="searchInput" class="form-control" placeholder="Cari mata kuliah..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fas fa-search me-1"></i>Cari
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('matakuliah.index', ['prodi_id' => request('prodi_id'), 'semester' => request('semester')]) }}" class="btn btn-outline-danger">
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

                    @if($mataKuliah->isEmpty())
                        <div class="alert alert-info text-center" role="alert">
                            <i class="fas fa-info-circle me-2"></i>Tidak ada data mata kuliah yang tersedia.
                            <br>
                            <a href="{{ route('matakuliah.create') }}" class="alert-link mt-2 d-inline-block">Tambahkan data mata kuliah baru sekarang!</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col" class="text-center text-white">No</th>
                                        <th scope="col" class="text-white">Kode MK</th>
                                        <th scope="col" class="text-white">Nama</th>
                                        <th scope="col" class="text-white">SKS</th>
                                        <th scope="col" class="text-white">Semester</th>
                                        <th scope="col" class="text-white">Prodi</th>
                                        <th scope="col" class="text-center text-white">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mataKuliah as $index => $mk)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 + ($mataKuliah->currentPage() - 1) * $mataKuliah->perPage() }}</td>
                                        <td>{{ $mk->kode_mk }}</td>
                                        <td>{{ $mk->nama }}</td>
                                        <td>{{ $mk->sks }}</td>
                                        <td>{{ $mk->semester }}</td>
                                        <td>{{ $mk->prodi->nama_prodi ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group" aria-label="Aksi Mata Kuliah">
                                                <a href="{{ route('matakuliah.edit', $mk->id) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </a>
                                                <form action="{{ route('matakuliah.destroy', $mk->id) }}" method="POST" class="d-inline" id="delete-form-{{ $mk->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(event, 'delete-form-{{ $mk->id }}')" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus">
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
                            Menampilkan {{ $mataKuliah->firstItem() }} - {{ $mataKuliah->lastItem() }} dari {{ $mataKuliah->total() }} data
                        </div>
                        <nav aria-label="Navigasi Halaman">
                            <ul class="pagination mb-0">
                                {{-- Previous Page Link --}}
                                @if ($mataKuliah->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">Previous</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $mataKuliah->previousPageUrl() }}">Previous</a>
                                    </li>
                                @endif

                                {{-- Page Numbers --}}
                                @foreach(range(1, $mataKuliah->lastPage()) as $page)
                                    @if($page == $mataKuliah->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $mataKuliah->url($page) }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($mataKuliah->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $mataKuliah->nextPageUrl() }}">Next</a>
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
            text: "Data mata kuliah ini akan dihapus secara permanen!",
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