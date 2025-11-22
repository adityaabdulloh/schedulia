<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Prodi; // Import Prodi model
use App\Models\User; // Import Mahasiswa model
use Illuminate\Http\Request; // Import Dosen model
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Tampilkan daftar pengguna.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(email) LIKE ?', ["%{$search}%"])
                  ->orWhereHas('mahasiswa', function ($q2) use ($search) {
                      $q2->whereRaw('LOWER(nim) LIKE ?', ["%{$search}%"])
                         ->orWhereHas('prodi', function ($q3) use ($search) {
                             $q3->whereRaw('LOWER(nama_prodi) LIKE ?', ["%{$search}%"]);
                         })
                         ->orWhereHas('kelas', function ($q3) use ($search) {
                             $q3->whereRaw('LOWER(nama_kelas) LIKE ?', ["%{$search}%"]);
                         })
                         ->orWhereRaw('CAST(semester AS TEXT) LIKE ?', ["%{$search}%"]);
                  })
                  ->orWhereHas('dosen', function ($q2) use ($search) {
                      $q2->whereRaw('LOWER(nip) LIKE ?', ["%{$search}%"])
                         ->orWhereHas('prodi', function ($q3) use ($search) {
                             $q3->whereRaw('LOWER(nama_prodi) LIKE ?', ["%{$search}%"]);
                         });
                  });
            });
        }

        $users = $query->with(['mahasiswa.prodi', 'mahasiswa.kelas', 'dosen.prodi'])->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Tampilkan form untuk membuat user baru.
     */
    public function create()
    {
        $prodis = Prodi::all(); // Get all prodi for dropdown
        $kelases = Kelas::all();

        return view('users.create', compact('prodis', 'kelases'));
    }

    /**
     * Simpan user baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,dosen,mahasiswa',
            'nim' => 'required_if:role,mahasiswa|nullable|string|unique:mahasiswa,nim',
            'prodi_id' => 'required_if:role,mahasiswa|nullable|exists:prodi,id',
            'semester' => 'required_if:role,mahasiswa|nullable|integer|min:1',
            'kelas_id' => 'required_if:role,mahasiswa|nullable|exists:kelas,id',
            'nip' => 'required_if:role,dosen|nullable|string|unique:dosen,nip',
            'prodi_id_dosen' => 'required_if:role,dosen|nullable|exists:prodi,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($user->role === 'mahasiswa') {
            Mahasiswa::create([
                'user_id' => $user->id,
                'nama' => $user->name,
                'nim' => $request->nim,
                'prodi_id' => $request->prodi_id,
                'semester' => $request->semester,
                'kelas_id' => $request->kelas_id,
            ]);
        } elseif ($user->role === 'dosen') {
            Dosen::create([
                'user_id' => $user->id,
                'nama' => $user->name,
                'email' => $user->email,
                'nip' => $request->nip,
                'prodi_id' => $request->prodi_id_dosen,
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $prodis = Prodi::all();
        $kelases = Kelas::all();
        return view('users.edit', compact('user', 'prodis', 'kelases'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'role' => 'required|string|in:admin,dosen,mahasiswa',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'nullable|string|min:8|confirmed'; // Changed to nullable
        }

        if ($request->role === 'mahasiswa') {
            $rules['nim'] = 'required|string|unique:mahasiswa,nim,'.($user->mahasiswa->id ?? 'NULL').',id';
            $rules['prodi_id'] = 'required|exists:prodi,id';
            $rules['semester'] = 'required|integer|min:1';
            $rules['kelas_id'] = 'required|exists:kelas,id';
        } elseif ($request->role === 'dosen') {
            $rules['nip'] = 'required|string|unique:dosen,nip,'.($user->dosen->id ?? 'NULL').',id';
            $rules['prodi_id_dosen'] = 'required|exists:prodi,id';
        }

        $request->validate($rules);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // Update or create associated Mahasiswa/Dosen record
        if ($user->role === 'mahasiswa') {
            $user->mahasiswa()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama' => $user->name,
                    'nim' => $request->nim,
                    'prodi_id' => $request->prodi_id,
                    'semester' => $request->semester,
                    'kelas_id' => $request->kelas_id,
                ]
            );
            // If role changed from dosen to mahasiswa, delete dosen record
            if ($user->dosen) {
                $user->dosen->delete();
            }
        } elseif ($user->role === 'dosen') {
            $user->dosen()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama' => $user->name,
                    'email' => $user->email,
                    'nip' => $request->nip,
                    'prodi_id' => $request->prodi_id_dosen,
                ]
            );
            // If role changed from mahasiswa to dosen, delete mahasiswa record
            if ($user->mahasiswa) {
                $user->mahasiswa->delete();
            }
        } else {
            // If role is admin, delete any associated mahasiswa or dosen records
            if ($user->mahasiswa) {
                $user->mahasiswa->delete();
            }
            if ($user->dosen) {
                $user->dosen->delete();
            }
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    /**
     * Redirect berdasarkan role pengguna.
     */
    public function redirectByRole()
    {
        $user = auth()->user(); // Ambil pengguna yang sedang login

        if (! $user) {
            return redirect('/login')->withErrors(['error' => 'Anda belum login!']);
        }

        switch ($user->role) {
            case 'admin':
                return redirect('/dashboard/admin');
            case 'dosen':
                return redirect('/dashboard/dosen');
            case 'mahasiswa':
                return redirect('/dashboard/mahasiswa');
            default:
                return redirect('/')->withErrors(['error' => 'Role tidak dikenali.']);
        }
    }
}
