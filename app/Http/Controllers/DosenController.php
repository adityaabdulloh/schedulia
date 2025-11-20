<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Dosen;
use App\Models\Hari;
use App\Models\JadwalKuliah;
use App\Models\Mahasiswa;
use App\Models\Pengampu;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon; // For current date

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

        // Get current day name and find its ID
        $hariIni = Carbon::now()->locale('id')->dayName;
        $hari = Hari::where('nama_hari', $hariIni)->first();
        $hariId = $hari ? $hari->id : -1; // Use -1 if day not found to avoid errors

        // Ambil jadwal kuliah untuk dosen ini pada hari ini
        $jadwalHariIni = JadwalKuliah::whereIn('pengampu_id', $pengampuIds)
            ->where('hari_id', $hariId) // Filter by current day
            ->with(['pengampu.matakuliah', 'ruang', 'kelas', 'hari'])
            ->orderBy('jam_mulai', 'asc')
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

        return view('dashboard-dosen', compact('dosen', 'jadwalHariIni', 'totalCourses', 'mahasiswaPerKelas'));
    }

    // Tampilkan data dosen
    public function index(Request $request)
    {
        $search = $request->input('search');
        $prodiFilter = $request->input('prodi_id');

        $query = Dosen::with('prodi'); // Eager load prodi relationship

        if ($search) {
            $query->where(function ($q) use ($search) {
                $lowerSearch = strtolower($search);
                $q->whereRaw('LOWER(nama) LIKE ?', ['%'.$lowerSearch.'%'])
                    ->orWhereRaw('LOWER(nip) LIKE ?', ['%'.$lowerSearch.'%'])
                    ->orWhereRaw('LOWER(email) LIKE ?', ['%'.$lowerSearch.'%']);
            });
        }

        if ($prodiFilter) {
            $query->where('prodi_id', $prodiFilter);
        }

        $dosen = $query->paginate(10)->appends($request->query()); // 10 items per page and append query string
        $prodi = Prodi::all(); // Fetch all Prodi for the filter dropdown

        return view('dosen.index', compact('dosen', 'prodi', 'prodiFilter'));
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
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Added validation
        ]);

        DB::transaction(function () use ($request) {
            $data = $request->all(); // Get all request data

            if ($request->hasFile('foto_profil')) {
                $fileName = time().'.'.$request->foto_profil->extension();
                $request->foto_profil->storeAs('public/foto_profil', $fileName);
                $data['foto_profil'] = $fileName;
            } else {
                $data['foto_profil'] = null; // Ensure it's null if no file is uploaded
            }

            $dosen = Dosen::create($data); // Create Dosen with processed data

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
        $hide_sidebar = true;
        return view('dosen.profil', compact('dosen', 'hide_sidebar'));
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

    public function absensiIndex()
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->firstOrFail();

        $pengampuCourses = $dosen->pengampus()
                                    ->with('matakuliah', 'kelas')
                                    ->get();

        return view('dosen.absensi.index', compact('pengampuCourses'));
    }

    public function takeAbsensi(Pengampu $pengampu)
    {
        // Ensure the logged-in dosen is the one teaching this course
        $userId = Auth::id();
        $dosen = Dosen::where('user_id', $userId)->firstOrFail();

        if (!$pengampu->dosen->contains($dosen->id)) {
            abort(403, 'Unauthorized action.');
        }

        // Get students enrolled in this specific Pengampu (course)
        $mahasiswas = Mahasiswa::whereHas('pengambilanMk', function ($query) use ($pengampu) {
            $query->where('pengampu_id', $pengampu->id);
        })->with('prodi')->get(); // Eager load prodi for display

        // Get all JadwalKuliah for this Pengampu, ensuring they have required relations
        $jadwalKuliahs = JadwalKuliah::where('pengampu_id', $pengampu->id)
                                    ->whereHas('hari')
                                    ->whereHas('ruang')
                                    ->with('hari', 'ruang')
                                    ->orderBy('hari_id')
                                    ->orderBy('jam_mulai')
                                    ->get();

        // Determine the current day's ID using Indonesian locale
        $currentDayName = Carbon::now()->locale('id')->dayName;
        $hari = Hari::where('nama_hari', $currentDayName)->first();
        $currentDayId = $hari ? $hari->id : null;

        $currentTime = Carbon::now()->format('H:i:s');

        $selectedJadwalKuliah = null;
        if ($currentDayId) {
            // Filter schedules for the current day
            $jadwalHariIni = $jadwalKuliahs->where('hari_id', $currentDayId);

            // Find a schedule that is currently active
            $selectedJadwalKuliah = $jadwalHariIni->first(function ($jadwal) use ($currentTime) {
                return $currentTime >= $jadwal->jam_mulai && $currentTime <= $jadwal->jam_selesai;
            });

            // If no schedule is active right now, take the first one for today
            if (!$selectedJadwalKuliah) {
                $selectedJadwalKuliah = $jadwalHariIni->first();
            }
        }

        // If no schedule found for today, just pick the first one available for the course
        if (!$selectedJadwalKuliah && $jadwalKuliahs->isNotEmpty()) {
            $selectedJadwalKuliah = $jadwalKuliahs->first();
        }
        
        // Determine the next logical 'pertemuan' number
        $nextPertemuan = Absensi::where('pengampu_id', $pengampu->id)
                                ->max('pertemuan');
        $nextPertemuan = $nextPertemuan ? $nextPertemuan + 1 : 1; // If no attendance yet, start at 1

        return view('dosen.absensi.take', compact('pengampu', 'mahasiswas', 'jadwalKuliahs', 'selectedJadwalKuliah', 'nextPertemuan'));
    }

    public function storeAbsensi(Request $request, Pengampu $pengampu)
    {
        $request->validate([
            'absensi' => 'required|array',
            'absensi.*' => 'required|in:hadir,izin,sakit,alpha',
            'pertemuan' => 'required|integer|min:1',
            'jadwal_kuliah_id' => 'required|exists:jadwal_kuliah,id',
        ]);

        $userId = Auth::id();
        $dosen = Dosen::where('user_id', $userId)->firstOrFail();

        if (!$pengampu->dosen->contains($dosen->id)) {
            abort(403, 'Unauthorized action.');
        }

        $selectedPertemuan = $request->input('pertemuan');
        $selectedJadwalKuliahId = $request->input('jadwal_kuliah_id');
        $currentDate = Carbon::today();

        // Check if attendance for this meeting (pertemuan) and jadwal_kuliah_id has already been taken
        $existingAbsensi = Absensi::where('pengampu_id', $pengampu->id)
            ->where('jadwal_kuliah_id', $selectedJadwalKuliahId)
            ->where('pertemuan', $selectedPertemuan)
            ->where('tanggal', $currentDate)
            ->exists();

        if ($existingAbsensi) {
            return redirect()->back()->with('error', 'Absensi untuk pertemuan ini sudah diambil.');
        }

        $jadwalKuliah = JadwalKuliah::findOrFail($selectedJadwalKuliahId);

        foreach ($request->input('absensi') as $mahasiswaId => $status) {
            Absensi::create(
                [
                    'mahasiswa_id' => $mahasiswaId,
                    'jadwal_kuliah_id' => $jadwalKuliah->id,
                    'tanggal' => $currentDate,
                    'pengampu_id' => $pengampu->id,
                    'pertemuan' => $selectedPertemuan,
                    'status' => $status,
                    'waktu_absen' => Carbon::now(),
                ]
            );
        }

        return redirect()->route('dosen.absensi.index')->with('success', 'Absensi berhasil disimpan.');
    }
}
