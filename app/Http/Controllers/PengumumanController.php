<?php

namespace App\Http\Controllers;

use App\Models\JadwalKuliah;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumumans = Pengumuman::with('jadwalKuliah.pengampu.matakuliah', 'dosen')
            ->where('dosen_id', Auth::user()->dosen->id)
            ->latest()
            ->paginate(10);

        return view('pengumuman.index', compact('pengumumans'));
    }

    public function show(Pengumuman $pengumuman)
    {
        $pengumuman->load('jadwalKuliah.pengampu.matakuliah', 'jadwalKuliah.pengampu.kelas', 'jadwalKuliah.hari', 'jadwalKuliah.ruang', 'dosen');

        return view('pengumuman.show', compact('pengumuman'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(JadwalKuliah $jadwalKuliah)
    {
        // Eager load relationships for display
        $jadwalKuliah->load('pengampu.matakuliah', 'pengampu.dosen', 'pengampu.kelas', 'hari', 'ruang');

        return view('pengumuman.create', compact('jadwalKuliah'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jadwal_kuliah_id' => 'required|exists:jadwal_kuliah,id',
            'tipe' => 'required|in:perubahan,pembatalan,informasi',
            'pesan' => 'required|string|max:1000',
        ]);

        // Get the authenticated user's related Dosen model
        $dosen = Auth::user()->dosen;

        if (! $dosen) {
            return redirect()->back()->with('error', 'Hanya dosen yang bisa membuat pengumuman.');
        }

        Pengumuman::create([
            'jadwal_kuliah_id' => $request->jadwal_kuliah_id,
            'dosen_id' => $dosen->id,
            'tipe' => $request->tipe,
            'pesan' => $request->pesan,
        ]);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dikirim!');
    }
}
