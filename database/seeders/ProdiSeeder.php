<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prodi = [
            ['nama_prodi' => 'Teknik Informatika', 'created_at' => now(), 'updated_at' => now()],
            ['nama_prodi' => 'Sistem Informasi', 'created_at' => now(), 'updated_at' => now()],
            ['nama_prodi' => 'Pendidikan Teknologi Informasi', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('prodi')->insert($prodi);
    }
}
