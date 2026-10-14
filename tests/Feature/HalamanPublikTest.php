<?php

namespace Tests\Feature;

use App\Models\Instruktur;
use App\Models\Materi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HalamanPublikTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_depan_bisa_dibuka(): void
    {
        $this->seed();

        $this->get('/')->assertOk()->assertSee('Dasar-Dasar K3 di Tempat Kerja');
        $this->get('/dashboard')->assertOk();
        $this->get(route('materi.show', Materi::first()))->assertOk()->assertSee('Masuk untuk Mulai Belajar');
    }

    public function test_materi_draft_tidak_tampil_ke_publik(): void
    {
        $instruktur = Instruktur::create([
            'id_user' => User::factory()->instruktur()->create()->id,
            'spesialisasi' => 'K3', 'pengalaman' => '1 tahun', 'jabatan' => 'Instruktur',
        ]);

        $draft = Materi::create([
            'id_instruktur' => $instruktur->id, 'judul' => 'Materi Rahasia',
            'slug' => 'materi-rahasia', 'kategori' => 'K3',
            'jenis_materi' => 'video', 'status' => 'draft',
        ]);

        $this->get('/dashboard')->assertOk()->assertDontSee('Materi Rahasia');
        $this->get(route('materi.show', $draft))->assertNotFound();
    }
}
