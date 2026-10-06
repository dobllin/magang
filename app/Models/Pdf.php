<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pdf extends Model
{
    use HasFactory;

    protected $table = 'pdf';

    protected $fillable = [
        'id_materi',
        'pdf_files',
    ];

    // === RELATIONS ===
    public function materi()
    {
        return $this->belongsTo(Materi::class, 'id_materi');
    }
}