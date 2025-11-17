@extends('layouts.app')

@push('styles')
<link href="{{ asset('css/jadwal.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Data User</h3>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('users.create') }}" class="btn btn-success btn-sm me-2">Tambah User</a>
                        <form action="{{ route('users.index') }}" method="GET" class="d-flex">
                            <div class="input-group input-group-sm">
                                <input type="text" name="search" class="form-control" placeholder="Pencarian..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-secondary">Search</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card-body">
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

                    @if($users->isEmpty())
                        <div class="alert alert-info text-center" role="alert">
                            Tidak ada data user yang tersedia.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="usersTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="fw-bold text-white">No</th>
                                        <th class="fw-bold text-white">Nama</th>
                                        <th class="fw-bold text-white">Email</th>
                                        <th class="fw-bold text-white">Role</th>
                                        <th class="fw-bold text-white">Detail</th>
                                        <th class="fw-bold text-white">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $index => $user)
                                    <tr>
                                        <td>{{ $index + $users->firstItem() }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td><span class="badge bg-info text-dark">{{ ucfirst($user->role) }}</span></td>
                                        <td>
                                            @if($user->role === 'mahasiswa' && $user->mahasiswa)
                                                NIM: {{ $user->mahasiswa->nim }}<br>
                                                Prodi: {{ $user->mahasiswa->prodi->nama_prodi ?? '-' }}<br>
                                                Semester: {{ $user->mahasiswa->semester }}<br>
                                                Kelas: {{ $user->mahasiswa->kelas->nama_kelas ?? '-' }}
                                            @elseif($user->role === 'dosen' && $user->dosen)
                                                NIP: {{ $user->dosen->nip }}<br>
                                                Prodi: {{ $user->dosen->prodi->nama_prodi ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-action-edit me-1" data-bs-toggle="tooltip" title="Ubah">
                                                Ubah
                                            </a>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline delete-form">
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

                @if($users->isNotEmpty())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} 
                            dari {{ $users->total() }} data
                        </div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination mb-0">
                                {{-- Previous Page Link --}}
                                @if ($users->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">Previous</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $users->previousPageUrl() }}">Previous</a>
                                    </li>
                                @endif

                                {{-- Page Numbers --}}
                                @foreach(range(1, $users->lastPage()) as $page)
                                    @if($page == $users->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $users->url($page) }}">{{ $page }}</a>
                                    </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($users->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $users->nextPageUrl() }}">Next</a>
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
