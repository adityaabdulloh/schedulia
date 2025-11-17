<?php

namespace App\Http\Controllers;

use App\Models\Matakuliah;
use App\Models\Prodi;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class MatakuliahController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $prodiFilter = $request->input('prodi_id');
        $semesterFilter = $request->input('semester');

        $query = Matakuliah::with('prodi');

        if ($search) {
            $query->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($search) . '%']);
        }

        if ($prodiFilter) {
            $query->where('prodi_id', $prodiFilter);
        }

        if ($semesterFilter) {
            $query->where('semester', $semesterFilter);
        }

        $mataKuliah = $query->paginate(10);
        $prodi = Prodi::all(); // Fetch all Prodi for the filter dropdown

        return view('matakuliah.index', compact('mataKuliah', 'prodi', 'prodiFilter', 'semesterFilter'));
    }

    // Menampilkan form untuk menambah matakuliah
    public function create()
    {
        $prodi = Prodi::all();

        return view('matakuliah.create', compact('prodi'));
    }

    // Menyimpan matakuliah baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode_mk' => 'required|unique:matakuliah,kode_mk',
            'nama' => 'required',
            'sks' => 'required|numeric|min:1',
            'semester' => 'required|numeric|min:1|max:8',
            'prodi_id' => 'required|exists:prodi,id',
        ], [
            'kode_mk.required' => 'Kode mata kuliah wajib diisi',
            'kode_mk.unique' => 'Kode mata kuliah sudah digunakan',
            'nama.required' => 'Nama mata kuliah wajib diisi',
            'sks.required' => 'SKS wajib diisi',
            'sks.numeric' => 'SKS harus berupa angka',
            'semester.required' => 'Semester wajib diisi',
            'semester.numeric' => 'Semester harus berupa angka',
            'semester.min' => 'Semester minimal 1',
            'semester.max' => 'Semester maksimal 8',
            'prodi_id.required' => 'Program studi wajib dipilih',
        ]);

        try {
            MataKuliah::create($request->all());

            return redirect()->route('matakuliah.index')
                ->with('success', 'Data mata kuliah berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data')
                ->withInput();
        }
    }

    // Menampilkan form untuk mengedit matakuliah
    public function edit($id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);
        $prodi = Prodi::all();

        return view('matakuliah.edit', compact('mataKuliah', 'prodi'));
    }

    public function update(Request $request, $id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);

        // Validasi input
        $request->validate([
            'kode_mk' => 'required|unique:matakuliah,kode_mk,'.$id,
            'nama' => 'required',
            'sks' => 'required|numeric|min:1',
            'semester' => 'required|numeric|min:1|max:8',
            'prodi_id' => 'required|exists:prodi,id',
        ], [
            'kode_mk.required' => 'Kode mata kuliah wajib diisi',
            'kode_mk.unique' => 'Kode mata kuliah sudah digunakan',
            'nama.required' => 'Nama mata kuliah wajib diisi',
            'sks.required' => 'SKS wajib diisi',
            'sks.numeric' => 'SKS harus berupa angka',
            'semester.required' => 'Semester wajib diisi',
            'semester.numeric' => 'Semester harus berupa angka',
            'semester.min' => 'Semester minimal 1',
            'semester.max' => 'Semester maksimal 8',
            'prodi_id.required' => 'Program studi wajib dipilih',
        ]);

        try {
            $mataKuliah->update($request->all());

            return redirect()->route('matakuliah.index')
                ->with('success', 'Data mata kuliah berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data')
                ->withInput();
        }
    }

    // Menghapus matakuliah
    public function destroy($id)
    {
        $matakuliah = Matakuliah::findOrFail($id);
        $matakuliah->delete();

        return redirect()->route('matakuliah.index')->with('success', 'Matakuliah berhasil dihapus.');
    }

    public function getNextCourseCode($prodi_id)
    {
        if ($prodi_id == 0) {
            return response()->json('');
        }

        $prodi = Prodi::findOrFail($prodi_id);
        $nama_prodi = $prodi->nama_prodi;

        $prefix = '';
        if (str_contains(strtolower($nama_prodi), 'teknik informatika')) {
            $prefix = 'TI-';
        } elseif (str_contains(strtolower($nama_prodi), 'sistem informasi')) {
            $prefix = 'SI-';
        } elseif (str_contains(strtolower($nama_prodi), 'pendidikan teknologi informasi')) {
            $prefix = 'PTI-';
        }

        $latestCourse = Matakuliah::where('prodi_id', $prodi_id)
            ->where('kode_mk', 'like', $prefix.'%')
            ->orderBy('kode_mk', 'desc')
            ->first();

        $nextNumber = 1;
        if ($latestCourse) {
            $lastNumber = (int) substr($latestCourse->kode_mk, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        }

        $newCode = $prefix.str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return response()->json($newCode);
    }
}
