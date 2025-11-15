<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Jam extends Model
{
    protected $table = 'jam';

    protected $fillable = [
        'jam_mulai',
        'jam_selesai',
        'durasi',
    ];

    // Hapus relasi dengan matakuliah
    // Gunakan durasi sebagai pengganti SKS dari mata kuliah

    public function getJamMulaiAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('H:i') : null;
    }

    public function getJamSelesaiAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('H:i') : null;
    }

    public function jadwalKuliah()
    {
        return $this->hasMany(JadwalKuliah::class);
    }

    // Tambahkan accessor untuk range waktu
    public function getRangeWaktuAttribute()
    {
        return $this->jam_mulai.' - '.$this->jam_selesai;
    }

    // Tambahkan mutator untuk jam_mulai
    public function setJamMulaiAttribute($value)
    {
        $this->attributes['jam_mulai'] = Carbon::parse($value)->format('H:i:s');
    }

    // Tambahkan mutator untuk jam_selesai
    public function setJamSelesaiAttribute($value)
    {
        $this->attributes['jam_selesai'] = Carbon::parse($value)->format('H:i:s');
    }

    
}
