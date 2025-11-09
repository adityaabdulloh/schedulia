<?php

namespace App\Console\Commands;

use App\Models\User; // Pastikan namespace model User Anda benar
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RehashUserPasswords extends Command
{
    protected $signature = 'user:rehash-passwords';

    protected $description = 'Re-hashes user passwords that are not using Bcrypt.';

    public function handle()
    {
        // Ganti App\Models\User::all() jika model Anda berbeda
        $users = User::all();
        $updatedCount = 0;

        $this->info('Memulai pengecekan dan hashing ulang password pengguna...');

        foreach ($users as $user) {
            // Cek jika kolom password tidak kosong dan belum di-hash dengan Bcrypt ($2y$)
            if (! empty($user->password) && ! Str::startsWith($user->password, '$2y$')) {
                $plainPassword = $user->password; // Asumsikan nilai yang tersimpan adalah password asli

                // Lakukan hashing ulang dan simpan
                $user->password = Hash::make($plainPassword);
                $user->save();

                $this->line("Password untuk pengguna: {$user->email} (ID: {$user->id}) telah di-hash ulang.");
                $updatedCount++;
            }
        }

        if ($updatedCount > 0) {
            $this->info("Berhasil melakukan hash ulang untuk {$updatedCount} password pengguna.");
        } else {
            $this->info('Semua password pengguna sudah aman. Tidak ada yang perlu diubah.');
        }

        return Command::SUCCESS;
    }
}
