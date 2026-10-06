<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gambar extends Model
{
    use HasFactory;

    protected $table = 'gambar';

    protected $fillable = [
        'id_materi',
        'picture',
    ];

    // === RELATIONS ===
    public function materi()
    {
        return $this->belongsTo(Materi::class, 'id_materi');
    }
}