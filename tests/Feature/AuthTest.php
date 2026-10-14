<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_peserta_bisa_login_dan_diarahkan_ke_kelas_saya(): void
    {
        $user = User::factory()->create();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('kelas.index'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_akun_nonaktif_tidak_bisa_login(): void
    {
        $user = User::factory()->nonaktif()->create();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_akun_yang_dinonaktifkan_saat_login_langsung_dikeluarkan(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $user->update(['status' => 'nonaktif']);

        $this->get(route('kelas.index'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_login_dibatasi_lima_kali_per_menit(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => $user->email, 'password' => 'salah']);
        }

        $this->post('/login', ['email' => $user->email, 'password' => 'salah'])
            ->assertStatus(429);
    }

    public function test_daftar_akun_baru(): void
    {
        $this->post('/register', [
            'nama_lengkap' => 'Andi', 'email' => 'andi@contoh.test', 'no_hp' => '0812',
            'nis' => '123', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '2005-01-01',
            'alamat' => 'Jakarta', 'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
        ])->assertRedirect(route('kelas.index'));

        $this->assertDatabaseHas('users', ['email' => 'andi@contoh.test', 'role' => 'peserta']);
    }
}
