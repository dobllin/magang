<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian';

    protected $fillable = [
        'id_user',
        'id_progress',
        'nilai',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'nilai' => 'decimal:2',
        ];
    }

    // === RELATIONS ===
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function progress()
    {
        return $this->belongsTo(Progress::class, 'id_progress');
    }
}