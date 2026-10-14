<?php

namespace Database\Seeders;

use App\Models\Instruktur;
use App\Models\Materi;
use App\Models\Soal;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Data awal buat development. Semua akun password-nya: password
 *
 *   admin@ppkpi.test       → Admin
 *   instruktur@ppkpi.test  → Instruktur (udah punya profil + 1 materi)
 *   peserta@ppkpi.test     → Peserta
 *
 * JANGAN dijalankan di server produksi.
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->admin()->create([
            'name'         => 'Admin PPKPI',
            'nama_lengkap' => 'Admin PPKPI',
            'email'        => 'admin@ppkpi.test',
        ]);

        $userInstruktur = User::factory()->instruktur()->create([
            'name'         => 'Budi Instruktur',
            'nama_lengkap' => 'Budi Instruktur',
            'email'        => 'instruktur@ppkpi.test',
        ]);

        $instruktur = Instruktur::create([
            'id_user'      => $userInstruktur->id,
            'spesialisasi' => 'Keselamatan dan Kesehatan Kerja',
            'pengalaman'   => '8 tahun',
            'sertifikasi'  => 'Ahli K3 Umum',
            'jabatan'      => 'Instruktur Senior',
        ]);

        User::factory()->create([
            'name'         => 'Sari Peserta',
            'nama_lengkap' => 'Sari Peserta',
            'email'        => 'peserta@ppkpi.test',
            'nis'          => '2026001',
        ]);

        $materi = Materi::create([
            'id_instruktur'    => $instruktur->id,
            'judul'            => 'Dasar-Dasar K3 di Tempat Kerja',
            'slug'             => Materi::slugUnik('Dasar-Dasar K3 di Tempat Kerja'),
            'kategori'         => 'K3',
            'subkategori'      => 'Umum',
            'deskripsi'        => 'Pengenalan keselamatan dan kesehatan kerja untuk pekerja industri.',
            'objectives'       => "Memahami tujuan K3\nMengenali alat pelindung diri",
            'jenis_materi'     => 'video',
            'duration_minutes' => 90,
            'learning_points'  => 20,
            'total_lessons'    => 3,
            'bahasa'           => 'Indonesia',
            'status'           => 'published',
        ]);

        $soal = [
            ['pretes', 'Apa kepanjangan dari K3?', 'Keselamatan dan Kesehatan Kerja'],
            ['postes', 'Apa kepanjangan dari APD?', 'Alat Pelindung Diri'],
            ['postes', 'Warna helm untuk pekerja umum di banyak proyek adalah? (A. Kuning  B. Ungu  C. Hitam)', 'A'],
        ];

        foreach ($soal as [$jenis, $pertanyaan, $kunci]) {
            Soal::create([
                'id_instruktur' => $instruktur->id,
                'id_materi'     => $materi->id,
                'jenis_soal'    => $jenis,
                'pertanyaan'    => $pertanyaan,
                'kunci_jawaban' => $kunci,
            ]);
        }
    }
}
