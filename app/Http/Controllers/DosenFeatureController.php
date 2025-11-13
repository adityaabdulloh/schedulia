<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\PengambilanMK;
use App\Models\Pengampu;
use Illuminate\Support\Facades\Auth;

class DosenFeatureController extends Controller
{
    public function daftarMahasiswa()
    {
        $user = Auth::user();
        $dosen = Dosen::where('email', $user->email)->firstOrFail();

        $pengampuRecords = $dosen->pengampus()->with('matakuliah', 'kelas')->get();
        $matakuliahIdsTaughtByDosen = $pengampuRecords->pluck('matakuliah_id')->unique();

        // Get unique mahasiswa_id from PengambilanMK where status is 'approved'
        // and matakuliah_id is one of the courses taught by this dosen
        $validatedMahasiswaIds = PengambilanMK::whereIn('matakuliah_id', $matakuliahIdsTaughtByDosen)
            ->where('status', 'approved')
            ->pluck('mahasiswa_id')
            ->unique();

        // Get students based on the validatedMahasiswaIds
        $mahasiswa = Mahasiswa::whereIn('id', $validatedMahasiswaIds)
            ->with('prodi', 'kelas')
            ->orderBy('nama')
            ->get();

        

        return view('dosen.mahasiswa.index', compact('mahasiswa', 'pengampuRecords'));
    }

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
}
