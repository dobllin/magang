<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';

    protected $fillable = [
        'id_instruktur',
        'judul',
        'slug',
        'kategori',
        'subkategori',
        'deskripsi',
        'objectives',
        'thumbnail',
        'jenis_materi',
        'duration_minutes',
        'learning_points',
        'total_lessons',
        'bahasa',
        'status',
    ];

    // === RELATIONS ===
    public function instruktur()
    {
        return $this->belongsTo(Instruktur::class, 'id_instruktur');
    }

    public function soal()
    {
        return $this->hasMany(Soal::class, 'id_materi');
    }

    public function progresses()
    {
        return $this->hasMany(Progress::class, 'id_materi');
    }

    public function pdfs()
    {
        return $this->hasMany(Pdf::class, 'id_materi');
    }

    public function videos()
    {
        return $this->hasMany(Video::class, 'id_materi');
    }

    public function gambars()
    {
        return $this->hasMany(Gambar::class, 'id_materi');
    }

    // === HELPER METHODS ===
    public function getDurationFormatAttribute()
    {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;
        return sprintf('%02d:%02d Hours', $hours, $minutes);
    }
}