<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;

    protected $table = 'progress';

    protected $fillable = [
        'id_user',
        'id_materi',
        'progres',
    ];

    // === RELATIONS ===
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'id_materi');
    }

    public function penilaian()
    {
        return $this->hasOne(Penilaian::class, 'id_progress');
    }
}