<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Dosen;
use App\Models\Pengampu;
use App\Models\PengambilanMK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AbsensiDosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            // Handle case where the user is not a lecturer
            return redirect()->back()->with('error', 'Anda tidak terdaftar sebagai dosen.');
        }

        $pengampu = Pengampu::where('dosen_id', $dosen->id)
            ->with(['matakuliah', 'kelas', 'prodi'])
            ->get();

        return view('dosen.absensi.index', compact('pengampu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Pengampu $pengampu)
    {
        $request->validate([
            'pertemuan' => 'required|integer|min:1|max:16',
            'status' => 'required|array',
            'status.*' => ['required', Rule::in(['hadir', 'sakit', 'izin', 'alpha'])],
        ]);

        $pertemuan = $request->input('pertemuan');
        $statuses = $request->input('status');
        $mahasiswaIds = $request->input('mahasiswa_id');

        foreach ($mahasiswaIds as $mahasiswaId) {
            Absensi::updateOrCreate(
                [
                    'pengampu_id' => $pengampu->id,
                    'mahasiswa_id' => $mahasiswaId,
                    'pertemuan' => $pertemuan,
                ],
                [
                    'status' => $statuses[$mahasiswaId],
                ]
            );
        }

        return redirect()->back()->with('success', 'Absensi untuk pertemuan ke-' . $pertemuan . ' berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengampu $pengampu)
    {
        $mahasiswa = PengambilanMK::where('pengampu_id', $pengampu->id)
            ->where('status', 'disetujui')
            ->with('mahasiswa')
            ->get();

        return view('dosen.absensi.show', compact('pengampu', 'mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}