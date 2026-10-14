<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    // === SCOPES ===
    public function scopeTerbit($query)
    {
        return $query->where('status', 'published');
    }

    // === HELPER METHODS ===

    /**
     * Bikin slug dari judul yang belum dipakai materi lain.
     * Kalau "dasar-k3" udah ada, hasilnya "dasar-k3-2", dst.
     */
    public static function slugUnik(string $judul, $abaikanId = null): string
    {
        $dasar = Str::slug($judul) ?: 'materi';
        $slug  = $dasar;
        $n     = 2;

        while (
            static::where('slug', $slug)
                ->when($abaikanId, fn ($q) => $q->where('id', '!=', $abaikanId))
                ->exists()
        ) {
            $slug = $dasar . '-' . $n++;
        }

        return $slug;
    }

    public function getDurationFormatAttribute()
    {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;
        return sprintf('%02d:%02d Hours', $hours, $minutes);
    }
}