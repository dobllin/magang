<?php

namespace App\Support;

/**
 * Ngurusin link YouTube sama Google Drive.
 *
 * Link yang di-copy orang dari browser itu bentuknya link TONTON,
 * bukan link EMBED. Kalau langsung dimasukin ke <iframe> hasilnya
 * blank atau "refused to connect". Kelas ini yang nerjemahin.
 */
class Tautan
{
    // ============================================================
    // YOUTUBE
    // ============================================================

    /**
     * Ambil ID video dari macam-macam bentuk link YouTube.
     *
     * Yang didukung:
     *   youtube.com/watch?v=ID
     *   youtu.be/ID
     *   youtube.com/embed/ID
     *   youtube.com/shorts/ID
     *   youtube.com/live/ID
     */
    public static function youtubeId(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $pola = [
            '/[?&]v=([A-Za-z0-9_-]{11})/',
            '#youtu\.be/([A-Za-z0-9_-]{11})#',
            '#/embed/([A-Za-z0-9_-]{11})#',
            '#/shorts/([A-Za-z0-9_-]{11})#',
            '#/live/([A-Za-z0-9_-]{11})#',
        ];

        foreach ($pola as $p) {
            if (preg_match($p, $url, $cocok)) {
                return $cocok[1];
            }
        }

        // Kalau user cuma nempel ID-nya doang
        if (preg_match('/^[A-Za-z0-9_-]{11}$/', trim($url))) {
            return trim($url);
        }

        return null;
    }

    /** Link buat dipasang di <iframe>. */
    public static function youtubeEmbed(?string $url): ?string
    {
        $id = self::youtubeId($url);

        return $id ? "https://www.youtube.com/embed/{$id}" : null;
    }

    /** Gambar sampul video, berguna buat thumbnail materi. */
    public static function youtubeThumbnail(?string $url): ?string
    {
        $id = self::youtubeId($url);

        return $id ? "https://img.youtube.com/vi/{$id}/hqdefault.jpg" : null;
    }

    // ============================================================
    // GOOGLE DRIVE
    // ============================================================

    /**
     * Ambil ID file dari link Google Drive.
     *
     * Yang didukung:
     *   drive.google.com/file/d/ID/view
     *   drive.google.com/open?id=ID
     *   docs.google.com/....?id=ID
     *   drive.google.com/uc?export=download&id=ID
     */
    public static function driveId(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        if (preg_match('#/file/d/([A-Za-z0-9_-]{10,})#', $url, $cocok)) {
            return $cocok[1];
        }

        if (preg_match('/[?&]id=([A-Za-z0-9_-]{10,})/', $url, $cocok)) {
            return $cocok[1];
        }

        if (preg_match('#/d/([A-Za-z0-9_-]{10,})#', $url, $cocok)) {
            return $cocok[1];
        }

        return null;
    }

    /**
     * Link preview buat <iframe>. Dipakai buat PDF.
     */
    public static function drivePreview(?string $url): ?string
    {
        $id = self::driveId($url);

        return $id ? "https://drive.google.com/file/d/{$id}/preview" : null;
    }

    /**
     * Link gambar langsung buat <img>. Dipakai buat GIF/foto.
     *
     * Sengaja pakai /thumbnail, bukan /uc?export=view. Yang /uc
     * sering kena halangan quota kalau file-nya rame diakses,
     * sementara /thumbnail lebih stabil.
     */
    public static function driveGambar(?string $url, int $lebar = 1000): ?string
    {
        $id = self::driveId($url);

        return $id
            ? "https://drive.google.com/thumbnail?id={$id}&sz=w{$lebar}"
            : null;
    }

    /**
     * Link buat tombol "Buka di Google Drive".
     */
    public static function driveBuka(?string $url): ?string
    {
        $id = self::driveId($url);

        return $id ? "https://drive.google.com/file/d/{$id}/view" : null;
    }
}