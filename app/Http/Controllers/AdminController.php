<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\PengambilanMK;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        // Data for Stat Cards
        $dosenCount = Dosen::count();
        $mahasiswaCount = Mahasiswa::count();
        $matakuliahCount = Matakuliah::count();
        $userCount = User::count();

        // Data for Prodi Chart (Students per Program)
        $prodiData = Prodi::withCount('mahasiswa')->get();
        $prodiLabels = $prodiData->pluck('nama_prodi');
        $prodiMahasiswaCounts = $prodiData->pluck('mahasiswa_count');

        // Data for User Roles Chart
        $userRolesData = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->get();
        $userRoleLabels = $userRolesData->pluck('role');
        $userRoleCounts = $userRolesData->pluck('total');

        // Data for Pending Approvals
        $pendingKrsFromDb = PengambilanMK::where('status', 'pending')
            ->with([
                'mahasiswa' => function ($query) {
                    // Explicitly select columns to ensure foto_profil is included
                    $query->select('id', 'nim', 'nama', 'foto_profil', 'user_id', 'kelas_id', 'prodi_id', 'semester');
                },
                'mahasiswa.prodi', // We still need to load the prodi relationship on the mahasiswa model
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingKrs = $pendingKrsFromDb
            ->groupBy('mahasiswa_id')
            ->map(function ($group) {
                $item = $group->first();
                $item->total_mk = $group->count();

                return $item;
            })
            ->take(5);

        return view('dashboard', compact(
            'dosenCount',
            'mahasiswaCount',
            'matakuliahCount',
            'userCount',
            'prodiData',
            'prodiLabels',
            'prodiMahasiswaCounts',
            'userRoleLabels',
            'userRoleCounts',
            'pendingKrs'
        ));
    }

    public function editProfile()
    {
        return view('admin.edit-profile', [
            'user' => Auth::user(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        // For now, this method can be empty or handle other profile updates if needed.
        // The password update will be handled by updatePassword.
        return redirect()->route('dashboard.admin')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('dashboard.admin')->with('success', 'Password berhasil diperbarui.');
    }

    public function krsMahasiswa()
    {
        $krsMahasiswa = PengambilanMK::with(['mahasiswa', 'matakuliah'])->get();

        return view('admin.krs-mahasiswa', compact('krsMahasiswa'));
    }

    public function absensi()
    {
        return view('admin.absensi');
    }
}
//tes
