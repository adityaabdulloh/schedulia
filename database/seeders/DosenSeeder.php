<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil ID prodi yang sudah ada untuk referensi
        $prodiTI = Prodi::where('nama_prodi', 'Teknik Informatika')->first();
        $prodiSI = Prodi::where('nama_prodi', 'Sistem Informasi')->first();

        $dosenEntries = [
            [
                'nip' => '198501012010121001', // Menggunakan 'nidn' sesuai error
                'nama' => 'Dr. Budi Santoso, M.Kom.',
                'email' => 'budi.santoso@example.com',

                'prodi_id' => $prodiTI->id,
            ],
            [
                'nip' => '199002022015032002', // Menggunakan 'nidn'
                'nama' => 'Siti Aminah, S.Kom., M.T.',
                'email' => 'siti.aminah@example.com',

                'prodi_id' => $prodiSI->id,
            ],
        ];

        foreach ($dosenEntries as $dosenData) {
            // Gunakan firstOrCreate untuk membuat user jika belum ada
            $user = User::firstOrCreate(
                ['email' => $dosenData['email']],
                [
                    'name' => $dosenData['nama'],
                    'password' => bcrypt('password'), // Password default
                    'role' => 'dosen', // Asumsikan ada role 'dosen' di tabel users
                ]
            );

            // Gunakan updateOrCreate untuk membuat atau memperbarui data dosen berdasarkan NIDN
            // dan menautkannya dengan user_id yang sesuai.
            Dosen::updateOrCreate(['nip' => $dosenData['nip']], array_merge($dosenData, ['user_id' => $user->id]));
        }
    }
}
