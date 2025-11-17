<?php

namespace App\Http\Controllers;

use App\Models\JadwalKuliah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (! $mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan untuk pengguna ini.');
        }

        // Get approved PengambilanMK entries for the student
        $approvedPengambilanMKs = \App\Models\PengambilanMK::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'approved')
            ->pluck('matakuliah_id');

        // Get JadwalKuliah entries that correspond to the approved matakuliah_id's and the student's class
        $jadwalKuliah = JadwalKuliah::with(['hari', 'ruang', 'pengampu.matakuliah', 'pengampu.dosen', 'pengampu.kelas'])
            ->where('kelas_id', $mahasiswa->kelas_id)
            ->whereHas('pengampu', function ($query) use ($approvedPengambilanMKs) {
                $query->whereIn('matakuliah_id', $approvedPengambilanMKs);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('pengampu.matakuliah', function ($subq) use ($search) {
                        $subq->where('nama_matakuliah', 'like', '%'.$search.'%');
                    })->orWhereHas('pengampu.dosen', function ($subq) use ($search) {
                        $subq->where('nama', 'like', '%'.$search.'%');
                    })->orWhereHas('ruang', function ($subq) use ($search) {
                        $subq->where('nama_ruang', 'like', '%'.$search.'%');
                    })->orWhereHas('hari', function ($subq) use ($search) {
                        $subq->where('nama_hari', 'like', '%'.$search.'%');
                    })->orWhere('jam_mulai', 'like', '%'.$search.'%');
                });
            })->paginate(10);

        return view('jadwalmahasiswa.index', compact('jadwalKuliah', 'search'));
    }

    public function exportPDF(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (! $mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan untuk pengguna ini.');
        }

        // Get approved PengambilanMK entries for the student
        $approvedPengambilanMKs = \App\Models\PengambilanMK::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'approved')
            ->pluck('matakuliah_id');

        // Get JadwalKuliah entries that correspond to the approved matakuliah_id's and the student's class
        $jadwalKuliah = JadwalKuliah::with(['hari', 'ruang', 'pengampu.matakuliah', 'pengampu.dosen', 'pengampu.kelas'])
            ->where('kelas_id', $mahasiswa->kelas_id)
            ->whereHas('pengampu', function ($query) use ($approvedPengambilanMKs) {
                $query->whereIn('matakuliah_id', $approvedPengambilanMKs);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('pengampu.matakuliah', function ($subq) use ($search) {
                        $subq->where('nama_matakuliah', 'like', '%'.$search.'%');
                    })->orWhereHas('pengampu.dosen', function ($subq) use ($search) {
                        $subq->where('nama', 'like', '%'.$search.'%');
                    })->orWhereHas('ruang', function ($subq) use ($search) {
                        $subq->where('nama_ruang', 'like', '%'.$search.'%');
                    })->orWhereHas('hari', function ($subq) use ($search) {
                        $subq->where('nama_hari', 'like', '%'.$search.'%');
                    })->orWhere('jam_mulai', 'like', '%'.$search.'%');
                });
            })->get();

        $pdf = Pdf::loadView('jadwalmahasiswa.pdf', compact('jadwalKuliah'));

        return $pdf->stream('jadwal-kuliah-'.$mahasiswa->nim.'.pdf');
    }
}
