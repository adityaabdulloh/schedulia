<!DOCTYPE html>
<html>
<head>
    <title>Jadwal Kuliah</title>
    <style>
        body { font-family: sans-serif; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; }
        .table th { background-color: #f2f2f2; text-align: left; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Jadwal Kuliah Mahasiswa</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Hari</th>
                <th>Jam</th>
                <th>Mata Kuliah</th>
                <th>SKS</th>
                <th>Dosen</th>
                <th>Ruang</th>
                <th>Kelas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwalKuliah as $j)
            <tr>
                <td>{{ $j->hari->nama_hari ?? '-' }}</td>
                <td>
                    @if ($j->jam_mulai && $j->jam_selesai)
                        {{ $j->jam_mulai }} - {{ $j->jam_selesai }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $j->pengampu->matakuliah->nama ?? '-' }}</td>
                <td>{{ $j->pengampu->matakuliah->sks ?? '-' }}</td>
                <td>
                    @foreach ($j->pengampu->dosen as $dosen)
                        {{ $dosen->nama }}<br>
                    @endforeach
                </td>
                <td>{{ $j->ruang->nama_ruang ?? '-' }}</td>
                <td>{{ $j->pengampu->kelas->nama_kelas ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">Tidak ada data jadwal yang tersedia.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
