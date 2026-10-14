<?php

namespace Tests\Feature;

use App\Models\Instruktur;
use App\Models\Materi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_bukan_admin_ditolak(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_form_instruktur_bisa_dibuka_dan_disimpan(): void
    {
        $calon = User::factory()->instruktur()->create();

        $this->actingAs($this->admin)->get(route('admin.instruktur.create'))
            ->assertOk()->assertSee($calon->nama_lengkap);

        $data = ['id_user' => $calon->id, 'spesialisasi' => 'Las', 'pengalaman' => '3 tahun', 'jabatan' => 'Instruktur'];

        $this->post(route('admin.instruktur.store'), $data)
            ->assertRedirect(route('admin.instruktur.index'));

        // User yang sama nggak boleh punya dua profil instruktur
        $this->post(route('admin.instruktur.store'), $data)->assertSessionHasErrors('id_user');

        $instruktur = Instruktur::first();
        $this->get(route('admin.instruktur.edit', $instruktur->id))->assertOk();
        $this->put(route('admin.instruktur.update', $instruktur->id), ['jabatan' => 'Kepala'] + $data)
            ->assertRedirect(route('admin.instruktur.index'));
    }

    public function test_profil_instruktur_harus_untuk_user_role_instruktur(): void
    {
        $peserta = User::factory()->create();

        $this->actingAs($this->admin)->post(route('admin.instruktur.store'), [
            'id_user' => $peserta->id, 'spesialisasi' => 'Las', 'pengalaman' => '3 tahun', 'jabatan' => 'Instruktur',
        ])->assertSessionHasErrors('id_user');
    }

    public function test_admin_tidak_bisa_hapus_atau_turunkan_dirinya_sendiri(): void
    {
        $this->actingAs($this->admin)
            ->delete(route('admin.users.destroy', $this->admin))
            ->assertSessionHas('error');

        $this->put(route('admin.users.update', $this->admin), [
            'nama_lengkap' => 'Admin', 'email' => $this->admin->email, 'no_hp' => '08',
            'role' => 'peserta', 'status' => 'aktif',
        ])->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $this->admin->id, 'role' => 'admin']);
    }

    public function test_admin_bisa_menonaktifkan_user_lain(): void
    {
        $user = User::factory()->create();

        $this->actingAs($this->admin)->put(route('admin.users.update', $user), [
            'nama_lengkap' => $user->nama_lengkap, 'email' => $user->email, 'no_hp' => '08',
            'role' => 'peserta', 'status' => 'nonaktif',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertSame('nonaktif', $user->fresh()->status);
    }

    public function test_slug_materi_dari_admin_selalu_unik(): void
    {
        $instruktur = Instruktur::create([
            'id_user' => User::factory()->instruktur()->create()->id,
            'spesialisasi' => 'K3', 'pengalaman' => '1 tahun', 'jabatan' => 'Instruktur',
        ]);

        $data = [
            'id_instruktur' => $instruktur->id, 'judul' => 'Dasar K3', 'kategori' => 'K3',
            'deskripsi' => 'x', 'jenis_materi' => 'video', 'duration_minutes' => 60,
            'learning_points' => 10, 'total_lessons' => 2, 'bahasa' => 'Indonesia', 'status' => 'draft',
        ];

        $this->actingAs($this->admin)->get(route('admin.materi.create'))->assertOk();
        $this->post(route('admin.materi.store'), $data);
        $this->post(route('admin.materi.store'), $data);

        $this->assertEquals(['dasar-k3', 'dasar-k3-2'], Materi::orderBy('id')->pluck('slug')->all());
    }

    public function test_semua_halaman_admin_bisa_dibuka(): void
    {
        $this->seed();
        $this->actingAs($this->admin);

        foreach (['admin.dashboard', 'admin.users.index', 'admin.users.create', 'admin.instruktur.index', 'admin.materi.index'] as $nama) {
            $this->get(route($nama))->assertOk();
        }

        $this->get(route('admin.users.edit', $this->admin))->assertOk();
        $this->get(route('admin.materi.edit', Materi::first()->id))->assertOk();
    }

    public function test_materi_yang_sudah_diambil_peserta_tidak_bisa_dihapus_admin(): void
    {
        $this->seed();
        $materi = Materi::first();
        $materi->progresses()->create(['id_user' => User::factory()->create()->id, 'progres' => 'view']);

        $this->actingAs($this->admin)
            ->delete(route('admin.materi.destroy', $materi->id))
            ->assertSessionHas('error');

        $this->assertModelExists($materi);
    }

    public function test_route_show_admin_tidak_ada(): void
    {
        $this->actingAs($this->admin)->get('/admin/users/' . $this->admin->id)->assertStatus(405);
    }
}
