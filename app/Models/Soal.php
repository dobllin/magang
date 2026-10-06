<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;

    protected $table = 'soal';

    protected $fillable = [
        'id_instruktur',
        'id_materi',
        'pertanyaan',
        'kunci_jawaban',
        'jenis_soal',
    ];

    // === RELATIONS ===
    public function instruktur()
    {
        return $this->belongsTo(Instruktur::class, 'id_instruktur');
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'id_materi');
    }
}