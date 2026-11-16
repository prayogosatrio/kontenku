<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_wajib_login(): void
    {
        foreach (['/', '/clients', '/campaigns', '/posts', '/posts/jadwal', '/platforms', '/profil'] as $url) {
            $this->get($url)->assertRedirect('/login');
        }
    }

    public function test_login_gagal_dan_berhasil(): void
    {
        Http::fake(['*' => Http::response(['total_klien' => 0])]);
        $this->admin();

        $this->followingRedirects()->from('/login')->post('/login', [])
            ->assertSee('Email wajib diisi.')->assertSee('Password wajib diisi.');

        $this->followingRedirects()->from('/login')->post('/login', ['email' => 'admin@uji.test', 'password' => 'salah'])
            ->assertSee('Email atau password salah.');
        $this->assertGuest();

        $this->post('/login', ['email' => 'admin@uji.test', 'password' => 'password'])->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->get('/')->assertOk()->assertSee('Login berhasil, selamat datang Admin Uji.')->assertSee('Admin Uji (Admin)');
        $this->get('/login')->assertRedirect('/');

        $this->followingRedirects()->post('/logout')->assertSee('Logout berhasil.');
        $this->assertGuest();
    }

    public function test_login_kembali_ke_halaman_yang_dituju(): void
    {
        $this->creator();
        $this->get('/clients')->assertRedirect('/login');
        $this->post('/login', ['email' => 'creator@uji.test', 'password' => 'password'])->assertRedirect(route('clients.index'));
    }

    public function test_hanya_admin_boleh_kelola_platform(): void
    {
        $this->actingAs($this->creator());

        $this->get('/platforms')->assertForbidden()->assertSee('Halaman ini hanya bisa diakses oleh admin.');
        $this->post('/platforms', ['nama_platform' => 'Threads'])->assertForbidden();
        $this->get('/clients')->assertOk()->assertDontSee('>Platform</a>', false);

        $this->actingAs($this->admin());
        $this->get('/platforms')->assertOk();
        $this->get('/clients')->assertSee('>Platform</a>', false);
    }

    public function test_profil_dan_ganti_password(): void
    {
        $user = $this->creator();
        $this->actingAs($user);

        $this->get('/profil')->assertOk()->assertSee('creator@uji.test')->assertSee('<td>Creator</td>', false);

        $this->followingRedirects()->from('/profil')->put('/profil/password', [])
            ->assertSee('Password lama wajib diisi.')->assertSee('Password baru wajib diisi.');
        $this->followingRedirects()->from('/profil')->put('/profil/password', ['password_lama' => 'password', 'password_baru' => 'abc', 'password_baru_confirmation' => 'abc'])
            ->assertSee('Password baru minimal 8 karakter.');
        $this->followingRedirects()->from('/profil')->put('/profil/password', ['password_lama' => 'password', 'password_baru' => 'rahasia123', 'password_baru_confirmation' => 'beda12345'])
            ->assertSee('Konfirmasi password baru tidak sama.');
        $this->followingRedirects()->from('/profil')->put('/profil/password', ['password_lama' => 'keliru', 'password_baru' => 'rahasia123', 'password_baru_confirmation' => 'rahasia123'])
            ->assertSee('Password lama salah.');

        $this->followingRedirects()->from('/profil')->put('/profil/password', ['password_lama' => 'password', 'password_baru' => 'rahasia123', 'password_baru_confirmation' => 'rahasia123'])
            ->assertSee('Password berhasil diubah.');

        $hash = $user->fresh()->password;
        $this->assertNotSame('rahasia123', $hash);
        $this->assertTrue(Hash::check('rahasia123', $hash));
    }
}
