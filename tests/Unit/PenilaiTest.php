<?php

namespace Tests\Unit;

use App\Support\Penilai;
use PHPUnit\Framework\TestCase;

class PenilaiTest extends TestCase
{
    public function test_pencocokan_mengabaikan_huruf_besar_spasi_dan_tanda_baca(): void
    {
        $this->assertTrue(Penilai::cocok('  alat   PELINDUNG diri. ', 'Alat Pelindung Diri'));
        $this->assertTrue(Penilai::cocok('b', 'B'));
        $this->assertFalse(Penilai::cocok('C', 'B'));
        $this->assertFalse(Penilai::cocok('', ''));
        $this->assertFalse(Penilai::cocok(null, 'A'));
    }

    public function test_nilai_dihitung_dari_persentase_benar(): void
    {
        $soal = collect([
            (object) ['id' => 1, 'kunci_jawaban' => 'A'],
            (object) ['id' => 2, 'kunci_jawaban' => 'B'],
            (object) ['id' => 3, 'kunci_jawaban' => 'C'],
        ]);

        $hasil = Penilai::nilai($soal, [1 => 'a', 2 => 'x', 3 => 'c']);

        $this->assertSame(['benar' => 2, 'total' => 3, 'nilai' => 66.67], $hasil);
    }
}
