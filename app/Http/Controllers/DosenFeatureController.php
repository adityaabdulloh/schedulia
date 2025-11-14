<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\PengambilanMK;
use App\Models\Pengampu;
use Illuminate\Support\Facades\Auth;

class DosenFeatureController extends Controller
{
    public function inputNilai()
    {
        // Logic for inputting grades
        return view('dosen.nilai.input');
    }

    public function materiKuliah()
    {
        // Logic for managing course materials
        return view('dosen.materi.index');
    }

    public function pengambilanMk()
    {
        $user = Auth::user();
        $dosen = Dosen::where('email', $user->email)->firstOrFail();

        // Dapatkan semua ID mata kuliah yang diampu oleh dosen
        $matakuliahIds = $dosen->pengampus()->pluck('matakuliah_id')->unique();

        // Dapatkan data pengambilan MK oleh mahasiswa untuk mata kuliah yang diampu dosen
        $pengambilanMk = PengambilanMK::whereIn('matakuliah_id', $matakuliahIds)
            ->where('status', 'approved')
            ->with(['mahasiswa.prodi', 'matakuliah.prodi', 'mahasiswa.kelas'])
            ->get();

        return view('dosen.pengambilan-mk', compact('pengambilanMk', 'dosen'));
    }

    public function absensi()
    {
        return view('dosen.absensi');
    }

    public function pengambilanMkDosen()
    {
        return view('dosen.mahasiswa.pengambilanmk');
    }

    public function absensiMahasiswa()
    {
        return view('dosen.mahasiswa.absensi');
    }
}
