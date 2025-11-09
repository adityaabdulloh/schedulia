<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HariJamRuangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seeder untuk Hari
        $hari = [
            ['nama_hari' => 'Senin', 'created_at' => now(), 'updated_at' => now()],
            ['nama_hari' => 'Selasa', 'created_at' => now(), 'updated_at' => now()],
            ['nama_hari' => 'Rabu', 'created_at' => now(), 'updated_at' => now()],
            ['nama_hari' => 'Kamis', 'created_at' => now(), 'updated_at' => now()],
            ['nama_hari' => 'Jumat', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('hari')->insert($hari);

        // Seeder untuk Jam
        $jam = [
            ['jam_mulai' => '08:00', 'jam_selesai' => '09:40', 'durasi' => 100, 'created_at' => now(), 'updated_at' => now()], // 2 SKS
            ['jam_mulai' => '10:00', 'jam_selesai' => '12:30', 'durasi' => 150, 'created_at' => now(), 'updated_at' => now()], // 3 SKS
            ['jam_mulai' => '13:30', 'jam_selesai' => '15:10', 'durasi' => 100, 'created_at' => now(), 'updated_at' => now()], // 2 SKS
            ['jam_mulai' => '15:30', 'jam_selesai' => '17:10', 'durasi' => 100, 'created_at' => now(), 'updated_at' => now()], // 2 SKS
        ];
        DB::table('jam')->insert($jam);

        // Seeder untuk Ruang
        $ruang = [
            ['nama_ruang' => 'R-101', 'kapasitas' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['nama_ruang' => 'R-102', 'kapasitas' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['nama_ruang' => 'R-201', 'kapasitas' => 35, 'created_at' => now(), 'updated_at' => now()],
            ['nama_ruang' => 'LAB-KOM 1', 'kapasitas' => 30, 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('ruang')->insert($ruang);
    }
}
