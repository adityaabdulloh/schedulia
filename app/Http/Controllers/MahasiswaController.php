<?php

namespace App\Http\Controllers;

use App\Models\Hari;
use App\Models\JadwalKuliah;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\PengambilanMK;
use App\Models\Pengumuman;
use App\Models\Prodi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    // Menampilkan profil mahasiswa yang sedang login
    public function profil()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        return view('mahasiswa.show', compact('mahasiswa'));
    }

    // Menampilkan daftar mahasiswa (untuk admin)
    public function index(Request $request)
    {
        $query = Mahasiswa::with('prodi', 'kelas');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $lowerSearch = strtolower($search);
                $q->whereRaw('LOWER(nama) LIKE ?', ['%'.$lowerSearch.'%'])
                    ->orWhereRaw('LOWER(nim) LIKE ?', ['%'.$lowerSearch.'%']);
            });
        }

        $mahasiswas = $query->paginate(10)->appends($request->query());

        return view('mahasiswa.index', compact('mahasiswas'));
    }

    // Menampilkan dashboard mahasiswa yang sedang login
    public function studentDashboard()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        // Peta hari dari angka (Carbon) ke nama (Database)
        $dayMap = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu', // Asumsi Sabtu ada, jika tidak, tidak akan ditemukan
            0 => 'Minggu', // Asumsi Minggu ada, jika tidak, tidak akan ditemukan
        ];

        $todayName = $dayMap[Carbon::now()->dayOfWeek];

        // Ambil ID hari ini dari database
        $hari = Hari::where('nama_hari', $todayName)->first();

        $jadwalHariIni = collect(); // Default ke koleksi kosong

        // Ambil semua matakuliah_id dari PengambilanMK yang statusnya 'approved' untuk mahasiswa ini
        $approvedMatakuliahIds = PengambilanMK::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'approved')
            ->pluck('matakuliah_id')
            ->toArray();

        // Jika hari ini adalah hari kerja dan ada di database, dan ada mata kuliah yang disetujui
        if ($hari && ! empty($approvedMatakuliahIds)) {
            $jadwalHariIni = JadwalKuliah::where('kelas_id', $mahasiswa->kelas_id)
                ->where('hari_id', $hari->id)
                ->whereNotNull('jam_mulai') // Pastikan hanya jadwal yang punya jam
                ->whereHas('pengampu', function ($query) use ($approvedMatakuliahIds) {
                    $query->whereIn('matakuliah_id', $approvedMatakuliahIds);
                })
                ->with(['pengampu.matakuliah', 'pengampu.dosen', 'ruang'])
                ->orderBy('jam_mulai', 'asc')
                ->get();
        }

        // Get latest announcements for the student's class
        $pengumumans = Pengumuman::whereHas('jadwalKuliah', function ($query) use ($mahasiswa) {
            $query->where('kelas_id', $mahasiswa->kelas_id);
        })
            ->with(['dosen', 'jadwalKuliah.pengampu.matakuliah'])
            ->latest()
            ->take(5)
            ->get();

        // Ambil jadwal untuk seminggu (Senin-Jumat)
        $jadwalSeminggu = collect();
        if (!empty($approvedMatakuliahIds)) {
            $jadwalSeminggu = JadwalKuliah::where('kelas_id', $mahasiswa->kelas_id)
                ->whereNotNull('jam_mulai')
                ->whereHas('pengampu', function ($query) use ($approvedMatakuliahIds) {
                    $query->whereIn('matakuliah_id', $approvedMatakuliahIds);
                })
                ->with(['hari', 'pengampu.matakuliah', 'pengampu.dosen', 'ruang'])
                ->orderBy('hari_id', 'asc')
                ->orderBy('jam_mulai', 'asc')
                ->get()
                ->groupBy('hari.nama_hari'); // Group by day name
        }


        return view('dashboard-mahasiswa', compact('mahasiswa', 'jadwalHariIni', 'pengumumans', 'jadwalSeminggu'));
    }

    // Menampilkan form untuk menambah mahasiswa
    public function create()
    {
        $prodis = Prodi::all();
        $kelases = Kelas::all();

        return view('mahasiswa.create', compact('prodis', 'kelases'));
    }

    // Menyimpan mahasiswa baru
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|max:255|unique:mahasiswa,nim',
            'nama' => 'required',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'kelas_id' => 'required|exists:kelas,id',
            'prodi_id' => 'required',
            'semester' => 'required|integer',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat data user untuk login
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'mahasiswa',
            ]);

            // 2. Buat data mahasiswa dan hubungkan dengan user_id
            Mahasiswa::create([
                'user_id' => $user->id, // Hubungkan dengan ID dari tabel users
                'nim' => $request->nim,
                'nama' => $request->nama,
                'prodi_id' => $request->prodi_id,
                'kelas_id' => $request->kelas_id,
                'semester' => $request->semester,
            ]);
        });

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa dan user login berhasil ditambahkan.');
    }

    // Menampilkan detail mahasiswa
    public function show($id)
    {
        $mahasiswa = Mahasiswa::with(['prodi', 'kelas'])->findOrFail($id);

        return view('mahasiswa.show', compact('mahasiswa'));
    }

    // Menampilkan form untuk mengedit mahasiswa (Admin)
    public function edit($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $prodis = Prodi::all(); // Fetch all prodis
        $kelases = Kelas::all(); // Fetch all kelases

        return view('mahasiswa.edit', compact('mahasiswa', 'prodis', 'kelases'));
    }

    // Mengupdate data mahasiswa (Admin)
    public function update(Request $request, $id)
    {
        $request->validate([
            'nim' => 'required|unique:mahasiswa,nim,'.$id,
            'nama' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
            'prodi_id' => 'required',
            'semester' => 'required|integer',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $mahasiswa = Mahasiswa::findOrFail($id);

        $data = $request->except('foto_profil');

        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($mahasiswa->foto_profil) {
                Storage::delete('public/foto_profil/'.$mahasiswa->foto_profil);
            }

            // Simpan foto baru
            $path = $request->file('foto_profil')->store('public/foto_profil');
            $data['foto_profil'] = basename($path);
        }

        $mahasiswa->update($data);

        // Update nama di tabel users jika berubah
        if ($mahasiswa->user && $mahasiswa->user->name !== $request->nama) {
            $mahasiswa->user->update(['name' => $request->nama]);
        }

        return redirect()->route('mahasiswa.show', $mahasiswa->id)->with('success', 'Mahasiswa berhasil diperbarui.');
    }

    // Menampilkan form edit profil mahasiswa yang sedang login
    public function editProfil()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        $prodis = Prodi::all();
        $kelases = Kelas::all();

        return view('mahasiswa.edit-profil', compact('mahasiswa', 'prodis', 'kelases'));
    }

    // Mengupdate profil mahasiswa yang sedang login
    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        // Merge existing data for disabled fields into the request for validation
        $request->merge([
            'nim' => $mahasiswa->nim,
            'nama' => $mahasiswa->nama,
            'kelas_id' => $mahasiswa->kelas_id,
            'prodi_id' => $mahasiswa->prodi_id,
            'semester' => $mahasiswa->semester,
        ]);

        $request->validate([
            'nim' => 'required|unique:mahasiswa,nim,'.$mahasiswa->id,
            'nama' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
            'prodi_id' => 'required',
            'semester' => 'required|integer',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except('foto_profil');

        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($mahasiswa->foto_profil) {
                Storage::delete('public/foto_profil/'.$mahasiswa->foto_profil);
            }

            // Simpan foto baru
            $path = $request->file('foto_profil')->store('public/foto_profil');
            $data['foto_profil'] = basename($path);
        }

        $mahasiswa->update($data);

        // Update nama di tabel users jika berubah
        if ($mahasiswa->user && $mahasiswa->user->name !== $request->nama) {
            $mahasiswa->user->update(['name' => $request->nama]);
        }

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Profil berhasil diperbarui.');
    }

    // Menghapus mahasiswa
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $mahasiswa = Mahasiswa::findOrFail($id);
            $userId = $mahasiswa->user_id; // Get the user_id before deleting mahasiswa

            $mahasiswa->delete(); // Hapus data mahasiswa

            // Hapus juga data user yang terkait
            User::destroy($userId); // Use the retrieved user_id
        });

        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil dihapus.');
    }
}
