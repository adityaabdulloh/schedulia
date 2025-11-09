<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Prodi;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Ambil prodi yang ada untuk dihubungkan dengan kelas
        $prodiTI = Prodi::where('nama_prodi', 'Teknik Informatika')->first();
        $prodiSI = Prodi::where('nama_prodi', 'Sistem Informasi')->first();

        $kelases = [
            [
                'nama_kelas' => 'TI-A',
                'prodi_id' => $prodiTI->id ?? null,
            ],
            [
                'nama_kelas' => 'TI-B',
                'prodi_id' => $prodiTI->id ?? null,
            ],
            [
                'nama_kelas' => 'SI-A',
                'prodi_id' => $prodiSI->id ?? null,
            ],
            // Anda bisa menambahkan data kelas lain di sini
        ];

        foreach ($kelases as $kelas) {
            if (! empty($kelas['prodi_id'])) {
                Kelas::updateOrCreate(['nama_kelas' => $kelas['nama_kelas']], $kelas);
            }
        }
    }
}
