<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\Pengampu;
use App\Models\PengambilanMK; // Add this line
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengambilanMKController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure user is authenticated
        $this->middleware('role:admin')->except(['indexForStudent', 'createForStudent', 'storeForStudent', 'destroyForStudent', 'exportKRS_PDF']); // Only admin can access admin methods
    }

    // ===================================================================
    // METHOD UNTUK MAHASISWA
    // ===================================================================

    public function indexForStudent()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $pengambilanMKs = PengambilanMK::where('mahasiswa_id', $mahasiswa->id)->with('matakuliah')->get();

        return view('pengambilanmk.krs', compact('pengambilanMKs'));
    }

    public function createForStudent()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        // Ambil ID matakuliah yang sudah diambil dan divalidasi (status 'approved') oleh mahasiswa
        $approvedPengampuIds = PengambilanMK::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'approved')
            ->pluck('pengampu_id')
            ->toArray();

        // Ambil semua matakuliah yang sesuai dengan prodi mahasiswa dan semester mahasiswa saat ini,
        // dan yang belum diambil serta divalidasi
        $pengampuTersedia = Pengampu::where('prodi_id', $mahasiswa->prodi_id)
            ->whereHas('matakuliah', function ($query) use ($mahasiswa) {
                $query->where('semester', $mahasiswa->semester);
            })
            ->whereNotIn('id', $approvedPengampuIds)
            ->with(['matakuliah', 'dosen', 'kelas'])
            ->get();

        // Ambil ID matakuliah yang sudah diambil oleh mahasiswa (termasuk pending)
        // Ini mungkin tidak lagi diperlukan di view jika kita sudah memfilter matakuliahTersedia
        // Namun, jika view masih menggunakannya untuk menampilkan status "sudah diambil" untuk pending, kita bisa biarkan.
        $diambilPengampuIds = PengambilanMK::where('mahasiswa_id', $mahasiswa->id)->pluck('pengampu_id')->toArray();

        return view('pengambilanmk.create', compact('pengampuTersedia', 'diambilPengampuIds'));
    }

    public function storeForStudent(Request $request)
    {
        $request->validate([
            'pengampu_id' => 'required|exists:pengampu,id',
        ]);

        $mahasiswa = Auth::user()->mahasiswa;
        $pengampu = Pengampu::find($request->pengampu_id);

        // Cek apakah sudah diambil
        $isExist = PengambilanMK::where('mahasiswa_id', $mahasiswa->id)
            ->where('pengampu_id', $request->pengampu_id)
            ->exists();

        if (! $isExist) {
            PengambilanMK::create([
                'mahasiswa_id' => $mahasiswa->id,
                'pengampu_id' => $pengampu->id,
                'matakuliah_id' => $pengampu->matakuliah_id,
                'semester' => $mahasiswa->semester, // Asumsi semester diambil dari data mahasiswa
                'status' => 'pending',
            ]);

            return redirect()->route('pengambilan-mk.create')->with('success', 'Mata kuliah berhasil diambil.');
        }

        return redirect()->route('pengambilan-mk.create')->with('error', 'Mata kuliah sudah Anda ambil sebelumnya.');
    }

    public function destroyForStudent($pengampu_id)
    {
        $mahasiswa = Auth::user()->mahasiswa;

        $pengambilanMK = PengambilanMK::where('mahasiswa_id', $mahasiswa->id)
            ->where('pengampu_id', $pengampu_id)
            ->first();

        if ($pengambilanMK) {
            $pengambilanMK->delete();

            return redirect()->route('pengambilan-mk.create')->with('success', 'Mata kuliah berhasil dilepas.');
        }

        return redirect()->route('pengambilan-mk.create')->with('error', 'Mata kuliah tidak ditemukan.');
    }

    public function exportKRS_PDF()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $pengambilanMKs = PengambilanMK::where('mahasiswa_id', $mahasiswa->id)->with('matakuliah')->get();

        $pdf = PDF::loadView('pengambilanmk.krs_pdf', compact('pengambilanMKs', 'mahasiswa'));

        return $pdf->stream('krs-'.$mahasiswa->nim.'.pdf');
    }

    // ===================================================================
    // METHOD UNTUK ADMIN (SUDAH ADA SEBELUMNYA)
    // ===================================================================

    // Menampilkan daftar pengambilan matakuliah
    public function index(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status');
        $perPage = $request->input('per_page', 10);

        $query = PengambilanMK::with('mahasiswa', 'matakuliah');

        if ($search) {
            $query->whereHas('mahasiswa', function ($q) use ($search) {
                $q->where('nama', 'like', '%'.$search.'%')
                    ->orWhere('nim', 'like', '%'.$search.'%');
            })->orWhereHas('matakuliah', function ($q) use ($search) {
                $q->where('nama', 'like', '%'.$search.'%')
                    ->orWhere('kode_mk', 'like', '%'.$search.'%');
            });
        }

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $pengambilanMKs = $query->paginate($perPage);

        return view('pengambilan_mk.index', compact('pengambilanMKs', 'search', 'statusFilter', 'perPage'));
    }

    // Menampilkan form untuk membuat pengambilan matakuliah baru
    public function create()
    {
        $mahasiswas = Mahasiswa::all();
        $matakuliahs = Matakuliah::all();

        return view('pengambilan_mk.create', compact('mahasiswas', 'matakuliahs'));
    }

    // Menyimpan pengambilan matakuliah baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'semester' => 'required|integer',
            'tahun_akademik' => 'required|string|max:9',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        PengambilanMK::create($request->all());

        return redirect()->route('pengambilan_mk.index')->with('success', 'Pengambilan matakuliah berhasil ditambahkan.');
    }

    // Menampilkan detail pengambilan matakuliah
    public function show($id)
    {
        $pengambilanMK = PengambilanMK::findOrFail($id);

        return view('pengambilan_mk.show', compact('pengambilanMK'));
    }

    // Menampilkan form untuk mengedit pengambilan matakuliah
    public function edit($id)
    {
        $pengambilanMK = PengambilanMK::findOrFail($id);
        $mahasiswas = Mahasiswa::all();
        $matakuliahs = Matakuliah::all();

        return view('pengambilan_mk.edit', compact('pengambilanMK', 'mahasiswas', 'matakuliahs'));
    }

    // Memperbarui pengambilan matakuliah di database
    public function update(Request $request, $id)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'semester' => 'required|integer',
            'tahun_akademik' => 'required|string|max:9',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $pengambilanMK = PengambilanMK::findOrFail($id);
        $pengambilanMK->update($request->all());

        return redirect()->route('pengambilan_mk.index')->with('success', 'Pengambilan matakuliah berhasil diperbarui.');
    }

    // Menghapus pengambilan matakuliah dari database
    public function destroy($id)
    {
        $pengambilanMK = PengambilanMK::findOrFail($id);
        $pengambilanMK->delete();

        return redirect()->route('pengambilan_mk.index')->with('success', 'Pengambilan matakuliah berhasil dihapus.');
    }

    // METHOD BARU UNTUK ADMIN VALIDASI
    public function adminValidationIndex()
    {
        $pendingPengambilanMKs = PengambilanMK::where('status', 'pending')->with('mahasiswa', 'matakuliah')->get();

        return view('pengambilanmk.admin_validation', compact('pendingPengambilanMKs'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $pengambilanMK = PengambilanMK::findOrFail($id);
        $pengambilanMK->status = $request->status;
        $pengambilanMK->save();

        return redirect()->route('admin.pengambilanmk.validation.index')->with('success', 'Status pengambilan mata kuliah berhasil diperbarui.');
    }

    public function showStudentKRS(Mahasiswa $mahasiswa)
    {
        $pengambilanMKs = PengambilanMK::where('mahasiswa_id', $mahasiswa->id)->with('matakuliah')->get();

        return view('pengambilanmk.student_krs_detail', compact('pengambilanMKs', 'mahasiswa'));
    }

    public function approveAllPending(Mahasiswa $mahasiswa)
    {
        PengambilanMK::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'pending')
            ->update(['status' => 'approved']);

        return redirect()->route('admin.mahasiswa.krs.show', $mahasiswa->id)->with('success', 'Semua mata kuliah yang tertunda berhasil disetujui.');
    }

    public function indexForAdmin()
    {
        return view('admin.pengambilan-mk');
    }
}
