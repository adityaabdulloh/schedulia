<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Matakuliah;
use App\Models\Pengampu;
use App\Models\Prodi; // Import Prodi model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengampuController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Pengampu::query();

        // Check if the user is logged in and has a role
        if ($user) {
            if ($user->role === 'dosen') {
                if (! $user->dosen) {
                    $pengampus = $query->whereRaw('1 = 0')->paginate(10);

                    return view('pengampu.index', compact('pengampus'));
                }
                $dosenId = $user->dosen->id;

                $query->whereHas('dosen', function ($q2) use ($dosenId) { // Check additional lecturers via pivot table
                            $q2->where('dosen.id', $dosenId);
                        });
            }
            // If the user is 'admin', no additional filtering is needed based on user ID.
            // The query will proceed to apply search filters if any.
        } else {
            // If no user is logged in, perhaps redirect to login or show empty data
            return redirect()->route('login')->with('error', 'Silakan login untuk mengakses halaman ini.');
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereHas('matakuliah', function ($q2) use ($search) {
                    $q2->whereRaw('LOWER(nama) LIKE ?', ["%{$search}%"]);
                })->orWhereHas('Kelas', function ($q2) use ($search) {
                    $q2->whereRaw('LOWER(nama_kelas) LIKE ?', ["%{$search}%"]);
                })->orWhereHas('dosen', function ($q2) use ($search) {
                    $q2->whereRaw('LOWER(nama) LIKE ?', ["%{$search}%"]);
                })->orWhereRaw('LOWER(tahun_akademik) LIKE ?', ["%{$search}%"]);
            });
        }

        $pengampus = $query->with(['dosen', 'matakuliah', 'Kelas', 'prodi'])->paginate(10);

        return view('pengampu.index', compact('pengampus'));
    }

    public function create()
    {
        $prodis = Prodi::all(); // Get all prodi for dropdown

        return view('pengampu.create', [
            'prodis' => $prodis,
            'matakuliahs' => [], // Initially empty
            'dosens' => [], // Initially empty
            'kelas' => Kelas::all(),
            'dosens_data' => [],
        ]);
    }

    public function getMatakuliahDosen($prodi_id)
    {
        $matakuliahs = Matakuliah::where('prodi_id', $prodi_id)->get();
        $dosens = Dosen::where('prodi_id', $prodi_id)->get();

        return response()->json([
            'matakuliahs' => $matakuliahs,
            'dosens' => $dosens,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'dosen1' => 'required|exists:dosen,id',
            'dosen2' => 'nullable|exists:dosen,id|different:dosen1',
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_akademik' => 'required',
            'prodi_id' => 'required|exists:prodi,id',
        ], [
            'dosen2.different' => 'Dosen tidak boleh sama.',
        ]);

        DB::transaction(function () use ($request) {
            $pengampu = Pengampu::create([
                'matakuliah_id' => $request->matakuliah_id,
                'kelas_id' => $request->kelas_id,
                'tahun_akademik' => $request->tahun_akademik,
                'prodi_id' => $request->prodi_id,
            ]);

            // Kumpulkan dosen yang valid dan siapkan data pivot
            $dosenPivotData = [];
            $kelasId = $request->kelas_id;

            $dosenIdsFromRequest = array_filter([
                $request->dosen1,
                $request->dosen2,
            ]);

            foreach ($dosenIdsFromRequest as $dosenId) {
                $dosenPivotData[$dosenId] = ['kelas_id' => $kelasId];
            }

            // Tambahkan dosen ke pengampu
            $pengampu->dosen()->sync($dosenPivotData);
        });

        return redirect()->route('pengampu.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(Pengampu $pengampu)
    {
        $pengampu->load('dosen'); // Eager-load the 'dosen' relationship
        $dosens = Dosen::with('prodi')->get();
        $matakuliahs = Matakuliah::all();
        $kelas = Kelas::all();
        $prodis = Prodi::all(); // Get all prodi for dropdown

        return view('pengampu.edit', compact('pengampu', 'dosens', 'matakuliahs', 'kelas', 'prodis'));
    }

    public function update(Request $request, Pengampu $pengampu)
    {
        $request->validate([
            'dosen1' => 'required|exists:dosen,id',
            'dosen2' => 'nullable|exists:dosen,id|different:dosen1',
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_akademik' => 'required',
            'prodi_id' => 'required|exists:prodi,id',
        ], [
            'dosen2.different' => 'Dosen tidak boleh sama.',
        ]);

        DB::transaction(function () use ($request, $pengampu) {
            $pengampu->update([
                'matakuliah_id' => $request->matakuliah_id,
                'kelas_id' => $request->kelas_id,
                'tahun_akademik' => $request->tahun_akademik,
                'prodi_id' => $request->prodi_id,
            ]);

            // Kumpulkan dosen yang valid dan siapkan data pivot
            $dosenPivotData = [];
            $kelasId = $request->kelas_id;

            $dosenIdsFromRequest = array_filter([
                $request->dosen1,
                $request->dosen2,
            ]);

            foreach ($dosenIdsFromRequest as $dosenId) {
                $dosenPivotData[$dosenId] = ['kelas_id' => $kelasId];
            }

            // Update dosen-dosen yang mengampu
            $pengampu->dosen()->sync($dosenPivotData);
        });

        return redirect()->route('pengampu.index')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy(Pengampu $pengampu)
    {
        $pengampu->delete();

        return redirect()->route('pengampu.index')->with('success', 'Data berhasil dihapus');
    }

    public function testDosenRoute()
    {
        return 'This is a test route for pengampu/dosen';
    }
}
