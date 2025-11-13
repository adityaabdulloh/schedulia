@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-color: #4e73df;
        --secondary-color: #858796;
        --success-color: #1cc88a;
        --info-color: #36b9cc;
        --warning-color: #f6c23e;
        --danger-color: #e74a3b;
        --light-color: #f8f9fc;
        --dark-color: #5a5c69;
        --font-family-sans-serif: "Nunito", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    }

    .card {
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        overflow: hidden;
    }

    .card-header {
        background-color: var(--primary-color);
        color: white;
        padding: 1.5rem;
        border-bottom: none;
    }
    .card-header h3 {
        margin-bottom: 0.25rem;
        font-weight: 700;
    }
    .card-header p {
        margin-bottom: 0;
        opacity: 0.8;
    }

    .nav-tabs {
        border-bottom: none;
        padding: 0 1.5rem;
        background-color: #f1f3f8;
    }
    .nav-tabs .nav-link {
        border: none;
        padding: 1rem 1.5rem;
        color: var(--dark-color);
        font-weight: 600;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
        transition: all 0.3s ease;
    }
    .nav-tabs .nav-link.active {
        background-color: #fff;
        color: var(--primary-color);
        border-bottom: 3px solid var(--primary-color);
    }
    .nav-tabs .nav-link:hover {
        background-color: #e9ecef;
    }

    .tab-content {
        padding: 1.5rem;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5em 0.75em;
        border-radius: 0.35rem;
        font-size: 0.75rem;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        text-transform: uppercase;
    }
    .status-hadir { background-color: var(--success-color); color: white; }
    .status-sakit { background-color: var(--warning-color); color: #5a5c69; }
    .status-izin { background-color: var(--info-color); color: white; }
    .status-alpha { background-color: var(--danger-color); color: white; }
    .status-null { background-color: #e9ecef; color: #858796; }

    .table-hover tbody tr:hover {
        background-color: #f1f3f8;
    }
    .table th {
        font-weight: 600;
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
</style>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-calendar-check me-2"></i>Absensi Mata Kuliah: {{ $pengampu->matakuliah->nama }}</h3>
                    <p class="mb-0">Kelas: {{ $pengampu->kelas->nama_kelas }} | Prodi: {{ $pengampu->prodi->nama_prodi }}</p>
                </div>

                <ul class="nav nav-tabs" id="absensiTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="ambil-absensi-tab" data-bs-toggle="tab" data-bs-target="#ambil-absensi" type="button" role="tab" aria-controls="ambil-absensi" aria-selected="true"><i class="fas fa-user-check me-2"></i>Ambil Absensi</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="histori-absensi-tab" data-bs-toggle="tab" data-bs-target="#histori-absensi" type="button" role="tab" aria-controls="histori-absensi" aria-selected="false"><i class="fas fa-history me-2"></i>Histori Absensi</button>
                    </li>
                </ul>

                <div class="card-body tab-content" id="absensiTabContent">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    {{-- Tab untuk mengambil absensi --}}
                    <div class="tab-pane fade show active" id="ambil-absensi" role="tabpanel" aria-labelledby="ambil-absensi-tab">
                        <form action="{{ route('dosen.absensi.store', $pengampu->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="pertemuan" class="form-label fw-bold">Pertemuan Ke:</label>
                                <select name="pertemuan" id="pertemuan" class="form-select form-select-lg" required>
                                    <option value="" disabled selected>Pilih Pertemuan</option>
                                    @for ($i = 1; $i <= 16; $i++)
                                        <option value="{{ $i }}">Pertemuan {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>NIM</th>
                                            <th>Nama Mahasiswa</th>
                                            <th class="text-center">Status Kehadiran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($mahasiswa as $m)
                                        <tr>
                                            <td>{{ $m->nim }}</td>
                                            <td>{{ $m->nama }}</td>
                                            <td>
                                                <input type="hidden" name="mahasiswa_id[]" value="{{ $m->id }}">
                                                <div class="d-flex justify-content-center gap-3">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status[{{ $m->id }}]" id="hadir_{{ $m->id }}" value="hadir" checked>
                                                        <label class="form-check-label" for="hadir_{{ $m->id }}">Hadir</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status[{{ $m->id }}]" id="izin_{{ $m->id }}" value="izin">
                                                        <label class="form-check-label" for="izin_{{ $m->id }}">Izin</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status[{{ $m->id }}]" id="sakit_{{ $m->id }}" value="sakit">
                                                        <label class="form-check-label" for="sakit_{{ $m->id }}">Sakit</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status[{{ $m->id }}]" id="alpha_{{ $m->id }}" value="alpha">
                                                        <label class="form-check-label" for="alpha_{{ $m->id }}">Alpha</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-5">
                                                <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Tidak ada mahasiswa yang mengambil mata kuliah ini.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if($mahasiswa->isNotEmpty())
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save me-2"></i>Simpan Absensi</button>
                            </div>
                            @endif
                        </form>
                    </div>

                    {{-- Tab untuk histori absensi --}}
                    <div class="tab-pane fade" id="histori-absensi" role="tabpanel" aria-labelledby="histori-absensi-tab">
                        @if($mahasiswa->isNotEmpty() && $pertemuanTerisi->isNotEmpty())
                        <div class="d-flex justify-content-end mb-3">
                            <div class="d-flex gap-3">
                                <span class="status-badge status-hadir">Hadir</span>
                                <span class="status-badge status-sakit">Sakit</span>
                                <span class="status-badge status-izin">Izin</span>
                                <span class="status-badge status-alpha">Alpha</span>
                                <span class="status-badge status-null">Kosong</span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Mahasiswa</th>
                                        @foreach($pertemuanTerisi as $pertemuan)
                                            <th class="text-center">P{{ $pertemuan }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mahasiswa as $m)
                                        <tr>
                                            <td>{{ $m->nama }}</td>
                                            @foreach($pertemuanTerisi as $pertemuan)
                                                <td class="text-center p-2">
                                                    @php
                                                        $status = '-';
                                                        if(isset($absensiHistory[$m->id])) {
                                                            $absensiPertemuan = $absensiHistory[$m->id]->firstWhere('pertemuan', $pertemuan);
                                                            if ($absensiPertemuan) {
                                                                $status = $absensiPertemuan->status;
                                                            }
                                                        }
                                                    @endphp
                                                    <span class="status-badge status-{{ $status != '-' ? $status : 'null' }}">{{ $status }}</span>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada histori absensi untuk mata kuliah ini.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
