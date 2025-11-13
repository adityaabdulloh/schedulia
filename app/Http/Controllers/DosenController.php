<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Hari;
use App\Models\JadwalKuliah;
use App\Models\Pengampu;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DosenController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $dosen = Dosen::where('email', $user->email)->firstOrFail();

        // Ambil semua ID pengampu untuk dosen ini
        $pengampuIds = Pengampu::whereHas('dosen', function ($query) use ($dosen) {
            $query->where('dosen.id', $dosen->id);
        })->pluck('id');

        // Ambil semua jadwal kuliah untuk dosen ini, diurutkan berdasarkan hari dan jam
        $semuaJadwal = JadwalKuliah::whereIn('pengampu_id', $pengampuIds)
            ->with(['pengampu.matakuliah', 'jam', 'ruang', 'kelas', 'hari'])
            ->orderBy('hari_id', 'asc')
            ->orderBy('jam_id', 'asc')
            ->get();

        // Hitung total mata kuliah yang diampu dosen
        $totalCourses = Pengampu::whereHas('dosen', function ($query) use ($dosen) {
            $query->where('dosen.id', $dosen->id);
        })->distinct('matakuliah_id')->count('matakuliah_id');

        // Ambil semua kelas_id yang diampu oleh dosen ini
        $kelasDiampuIds = Pengampu::whereHas('dosen', function ($query) use ($dosen) {
            $query->where('dosen.id', $dosen->id);
        })->pluck('kelas_id')->unique();

        // Ambil mahasiswa yang berada di kelas-kelas yang diampu dosen ini
        $mahasiswaBimbingan = \App\Models\Mahasiswa::whereIn('kelas_id', $kelasDiampuIds)
            ->with('kelas')
            ->get();

        // Kelompokkan mahasiswa berdasarkan kelas dan hitung jumlahnya
        $mahasiswaPerKelas = $mahasiswaBimbingan->groupBy('kelas.nama_kelas')
            ->map(function ($students) {
                return $students->count();
            });

        return view('dashboard-dosen', compact('dosen', 'semuaJadwal', 'totalCourses', 'mahasiswaPerKelas'));
    }

    // Tampilkan data dosen
    public function index(Request $request)
    {
        $query = Dosen::with('prodi'); // Eager load prodi relationship

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $lowerSearch = strtolower($search);
                $q->whereRaw('LOWER(nama) LIKE ?', ['%'.$lowerSearch.'%'])
                    ->orWhereRaw('LOWER(nip) LIKE ?', ['%'.$lowerSearch.'%'])
                    ->orWhereRaw('LOWER(email) LIKE ?', ['%'.$lowerSearch.'%']);
            });
        }

        $dosen = $query->paginate(10)->appends($request->query()); // 10 items per page and append query string

        return view('dosen.index', compact('dosen'));
    }

    // Tampilkan detail data dosen
    public function show(Dosen $dosen)
    {
        return view('dosen.show', compact('dosen'));
    }

    // Form untuk tambah data dosen
    public function create()
    {
        $prodi = Prodi::all(); // Ambil semua data prodi

        return view('dosen.create', compact('prodi'));
    }

    // Simpan data dosen
    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:dosen,nip',
            'nama' => 'required',
            'email' => 'required|email|unique:dosen,email',
            'prodi_id' => 'required|exists:prodi,id',
        ]);

        DB::transaction(function () use ($request) {
            $dosen = Dosen::create($request->all());

            User::create([
                'name' => $dosen->nama,
                'email' => $dosen->email,
                'password' => Hash::make('password'), // default password
                'role' => 'dosen',
            ]);
        });

        session()->flash('success', 'Data dosen berhasil ditambahkan.');

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil ditambahkan.');
    }

    // Form untuk edit data dosen
    public function edit(Dosen $dosen)
    {
        $prodi = Prodi::all(); // Ambil semua data prodi

        return view('dosen.edit', compact('dosen', 'prodi'));
    }

    // Update data dosen
    public function update(Request $request, Dosen $dosen)
    {
        $request->validate([
            'nip' => 'required|unique:dosen,nip,'.$dosen->id,
            'nama' => 'required',
            'email' => 'required|email|unique:dosen,email,'.$dosen->id,
            'prodi_id' => 'required|exists:prodi,id',
            'foto_profil' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        DB::transaction(function () use ($request, $dosen) {
            $oldEmail = $dosen->email;
            $data = $request->all();

            if ($request->hasFile('foto_profil')) {
                // Hapus foto lama jika ada
                if ($dosen->foto_profil) {
                    Storage::delete('public/foto_profil/'.$dosen->foto_profil);
                }

                $fileName = time().'.'.$request->foto_profil->extension();
                Log::info('Uploading file: '.$fileName);
                $request->foto_profil->storeAs('public/foto_profil', $fileName);
                $data['foto_profil'] = $fileName;
            }

            $dosen->update($data);

            $user = User::where('email', $oldEmail)->first();
            if ($user) {
                $user->update([
                    'name' => $dosen->nama,
                    'email' => $dosen->email,
                ]);
            }
        });

        return redirect()->route('dosen.show', $dosen->id)->with('success', 'Profil dosen berhasil diperbarui.');
    }

    // Hapus data dosen
    public function destroy(Dosen $dosen)
    {
        DB::transaction(function () use ($dosen) {
            $user = User::where('email', $dosen->email)->first();
            if ($user) {
                $user->delete();
            }
            $dosen->delete();
        });

        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil dihapus.');
    }

    public function profil()
    {
        $dosen = auth()->user()->dosen; // Assuming a 'dosen' relationship on the User model

        return view('dosen.profil', compact('dosen'));
    }

    public function editProfile()
    {
        $dosen = auth()->user()->dosen;
        $prodi = Prodi::all(); // Assuming Prodi model exists for dropdown

        return view('dosen.edit-profile', compact('dosen', 'prodi'));
    }

    public function updateProfile(Request $request)
    {
        $dosen = auth()->user()->dosen;

        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:255|unique:dosen,nip,'.$dosen->id,
            'email' => 'required|string|email|max:255|unique:dosen,email,'.$dosen->id,
            'prodi_id' => 'required|exists:prodi,id',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($request, $dosen) {
            $oldEmail = $dosen->email;
            $data = $request->only(['nama', 'nip', 'email', 'prodi_id']);

            if ($request->hasFile('foto_profil')) {
                // Delete old profile photo if exists
                if ($dosen->foto_profil) {
                    Storage::delete('public/'.$dosen->foto_profil);
                }
                $path = $request->file('foto_profil')->store('public/foto_profil');
                $data['foto_profil'] = str_replace('public/', '', $path);
            } elseif ($request->has('remove_foto_profil') && $request->input('remove_foto_profil') == 1) {
                // Remove existing profile photo
                if ($dosen->foto_profil) {
                    Storage::delete('public/'.$dosen->foto_profil);
                }
                $data['foto_profil'] = null;
            }

            $dosen->update($data);

            // Update associated User model if email changed
            if ($oldEmail !== $dosen->email) {
                $user = auth()->user();
                $user->update([
                    'name' => $dosen->nama,
                    'email' => $dosen->email,
                ]);
            } else {
                // Only update name if email didn't change
                $user = auth()->user();
                $user->update([
                    'name' => $dosen->nama,
                ]);
            }

            // Update password if provided
            if ($request->filled('password')) {
                $user = auth()->user();
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }
        });

        return redirect()->route('dosen.dashboard')->with('success', 'Profil berhasil diperbarui.');
    }
}
