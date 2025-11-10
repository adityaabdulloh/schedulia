<?php

namespace App\Http\Controllers;

use App\Events\PengumumanCreated;
use App\Models\JadwalKuliah;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(JadwalKuliah $jadwalKuliah)
    {
        // Eager load relationships for display
        $jadwalKuliah->load('pengampu.matakuliah', 'pengampu.dosen', 'pengampu.kelas', 'hari', 'jam', 'ruang');

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

        $pengumuman = Pengumuman::create([
            'jadwal_kuliah_id' => $request->jadwal_kuliah_id,
            'dosen_id' => $dosen->id,
            'tipe' => $request->tipe,
            'pesan' => $request->pesan,
        ]);

        // Broadcast the event
        broadcast(new PengumumanCreated($pengumuman))->toOthers();

        return redirect()->route('dosen.dashboard')->with('success', 'Pengumuman berhasil dikirim!');
    }
}
