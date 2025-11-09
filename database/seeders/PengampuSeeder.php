<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\Pengampu;
use Illuminate\Database\Seeder;

class PengampuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan seeder prasyarat sudah dijalankan
        $this->call(DosenSeeder::class);
        $this->call(MataKuliahSeeder::class);
        $this->call(KelasSeeder::class);

        // Ambil semua data yang diperlukan sekali saja untuk efisiensi
        $dosens = Dosen::all()->keyBy('email');
        $matakuliahs = MataKuliah::all()->keyBy('kode_mk');
        $kelases = Kelas::all()->keyBy('nama_kelas');

        $pengampuData = [
            [
                'dosen_email' => 'dosen1@example.com',
                'kode_mk' => 'TI101',
                'nama_kelas' => 'TI-A',
                'tahun_akademik' => '2023/2024',
            ],
            [
                'dosen_email' => 'dosen2@example.com',
                'kode_mk' => 'TI102',
                'nama_kelas' => 'TI-B',
                'tahun_akademik' => '2023/2024',
            ],
            // Tambahkan data pengampu lain di sini
        ];

        foreach ($pengampuData as $data) {
            $dosen = $dosens->get($data['dosen_email']);
            $matakuliah = $matakuliahs->get($data['kode_mk']);
            $kelas = $kelases->get($data['nama_kelas']);

            if ($dosen && $matakuliah && $kelas) {
                $pengampu = Pengampu::updateOrCreate(
                    ['matakuliah_id' => $matakuliah->id, 'kelas_id' => $kelas->id, 'tahun_akademik' => $data['tahun_akademik']],
                    ['prodi_id' => $matakuliah->prodi_id]
                );
                $pengampu->dosen()->sync([$dosen->id]);
            }
        }
    }
}
