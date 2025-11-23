<?php

namespace App\Http\Controllers;

use App\Events\PengumumanBaru;
use App\Models\JadwalKuliah;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengumuman::with('jadwalKuliah.pengampu.matakuliah', 'dosen')
            ->where('dosen_id', Auth::user()->dosen->id);

        if ($request->has('matakuliah') && $request->matakuliah != '') {
            $query->whereHas('jadwalKuliah.pengampu.matakuliah', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->matakuliah . '%');
            });
        }

        if ($request->has('tipe') && $request->tipe != '') {
            $query->where('tipe', $request->tipe);
        }

        $pengumumans = $query->latest()->paginate(10);

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
        ], [
            'pesan.required' => 'bidang ini tidak boleh kosong',
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

        // Eager load relationships for the broadcast
        $pengumuman->load('jadwalKuliah.pengampu.matakuliah', 'dosen');

        // Broadcast the new announcement event
        event(new PengumumanBaru($pengumuman));

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dikirim!');
    }

    public function indexForMahasiswa()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        // Get all pengampu_id that the student is approved for
        $pengampuIds = $mahasiswa->pengambilanMk()
            ->where('status', 'approved')
            ->pluck('pengampu_id');

        // Get all jadwal_kuliah_id for the approved courses
        $jadwalKuliahIds = JadwalKuliah::whereIn('pengampu_id', $pengampuIds)
            ->pluck('id');

        // Get announcements related to those schedules
        $pengumumans = Pengumuman::with('jadwalKuliah.pengampu.matakuliah', 'dosen')
            ->whereIn('jadwal_kuliah_id', $jadwalKuliahIds)
            ->latest()
            ->paginate(10);

        // Get schedule for today
        $hariIni = now()->dayOfWeek; // Sunday = 0, Monday = 1, etc.
        $jadwalHariIni = JadwalKuliah::whereIn('id', $jadwalKuliahIds)
            ->where('hari_id', $hariIni)
            ->orderBy('jam_mulai')
            ->get();

        // Get schedule for the week
        $jadwalSeminggu = JadwalKuliah::whereIn('id', $jadwalKuliahIds)
            ->with('hari')
            ->get()
            ->groupBy('hari.nama');

        return view('dashboard-mahasiswa', compact('pengumumans', 'jadwalHariIni', 'jadwalSeminggu', 'mahasiswa'));
    }
}
