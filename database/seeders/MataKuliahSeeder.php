<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use App\Models\Prodi;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Ambil prodi yang ada
        $prodiTI = Prodi::where('nama_prodi', 'Teknik Informatika')->first();
        $prodiSI = Prodi::where('nama_prodi', 'Sistem Informasi')->first();

        $matakuliahs = [
            [
                'kode_mk' => 'TI101',
                'nama' => 'Algoritma dan Pemrograman',
                'sks' => 3,
                'semester' => 1, // Tambahkan semester
                'prodi_id' => $prodiTI->id ?? null,
            ],
            [
                'kode_mk' => 'TI102',
                'nama' => 'Struktur Data',
                'sks' => 3,
                'semester' => 2, // Tambahkan semester
                'prodi_id' => $prodiTI->id ?? null,
            ],
            [
                'kode_mk' => 'SI101',
                'nama' => 'Dasar Sistem Informasi',
                'sks' => 3,
                'semester' => 1, // Tambahkan semester
                'prodi_id' => $prodiSI->id ?? null,
            ],
            // Tambahkan data mata kuliah lain di sini
        ];

        foreach ($matakuliahs as $matakuliah) {
            if ($matakuliah['prodi_id']) { // Hanya buat jika prodi ditemukan
                if (! empty($matakuliah['prodi_id'])) { // Hanya buat jika prodi ditemukan
                    MataKuliah::updateOrCreate(['kode_mk' => $matakuliah['kode_mk']], $matakuliah);
                }
            }
        }
    }
}
