<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Prodi;
use Illuminate\Support\Facades\Hash;

class HarunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prodi = Prodi::where('nama_prodi', 'Pendidikan Teknologi Informasi')->first();

        if ($prodi) {
            $user = User::create([
                'name' => 'Pak Harun',
                'email' => 'harun@gmail.com',
                'password' => Hash::make('harun123'),
                'role' => 'dosen',
            ]);

            Dosen::create([
                'user_id' => $user->id,
                'nip' => str_pad(mt_rand(1, 99999999999999), 14, '0', STR_PAD_LEFT),
                'nama' => 'Pak Harun',
                'email' => 'harun@gmail.com',
                'prodi_id' => $prodi->id,
            ]);
        }
    }
}
