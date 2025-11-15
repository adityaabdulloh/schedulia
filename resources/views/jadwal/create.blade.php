@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Jadwal Kuliah</h3>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('jadwal.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="pengampu_id" class="form-label">Pengampu</label>
                            <select name="pengampu_id" id="pengampu_id" class="form-select" required>
                                <option value="">Pilih Pengampu</option>
                                @foreach($pengampus as $pengampu)
                                    <option value="{{ $pengampu->id }}" {{ old('pengampu_id') == $pengampu->id ? 'selected' : '' }}>
                                        {{ $pengampu->matakuliah->nama }} - {{ $pengampu->dosen->implode('nama', ', ') }} - {{ $pengampu->kelas->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="sks" class="form-label">Jumlah SKS</label>
                            <input type="text" id="sks" name="sks" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="tahun_akademik" class="form-label">Tahun Akademik</label>
                            <input type="text" name="tahun_akademik" id="tahun_akademik" class="form-control" value="{{ old('tahun_akademik', date('Y').'/'.(date('Y')+1)) }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hari_id" class="form-label">Hari</label>
                                    <select name="hari_id" id="hari_id" class="form-select" required>
                                        <option value="">Pilih Hari</option>
                                        @foreach($haris as $hari)
                                            <option value="{{ $hari->id }}" {{ old('hari_id') == $hari->id ? 'selected' : '' }}>
                                                {{ $hari->nama_hari }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ruang_id" class="form-label">Ruang</label>
                                    <select name="ruang_id" id="ruang_id" class="form-select" required>
                                        <option value="">Pilih Ruang</option>
                                        @foreach($ruangs as $ruang)
                                            <option value="{{ $ruang->id }}" {{ old('ruang_id') == $ruang->id ? 'selected' : '' }}>
                                                {{ $ruang->nama_ruang }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Metode Penjadwalan</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metode_penjadwalan" id="metode_otomatis" value="otomatis" checked>
                                <label class="form-check-label" for="metode_otomatis">
                                    Otomatis
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metode_penjadwalan" id="metode_manual" value="manual">
                                <label class="form-check-label" for="metode_manual">
                                    Manual
                                </label>
                            </div>
                        </div>

                        <div id="manual_time_input" class="mb-3" style="display: none;">
                            <label for="jam_mulai_manual" class="form-label">Jam Mulai</label>
                            <input type="time" name="jam_mulai_manual" id="jam_mulai_manual" class="form-control">
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
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
        const pengampuSelect = document.getElementById('pengampu_id');
        const sksInput = document.getElementById('sks');
        const sksData = @json($sksData);

        pengampuSelect.addEventListener('change', function () {
            const selectedPengampuId = this.value;
            if (selectedPengampuId && sksData[selectedPengampuId]) {
                sksInput.value = sksData[selectedPengampuId];
            } else {
                sksInput.value = '';
            }
        });

        if (pengampuSelect.value) {
            pengampuSelect.dispatchEvent(new Event('change'));
        }

        const metodeOtomatis = document.getElementById('metode_otomatis');
        const metodeManual = document.getElementById('metode_manual');
        const manualTimeInput = document.getElementById('manual_time_input');

        metodeOtomatis.addEventListener('change', function() {
            if (this.checked) {
                manualTimeInput.style.display = 'none';
            }
        });

        metodeManual.addEventListener('change', function() {
            if (this.checked) {
                manualTimeInput.style.display = 'block';
            }
        });
    });
</script>
@endpush