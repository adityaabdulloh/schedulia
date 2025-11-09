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
    public function index()
    {
        $users = User::all(); // Mengambil semua data pengguna

        return view('users.index', compact('users')); // Tampilkan di view users.index
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
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'role' => 'required|string|in:admin,dosen,mahasiswa',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

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
