<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $table = 'video';

    protected $fillable = [
        'id_materi',
        'video',
    ];

    // === RELATIONS ===
    public function materi()
    {
        return $this->belongsTo(Materi::class, 'id_materi');
    }
}