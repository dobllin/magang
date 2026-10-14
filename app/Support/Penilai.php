<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Mencocokkan jawaban peserta dengan kunci jawaban.
 *
 * Soal di project ini bentuknya isian (pertanyaan + kunci jawaban teks),
 * jadi penilaiannya pakai pencocokan teks yang longgar: huruf besar/kecil,
 * spasi berlebih, dan tanda baca di ujung kalimat diabaikan.
 * Untuk soal pilihan ganda, cukup tulis hurufnya (misal "B") sebagai kunci.
 */
class Penilai
{
    /**
     * @param  Collection  $soal     kumpulan model Soal
     * @param  array       $jawaban  [id_soal => jawaban peserta]
     * @return array{benar:int, total:int, nilai:float}
     */
    public static function nilai(Collection $soal, array $jawaban): array
    {
        $benar = $soal->filter(
            fn ($s) => self::cocok($jawaban[$s->id] ?? '', $s->kunci_jawaban)
        )->count();

        $total = $soal->count();

        return [
            'benar' => $benar,
            'total' => $total,
            'nilai' => $total > 0 ? round($benar / $total * 100, 2) : 0.0,
        ];
    }

    public static function cocok(?string $jawaban, ?string $kunci): bool
    {
        $jawaban = self::rapikan($jawaban);

        return $jawaban !== '' && $jawaban === self::rapikan($kunci);
    }

    private static function rapikan(?string $teks): string
    {
        $teks = Str::lower(Str::squish((string) $teks));

        return trim($teks, " \t\n\r\0\x0B.,;:!?\"'");
    }
}
