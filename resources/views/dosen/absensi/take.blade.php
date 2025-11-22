@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h3 text-gray-800">Ambil Absensi</h1>
    </div>
    <p class="mb-1">
        Mata Kuliah: <strong>{{ $pengampu->matakuliah->nama }}</strong>
    </p>
    @if($selectedJadwalKuliah)
        <p class="mb-1">
            Jadwal: <strong>{{ optional($selectedJadwalKuliah->hari)->nama_hari }}, {{ \Carbon\Carbon::parse($selectedJadwalKuliah->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($selectedJadwalKuliah->jam_selesai)->format('H:i') }}</strong>
        </p>
        <p class="mb-4">
            Ruang: <strong>{{ optional($selectedJadwalKuliah->ruang)->nama_ruang }}</strong>
        </p>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Daftar Mahasiswa</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('dosen.absensi.store', $pengampu->id) }}" method="POST">
                @csrf
                @if($selectedJadwalKuliah)
                    <input type="hidden" name="jadwal_kuliah_id" value="{{ $selectedJadwalKuliah->id }}">
                @endif
                <div class="form-group row">
                    <label for="pertemuan" class="col-sm-2 col-form-label">Pertemuan Ke-</label>
                    <div class="col-sm-4">
                        <select class="form-control @error('pertemuan') is-invalid @enderror" id="pertemuan" name="pertemuan" required>
                            @for ($i = 1; $i <= 16; $i++)
                                <option value="{{ $i }}" {{ $i == $nextPertemuan ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        @error('pertemuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Program Studi</th>
                                <th>Status KRS</th>
                                <th>Status Absensi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mahasiswas as $mahasiswa)
                            <tr>
                                <td class="d-flex align-items-center">
                                    <img src="{{ $mahasiswa->foto_profil ? asset('storage/foto_profil/' . $mahasiswa->foto_profil) : asset('images/default-profil.svg') }}"
                                         alt="Foto Profil" class="img-thumbnail rounded-circle mr-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    {{ $mahasiswa->nim }}
                                </td>
                                <td>{{ $mahasiswa->nama }}</td>
                                <td>{{ $mahasiswa->prodi->nama_prodi ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $pengambilanMk = $mahasiswa->pengambilanMk->first();
                                        $status = $pengambilanMk ? $pengambilanMk->status : 'N/A';
                                        $badgeClass = 'badge-secondary';
                                        if ($status === 'approved') {
                                            $badgeClass = 'badge-success';
                                        } elseif ($status === 'pending') {
                                            $badgeClass = 'badge-warning';
                                        } elseif ($status === 'rejected') {
                                            $badgeClass = 'badge-danger';
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                </td>
                                <td>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="absensi[{{ $mahasiswa->id }}]" id="hadir_{{ $mahasiswa->id }}" value="hadir" checked>
                                        <label class="form-check-label" style="color: black !important;" for="hadir_{{ $mahasiswa->id }}">Hadir</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="absensi[{{ $mahasiswa->id }}]" id="izin_{{ $mahasiswa->id }}" value="izin">
                                        <label class="form-check-label" style="color: black !important;" for="izin_{{ $mahasiswa->id }}">Izin</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="absensi[{{ $mahasiswa->id }}]" id="sakit_{{ $mahasiswa->id }}" value="sakit">
                                        <label class="form-check-label" style="color: black !important;" for="sakit_{{ $mahasiswa->id }}">Sakit</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="absensi[{{ $mahasiswa->id }}]" id="alpha_{{ $mahasiswa->id }}" value="alpha">
                                        <label class="form-check-label" style="color: black !important;" for="alpha_{{ $mahasiswa->id }}">Alpha</label>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada mahasiswa yang mengambil mata kuliah ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-success mt-3">Simpan Absensi</button>
            </form>
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

