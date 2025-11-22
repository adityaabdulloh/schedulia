@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Data Pengampu</h5>
                    <a href="{{ route('pengampu.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('pengampu.update', $pengampu->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label>Dosen 1</label>
                            <select name="dosen1" id="dosen1_select" class="form-control @error('dosen1') is-invalid @enderror">
                                <option value="">Pilih Dosen 1</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->id }}" 
                                        {{ isset($pengampu->dosen[0]) && $pengampu->dosen[0]->id == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dosen1')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Dosen 2 (Opsional)</label>
                            <select name="dosen2" id="dosen2_select" class="form-control @error('dosen2') is-invalid @enderror">
                                <option value="">Pilih Dosen 2</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->id }}"
                                        {{ isset($pengampu->dosen[1]) && $pengampu->dosen[1]->id == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dosen2')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        

                        

                        <div class="mb-3">
                            <label>Mata Kuliah</label>
                            <select name="matakuliah_id" class="form-control @error('matakuliah_id') is-invalid @enderror">
                                <option value="">Pilih Mata Kuliah</option>
                                @foreach($matakuliahs as $matakuliah)
                                    <option value="{{ $matakuliah->id }}" 
                                        {{ $pengampu->matakuliah_id == $matakuliah->id ? 'selected' : '' }}>
                                        {{ $matakuliah->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('matakuliah_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Kelas</label>
                            <select name="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror">
                                <option value="">Pilih Kelas</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" 
                                        {{ $pengampu->kelas_id == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Program Studi</label>
                            <select name="prodi_id" id="prodi_select" class="form-control @error('prodi_id') is-invalid @enderror">
                                <option value="">Pilih Program Studi</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id }}"
                                        {{ $pengampu->prodi_id == $prodi->id ? 'selected' : '' }}>
                                        {{ $prodi->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('prodi_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Tahun Akademik</label>
                            <input type="text" name="tahun_akademik" class="form-control @error('tahun_akademik') is-invalid @enderror" 
                                   value="{{ $pengampu->tahun_akademik }}">
                            @error('tahun_akademik')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Update Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dosen1Select = document.getElementById('dosen1_select');
        const dosen2Select = document.getElementById('dosen2_select');

        function updateDosenOptions() {
            const selectedDosen1 = dosen1Select.value;
            const selectedDosen2 = dosen2Select.value;

            // Enable all options first
            for (let option of dosen1Select.options) {
                option.disabled = false;
            }
            for (let option of dosen2Select.options) {
                option.disabled = false;
            }

            // Disable selected option in the other dropdown
            if (selectedDosen1) {
                const optionInDosen2 = dosen2Select.querySelector(`option[value="${selectedDosen1}"]`);
                if (optionInDosen2) {
                    optionInDosen2.disabled = true;
                }
            }

            if (selectedDosen2) {
                const optionInDosen1 = dosen1Select.querySelector(`option[value="${selectedDosen2}"]`);
                if (optionInDosen1) {
                    optionInDosen1.disabled = true;
                }
            }
        }

        dosen1Select.addEventListener('change', function() {
            if (this.value === dosen2Select.value) {
                dosen2Select.value = "";
            }
            updateDosenOptions();
        });

        dosen2Select.addEventListener('change', function() {
            if (this.value === dosen1Select.value) {
                dosen1Select.value = "";
            }
            updateDosenOptions();
        });

        // Initial update on page load
        updateDosenOptions();
    });
</script>
@endpush