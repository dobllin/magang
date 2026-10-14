<?php

namespace App\Support;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Query sertifikat yang dipakai bareng area instruktur dan peserta.
 */
class Sertifikat
{
    /**
     * Query dasar peserta lulus.
     *
     * "Lulus" di sini artinya salah satu dari dua ini:
     *   - penilaian.status = 'lulus'
     *   - progress.progres = 'sertif'
     *
     * Dua-duanya dipakai karena data lama belum tentu selalu ngisi
     * kedua kolom itu barengan.
     */
    public static function query(): Builder
    {
        return DB::table('progress')
            ->join('users', 'progress.id_user', '=', 'users.id')
            ->join('materi', 'progress.id_materi', '=', 'materi.id')
            ->join('penilaian', 'penilaian.id_progress', '=', 'progress.id')
            ->join('instruktur', 'materi.id_instruktur', '=', 'instruktur.id')
            ->join('users as pengajar', 'instruktur.id_user', '=', 'pengajar.id')
            ->where(function ($q) {
                $q->where('penilaian.status', 'lulus')
                  ->orWhere('progress.progres', 'sertif');
            })
            ->select(
                'progress.id as id_progress',
                'progress.progres',
                'users.id as id_user',
                'users.nama_lengkap',
                'users.email',
                'users.nis',
                'materi.id as id_materi',
                'materi.judul as judul_materi',
                'materi.kategori',
                'materi.duration_minutes',
                'penilaian.nilai',
                'penilaian.status as status_penilaian',
                'penilaian.updated_at as lulus_pada',
                'pengajar.nama_lengkap as nama_instruktur',
                'instruktur.jabatan as jabatan_instruktur'
            );
    }

    /**
     * Nomor sertifikat dibentuk dari id progres biar konsisten
     * tiap kali halaman dibuka, bukan angka acak.
     */
    public static function nomor(object $sertifikat): string
    {
        return sprintf(
            'PPKPI/%s/%s/%04d',
            date('Y', strtotime($sertifikat->lulus_pada)),
            str_pad($sertifikat->id_materi, 3, '0', STR_PAD_LEFT),
            $sertifikat->id_progress
        );
    }
}
