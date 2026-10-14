<?php

namespace Tests\Feature;

use App\Models\Materi;
use App\Models\Progress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlurBelajarTest extends TestCase
{
    use RefreshDatabase;

    private User $peserta;
    private Materi $materi;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->peserta = User::where('email', 'peserta@ppkpi.test')->first();
        $this->materi  = Materi::first();
    }

    public function test_alur_lengkap_sampai_dapat_sertifikat(): void
    {
        $this->actingAs($this->peserta);

        // Ikuti materi → diarahkan ke pretes
        $this->post(route('kelas.ikuti', $this->materi))
            ->assertRedirect(route('kelas.tes', [$this->materi, 'pretes']));

        // Belum boleh belajar sebelum pretes
        $this->get(route('kelas.belajar', $this->materi))
            ->assertRedirect(route('kelas.tes', [$this->materi, 'pretes']));

        $pretes = $this->materi->soal()->where('jenis_soal', 'pretes')->first();
        $this->get(route('kelas.tes', [$this->materi, 'pretes']))
            ->assertOk()
            ->assertSee($pretes->pertanyaan)
            ->assertDontSee($pretes->kunci_jawaban);

        // Pretes salah semua tetap lanjut belajar
        $this->post(route('kelas.tes.kirim', [$this->materi, 'pretes']), ['jawaban' => [$pretes->id => 'nggak tahu']])
            ->assertRedirect(route('kelas.belajar', $this->materi));

        $this->get(route('kelas.belajar', $this->materi))->assertOk();

        $this->post(route('kelas.selesai', $this->materi))
            ->assertRedirect(route('kelas.tes', [$this->materi, 'postes']));

        $postes = $this->materi->soal()->where('jenis_soal', 'postes')->orderBy('id')->get();

        // Postes cuma benar 1 dari 2 → nilai 50, belum lulus
        $this->post(route('kelas.tes.kirim', [$this->materi, 'postes']), [
            'jawaban' => [$postes[0]->id => 'alat pelindung diri', $postes[1]->id => 'C'],
        ])->assertRedirect(route('kelas.belajar', $this->materi));

        $progress = Progress::first();
        $this->assertSame('postes', $progress->progres);
        $this->assertDatabaseHas('penilaian', ['id_progress' => $progress->id, 'status' => 'tidak_lulus']);

        // Coba lagi, benar semua (huruf besar/kecil & titik diabaikan)
        $this->post(route('kelas.tes.kirim', [$this->materi, 'postes']), [
            'jawaban' => [$postes[0]->id => '  Alat Pelindung Diri. ', $postes[1]->id => 'a'],
        ])->assertRedirect(route('kelas.sertifikat', $progress->id));

        $this->assertSame('sertif', $progress->fresh()->progres);
        $this->assertDatabaseHas('penilaian', ['id_progress' => $progress->id, 'status' => 'lulus', 'nilai' => 100]);
        $this->assertDatabaseCount('penilaian', 1);

        $this->get(route('kelas.sertifikat', $progress->id))
            ->assertOk()->assertSee($this->peserta->nama_lengkap);

        $this->get(route('kelas.index'))->assertOk()->assertSee($this->materi->judul);
    }

    public function test_pretes_tidak_bisa_diulang_dan_postes_tidak_bisa_dilompati(): void
    {
        $this->actingAs($this->peserta)->post(route('kelas.ikuti', $this->materi));

        // Postes sebelum belajar ditolak
        $this->get(route('kelas.tes', [$this->materi, 'postes']))
            ->assertRedirect(route('kelas.tes', [$this->materi, 'pretes']))
            ->assertSessionHas('gagal');

        $pretes = $this->materi->soal()->where('jenis_soal', 'pretes')->first();
        $this->post(route('kelas.tes.kirim', [$this->materi, 'pretes']), ['jawaban' => [$pretes->id => 'x']]);

        $this->get(route('kelas.tes', [$this->materi, 'pretes']))
            ->assertRedirect(route('kelas.belajar', $this->materi));
    }

    public function test_peserta_tidak_bisa_buka_sertifikat_orang_lain(): void
    {
        $lain = User::factory()->create();
        $progress = Progress::create(['id_user' => $lain->id, 'id_materi' => $this->materi->id, 'progres' => 'sertif']);
        $progress->penilaian()->create(['id_user' => $lain->id, 'nilai' => 90, 'status' => 'lulus']);

        $this->actingAs($this->peserta)
            ->get(route('kelas.sertifikat', $progress->id))
            ->assertNotFound();
    }

    public function test_belum_ikut_materi_tidak_bisa_langsung_belajar(): void
    {
        $this->actingAs($this->peserta)
            ->get(route('kelas.belajar', $this->materi))
            ->assertRedirect(route('materi.show', $this->materi));
    }

    public function test_instruktur_tidak_bisa_ikut_materi(): void
    {
        $instruktur = User::where('email', 'instruktur@ppkpi.test')->first();

        $this->actingAs($instruktur)
            ->post(route('kelas.ikuti', $this->materi))
            ->assertForbidden();
    }

    public function test_hasil_belajar_peserta_muncul_di_area_instruktur(): void
    {
        $progress = Progress::create(['id_user' => $this->peserta->id, 'id_materi' => $this->materi->id, 'progres' => 'sertif']);
        $progress->penilaian()->create(['id_user' => $this->peserta->id, 'nilai' => 100, 'status' => 'lulus']);

        $instruktur = User::where('email', 'instruktur@ppkpi.test')->first();
        $this->actingAs($instruktur);

        $this->get(route('instruktur.dashboard'))->assertOk();
        $this->get(route('instruktur.peserta.index'))->assertOk()->assertSee($this->peserta->nama_lengkap);
        $this->get(route('instruktur.sertifikat.index'))->assertOk()->assertSee($this->peserta->nama_lengkap);
        $this->get(route('instruktur.sertifikat.show', $progress->id))->assertOk();
        $this->get(route('instruktur.materi.show', $this->materi->id))->assertOk();
    }

    public function test_admin_tidak_masuk_area_instruktur(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get(route('instruktur.dashboard'))
            ->assertForbidden();
    }
}
