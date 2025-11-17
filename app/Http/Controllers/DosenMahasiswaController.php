<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\PengambilanMK;
use App\Models\Absensi;
use App\Models\Dosen; // Import Dosen model
use App\Models\Pengampu; // Import Pengampu model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class DosenMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan.');
        }

        // Get all pengampu records for the logged-in dosen
        $pengampuIds = $dosen->pengampus->pluck('id');

        // Get all unique mahasiswa_id from PengambilanMK based on pengampu_id
        $mahasiswaIds = PengambilanMK::whereIn('pengampu_id', $pengampuIds)
            ->distinct()
            ->pluck('mahasiswa_id');

        // Get all distinct MataKuliah associated with the dosen's pengampu records
        $mataKuliahDosen = Pengampu::whereIn('id', $pengampuIds)
            ->with('matakuliah')
            ->get()
            ->map(function ($pengampu) {
                return $pengampu->matakuliah;
            })
            ->unique('id')
            ->values(); // Re-index the collection

        // Get the Mahasiswa data
        $mahasiswa = Mahasiswa::whereIn('id', $mahasiswaIds)
            ->with(['prodi', 'kelas']);

        // Apply matakuliah filter if present
        if ($request->has('matakuliah_id') && $request->matakuliah_id != '') {
            $matakuliahId = $request->matakuliah_id;
            $mahasiswa->whereHas('pengambilanMk', function ($query) use ($matakuliahId, $pengampuIds) {
                $query->whereIn('pengampu_id', $pengampuIds) // Ensure it's from this dosen's pengampu
                      ->whereHas('pengampu', function ($q) use ($matakuliahId) {
                          $q->where('matakuliah_id', $matakuliahId);
                      });
            });
        }

        $mahasiswa = $mahasiswa->get();

        //         dd($mataKuliahDosen); // Temporarily added for debugging // Temporarily added for debugging

        return view('dosen.mahasiswa.index', compact('mahasiswa', 'mataKuliahDosen'));
    }

    public function showKrs(Mahasiswa $mahasiswa)
    {
        $krs = PengambilanMK::where('mahasiswa_id', $mahasiswa->id)
            ->with('pengampu.matakuliah', 'pengampu.dosen')
            ->get();
        return view('dosen.mahasiswa.krs', compact('mahasiswa', 'krs'));
    }

    public function showAbsensi(Mahasiswa $mahasiswa)
    {
        $absensi = Absensi::where('mahasiswa_id', $mahasiswa->id)
            ->with('jadwalKuliah.pengampu.matakuliah')
            ->get();
        return view('dosen.mahasiswa.absensi', compact('mahasiswa', 'absensi'));
    }
}
