<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instruktur extends Model
{
    use HasFactory;

    protected $table = 'instruktur';

    protected $fillable = [
        'id_user',
        'spesialisasi',
        'pengalaman',
        'sertifikasi',
        'jabatan',
    ];

    // === RELATIONS ===
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function materi()
    {
        return $this->hasMany(Materi::class, 'id_instruktur');
    }

    public function soal()
    {
        return $this->hasMany(Soal::class, 'id_instruktur');
    }
}