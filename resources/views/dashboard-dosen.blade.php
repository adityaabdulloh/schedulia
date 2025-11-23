@extends('layouts.app')

@section('content')


<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="welcome-message-box mb-4 flex flex-col sm:flex-row items-center sm:items-start p-4">
        <div class="icon">
            <i class="fas fa-sun"></i>
        </div>
        <div class="text">
            <h4>Selamat Datang, Dosen {{ Auth::user()->name }} di Schedulia!</h4>
            <p>Semoga hari Anda produktif dan penuh pencapaian dalam mengajar.</p>
        </div>
    </div>

    <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-lg-8">
            <!-- Widget Jadwal Mengajar Hari Ini -->
            <div class="widget-card primary-border mb-4">
                <div class="widget-header">
                    <h2 class="widget-title"><i class="fas fa-calendar-alt mr-2"></i>Jadwal Mengajar Hari Ini</h2>
                </div>
                <div class="schedule-timeline">
                    @if(isset($jadwalHariIni) && $jadwalHariIni->count() > 0)
                        @foreach($jadwalHariIni as $jadwal)
                            <div class="timeline-item jadwal-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap">
                                        <div class="jadwal-info">
                                            <div class="jadwal-matkul">{{ $jadwal->pengampu->matakuliah->nama }}</div>
                                            <h6 class="text-success font-weight-bold"><i class="fas fa-calendar-day mr-1"></i>{{ $jadwal->hari->nama_hari }}</h6>
                                            <h6 class="text-primary"><i class="fas fa-users mr-1"></i>Kelas: {{ $jadwal->kelas->nama_kelas }}</h6>
                                            <h6 class="text-info d-block"><i class="fas fa-map-marker-alt mr-1"></i>Ruang: {{ $jadwal->ruang->nama_ruang }}</h6>
                                            <div class="jadwal-actions mt-2">
                                                <a href="{{ route('pengumuman.create', ['jadwalKuliah' => $jadwal->id]) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-bullhorn mr-1"></i> Buat Pengumuman
                                                </a>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="jadwal-time d-inline-block">{{ date('H:i', strtotime($jadwal->jam_mulai)) }} - {{ date('H:i', strtotime($jadwal->jam_selesai)) }}</div>
                                            <span class="badge badge-sks text-primary d-inline-block ml-2">{{ $jadwal->pengampu->matakuliah->sks }} SKS</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center empty-state">
                            <i class="fas fa-info-circle mr-2 fa-3x mb-3"></i>
                            <p class="lead">Tidak ada jadwal mengajar untuk hari ini.</p>
                            <p class="text-muted">Jadwal Anda kosong untuk hari ini. Nikmati waktu luang Anda!</p>
                        </div>
                    @endif
                </div>
            </div>

             <!-- Widget Pengumuman -->
            <div class="widget-card warning-border">
                <div class="widget-header">
                    <h2 class="widget-title"><i class="fas fa-bullhorn"></i>Pengumuman Terkirim</h2>
                </div>
                <div class="pengumuman-list">
                    @forelse($pengumuman as $item)
                        @php
                            $cardClass = 'info-type'; $iconClass = 'fas fa-info-circle'; $badgeClass = 'info';
                            if ($item->tipe == 'perubahan') { $cardClass = 'warning-type'; $iconClass = 'fas fa-exclamation-triangle'; $badgeClass = 'warning'; }
                            elseif ($item->tipe == 'pembatalan') { $cardClass = 'danger-type'; $iconClass = 'fas fa-times-circle'; $badgeClass = 'danger'; }
                        @endphp
                        <div class="announcement-card {{ $cardClass }}">
                            <div class="announcement-header">
                                <i class="announcement-icon {{ $iconClass }}"></i>
                                <div>
                                    <h5 class="announcement-title">{{ $item->jadwalKuliah->pengampu->matakuliah->nama }}</h5>
                                    <div class="announcement-meta">
                                        <span class="announcement-type-badge {{ $badgeClass }}">{{ ucfirst($item->tipe) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="announcement-content">
                                <p>{{ $item->pesan }}</p>
                            </div>
                            <div class="announcement-footer">
                                <span>Oleh: {{ $item->dosen->nama }}</span>
                                <span>{{ $item->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">Belum ada pengumuman yang Anda kirim.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-lg-4">
            <!-- Widget Jam Digital Atas -->
            <div class="digital-clock-widget-top mb-4">
                <div id="digital-clock-top"></div>
                <div id="digital-date-top"></div>
            </div>

            <!-- Widget Profil -->
            <div class="widget-card profile-widget secondary-border">
                <img src="{{ $dosen->foto_profil ? Storage::url($dosen->foto_profil) : asset('images/default-profil.svg') }}" alt="Foto Profil" class="profile-img">
                <div class="profile-info">
                    <h3 class="profile-name">{{ Auth::user()->name }}</h3>
                    <p class="mb-0 text-muted">{{ $dosen->nip }}</p>
                    @if($dosen->prodi)
                        <p class="mb-0 text-muted">{{ $dosen->prodi->nama_prodi }}</p>
                    @endif
                    <p class="mb-0 text-muted">{{ Auth::user()->email }}</p>
                    <a href="{{ route('dosen.edit-profile') }}" class="btn btn-primary mt-3">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk mengupdate jam digital
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        const clockElTop = document.getElementById('digital-clock-top');
        if (clockElTop) {
            clockElTop.textContent = `${hours}:${minutes}:${seconds}`;
        }

        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateElTop = document.getElementById('digital-date-top');
        if (dateElTop) {
            dateElTop.textContent = now.toLocaleDateString('id-ID', options);
        }
    }

    // Fungsi untuk menandai jadwal yang sedang berlangsung
    function updateScheduleHighlight() {
        const now = new Date();
        const currentTimeMinutes = now.getHours() * 60 + now.getMinutes();

        document.querySelectorAll('.jadwal-item').forEach(item => {
            const startTimeStr = item.dataset.start;
            const endTimeStr = item.dataset.end;

            if (startTimeStr && endTimeStr) {
                const startMinutes = parseInt(startTimeStr.split(':')[0]) * 60 + parseInt(startTimeStr.split(':')[1]);
                const endMinutes = parseInt(endTimeStr.split(':')[0]) * 60 + parseInt(endTimeStr.split(':')[1]);

                if (currentTimeMinutes >= startMinutes && currentTimeMinutes < endMinutes) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            }
        });
    }

    // Inisialisasi dan interval
    updateClock();
    updateScheduleHighlight();
    setInterval(updateClock, 1000);
    setInterval(updateScheduleHighlight, 60000); // Cek setiap menit
});
</script>
@endpush

@push('styles')
<style>
.digital-clock-widget-top {
    background-color: #4e73df; /* Warna primer tema */
    color: #ffffff;
    text-align: center;
    padding: 5px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

#digital-clock-top {
    font-size: 1.5rem;
    font-weight: 700;
    letter-spacing: 2px;
}

#digital-date-top {
    font-size: 0.8rem;
    letter-spacing: 1px;
}
</style>
@endpush

@endsection