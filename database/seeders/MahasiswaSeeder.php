<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Import the Kelas model

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil ID prodi yang sudah ada untuk referensi
        $prodiTI = DB::table('prodi')->where('nama_prodi', 'Teknik Informatika')->value('id');
        $prodiSI = DB::table('prodi')->where('nama_prodi', 'Sistem Informasi')->value('id');

        // Ambil ID kelas yang sudah ada untuk referensi
        $kelasTIA = Kelas::where('nama_kelas', 'TI-A')->where('prodi_id', $prodiTI)->value('id');
        $kelasSIB = Kelas::where('nama_kelas', 'SI-A')->where('prodi_id', $prodiSI)->value('id');

        $mahasiswaData = [
            [
                'nim' => '220101001',
                'nama' => 'Andi Pratama',
                'email' => 'andi.pratama@example.com',
                'password' => 'password',
                'prodi_id' => $prodiTI,
                'kelas_id' => $kelasTIA, // Dynamically get class ID
                'semester' => 3, // Assuming a default semester
            ],
            [
                'nim' => '220201001',
                'nama' => 'Citra Lestari',
                'email' => 'citra.lestari@example.com',
                'password' => 'password',
                'prodi_id' => $prodiSI,
                'kelas_id' => $kelasSIB, // Dynamically get class ID
                'semester' => 3, // Assuming a default semester
            ],
        ];

        foreach ($mahasiswaData as $data) {
            $user = User::create([
                'name' => $data['nama'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'mahasiswa',
            ]);

            Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $data['nim'],
                'nama' => $data['nama'],
                'prodi_id' => $data['prodi_id'],
                'kelas_id' => $data['kelas_id'],
                'semester' => $data['semester'],
            ]);
        }
    }
}
