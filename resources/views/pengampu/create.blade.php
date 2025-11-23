@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Data Pengampu</h5>
                    <a href="{{ route('pengampu.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>

                <div class="card-body">
                    {{-- SweetAlert for validation errors --}}
                    @if ($errors->any())
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                let errorMessages = '';
                                @foreach ($errors->all() as $error)
                                    errorMessages += '{{ $error }}<br>';
                                @endforeach
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validasi Gagal!',
                                    html: errorMessages,
                                });
                            });
                        </script>
                    @endif

                    <form action="{{ route('pengampu.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label>Program Studi</label>
                            <select name="prodi_id" id="prodi_select" class="form-control @error('prodi_id') is-invalid @enderror">
                                <option value="">Pilih Program Studi</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }}</option>
                                @endforeach
                            </select>
                            @error('prodi_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Mata Kuliah</label>
                            <select name="matakuliah_id" id="matakuliah_select" class="form-control @error('matakuliah_id') is-invalid @enderror">
                                <option value="">Pilih Mata Kuliah</option>
                            </select>
                            @error('matakuliah_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Dosen 1</label>
                            <select name="dosen1" id="dosen1_select" class="form-control @error('dosen1') is-invalid @enderror">
                                <option value="">Pilih Dosen 1</option>
                            </select>
                            @error('dosen1')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Dosen 2 (Opsional)</label>
                            <select name="dosen2" id="dosen2_select" class="form-control @error('dosen2') is-invalid @enderror">
                                <option value="">Pilih Dosen 2</option>
                            </select>
                            @error('dosen2')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Kelas</label>
                            <select name="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror">
                                <option value="">Pilih Kelas</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Tahun Akademik</label>
                            <input type="text" name="tahun_akademik" class="form-control @error('tahun_akademik') is-invalid @enderror">
                            @error('tahun_akademik')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Simpan Data
                            </button
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const prodiSelect = document.getElementById('prodi_select');
        const matakuliahSelect = document.getElementById('matakuliah_select');
        const dosen1Select = document.getElementById('dosen1_select');
        const dosen2Select = document.getElementById('dosen2_select');
        const kelasSelect = document.querySelector('select[name="kelas_id"]');

        prodiSelect.addEventListener('change', function () {
            const prodiId = this.value;
            if (prodiId) {
                fetch(`/get-matakuliah-dosen/${prodiId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Populate Matakuliah
                        matakuliahSelect.innerHTML = '<option value="">Pilih Mata Kuliah</option>';
                        data.matakuliahs.forEach(matakuliah => {
                            matakuliahSelect.innerHTML += `<option value="${matakuliah.id}">${matakuliah.nama}</option>`;
                        });

                        // Populate Dosen 1
                        dosen1Select.innerHTML = '<option value="">Pilih Dosen 1</option>';
                        data.dosens.forEach(dosen => {
                            dosen1Select.innerHTML += `<option value="${dosen.id}">${dosen.nama}</option>`;
                        });

                        // Store all dosens for later filtering
                        const allDosens = data.dosens;

                        // Function to populate dosen2 based on dosen1 selection
                        function populateDosen2() {
                            dosen2Select.innerHTML = '<option value="">Pilih Dosen 2</option>';
                            allDosens.forEach(dosen => {
                                dosen2Select.innerHTML += `<option value="${dosen.id}">${dosen.nama}</option>`;
                            });
                        }

                        // Populate Dosen 2 initially
                        populateDosen2(dosen1Select.value);

                        // Add event listener for dosen1 to filter dosen2
                        dosen1Select.addEventListener('change', function() {
                            const selectedDosen1 = this.value;
                            const selectedDosen2 = dosen2Select.value;
                            
                            // Enable all options in dosen2Select first
                            for (let option of dosen2Select.options) {
                                option.disabled = false;
                            }

                            if (selectedDosen1) {
                                const optionToDisable = dosen2Select.querySelector(`option[value="${selectedDosen1}"]`);
                                if (optionToDisable) {
                                    optionToDisable.disabled = true;
                                }
                            }
                            
                            if (selectedDosen1 === selectedDosen2) {
                                dosen2Select.value = "";
                            }
                        });

                        dosen2Select.addEventListener('change', function() {
                            const selectedDosen2 = this.value;
                            const selectedDosen1 = dosen1Select.value;

                            // Enable all options in dosen1Select first
                            for (let option of dosen1Select.options) {
                                option.disabled = false;
                            }

                            if (selectedDosen2) {
                                const optionToDisable = dosen1Select.querySelector(`option[value="${selectedDosen2}"]`);
                                if (optionToDisable) {
                                    optionToDisable.disabled = true;
                                }
                            }
                            
                            if (selectedDosen2 === selectedDosen1) {
                                dosen1Select.value = "";
                            }
                        });

                        // Fetch and populate Kelas
                        fetch(`/get-kelas-by-prodi/${prodiId}`)
                            .then(response => response.json())
                            .then(kelasData => {
                                kelasSelect.innerHTML = '<option value="">Pilih Kelas</option>';
                                kelasData.forEach(kelas => {
                                    kelasSelect.innerHTML += `<option value="${kelas.id}">${kelas.nama_kelas}</option>`;
                                });
                            });
                    });
            } else {
                matakuliahSelect.innerHTML = '<option value="">Pilih Mata Kuliah</option>';
                dosen1Select.innerHTML = '<option value="">Pilih Dosen 1</option>';
                dosen2Select.innerHTML = '<option value="">Pilih Dosen 2</option>';
                kelasSelect.innerHTML = '<option value="">Pilih Kelas</option>';
            }
        });
    });
</script>
@endpush
@endsection