<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';

    protected $fillable = [
        'nama',
        'nip',
        'email',
        'prodi_id',
        'foto_profil',
        'user_id',
    ];

    protected $with = ['prodi'];

    public function matakuliah()
    {
        return $this->hasMany(Matakuliah::class, 'dosen_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function JadwalKuliah()
    {
        return $this->hasMany(JadwalKuliah::class, 'dosen_id');
    }

    public function pengampus()
    {
        return $this->hasMany(Pengampu::class, 'dosen_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
