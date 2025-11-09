<?php

namespace App\Http\Controllers;

use App\Models\JadwalKuliah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalDosenController extends Controller
{
    /**
     * Menampilkan semua data jadwal kuliah.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user();
        $dosen = $user->dosen;

        $jadwalKuliahQuery = JadwalKuliah::whereHas('pengampu.dosen', function ($query) use ($dosen) {
            $query->where('dosen.id', $dosen->id);
        });

        if ($search) {
            $jadwalKuliahQuery->where(function ($query) use ($search) {
                $query->orWhereHas('hari', function ($q) use ($search) {
                    $q->where('nama_hari', 'like', '%'.$search.'%');
                })->orWhereHas('pengampu.matakuliah', function ($q) use ($search) {
                    $q->where('nama', 'like', '%'.$search.'%');
                })->orWhereHas('jam', function ($q) use ($search) {
                    $q->where('jam_mulai', 'like', '%'.$search.'%');
                })->orWhereHas('ruang', function ($q) use ($search) {
                    $q->where('nama_ruang', 'like', '%'.$search.'%');
                })->orWhereHas('kelas', function ($q) use ($search) {
                    $q->where('nama_kelas', 'like', '%'.$search.'%');
                });
            });
        }

        $jadwalKuliah = $jadwalKuliahQuery->paginate(10);

        return view('jadwaldosen.index', compact('jadwalKuliah', 'search'));
    }

    public function exportPDF(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user();
        $dosen = $user->dosen;

        if (! $dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan untuk pengguna ini.');
        }

        $jadwalKuliah = JadwalKuliah::whereHas('pengampu.dosen', function ($query) use ($dosen) {
            $query->where('dosen.id', $dosen->id);
        })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->orWhereHas('hari', function ($subq) use ($search) {
                        $subq->where('nama_hari', 'like', '%'.$search.'%');
                    })->orWhereHas('pengampu.matakuliah', function ($subq) use ($search) {
                        $subq->where('nama', 'like', '%'.$search.'%');
                    })->orWhereHas('jam', function ($subq) use ($search) {
                        $subq->where('jam_mulai', 'like', '%'.$search.'%');
                    })->orWhereHas('ruang', function ($subq) use ($search) {
                        $subq->where('nama_ruang', 'like', '%'.$search.'%');
                    })->orWhereHas('pengampu.kelas', function ($subq) use ($search) {
                        $subq->where('nama_kelas', 'like', '%'.$search.'%');
                    });
                });
            })
            ->get();

        $pdf = Pdf::loadView('jadwaldosen.pdf', compact('jadwalKuliah'));

        return $pdf->stream('jadwal-mengajar-'.$dosen->nip.'.pdf');
    }

    /**
     * Menyalin data dari tabel jadwal_kuliah ke tabel jadwal_dosen.
     */
}
