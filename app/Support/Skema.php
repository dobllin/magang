<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

/**
 * Baca struktur tabel langsung dari database.
 *
 * Kenapa perlu? Tabel di project ini dibikin manual lewat phpMyAdmin,
 * bukan lewat migration. Jadi isi ENUM-nya cuma database yang tau.
 * Daripada nebak-nebak dan kena "Data truncated", mending tanya langsung.
 */
class Skema
{
    /**
     * Ambil pilihan ENUM dari sebuah kolom.
     * Balikin array kosong kalau kolomnya bukan ENUM.
     *
     * Contoh: Skema::enum('materi', 'status')  =>  ['draft', 'published']
     */
    public static function enum(string $tabel, string $kolom): array
    {
        try {
            $row = DB::selectOne(
                "SHOW COLUMNS FROM `{$tabel}` WHERE Field = ?",
                [$kolom]
            );
        } catch (\Throwable $e) {
            return [];
        }

        if (! $row || ! isset($row->Type)) {
            return [];
        }

        // Bentuk aslinya: enum('draft','published')
        if (! preg_match("/^enum\((.*)\)$/i", $row->Type, $cocok)) {
            return [];
        }

        return array_map(
            fn ($v) => trim($v, "'"),
            str_getcsv($cocok[1], ',', "'", '\\')
        );
    }

    /**
     * Cek sebuah kolom ada atau nggak di tabel.
     */
    public static function punyaKolom(string $tabel, string $kolom): bool
    {
        try {
            return DB::selectOne(
                "SHOW COLUMNS FROM `{$tabel}` WHERE Field = ?",
                [$kolom]
            ) !== null;
        } catch (\Throwable $e) {
            return false;
        }
    }
}