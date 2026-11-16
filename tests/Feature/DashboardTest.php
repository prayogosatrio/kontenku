<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Client;
use App\Models\Platform;
use App\Models\Post;
use App\Models\User;
use Database\Seeders\PlatformSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_dan_jadwal_mengonsumsi_api(): void
    {
        $this->actingAs($this->creator());
        Http::fake([
            '127.0.0.1:8011/api/stats' => Http::response(['total_klien' => 12, 'campaign_berjalan' => 5, 'post_dijadwalkan' => 9, 'post_tayang' => 14]),
            '127.0.0.1:8011/api/posts*' => Http::response([
                'data' => [[
                    'id' => 7, 'judul' => 'Countdown peluncuran', 'format' => 'story', 'tanggal_tayang' => '2026-11-21', 'status' => 'dijadwalkan',
                    'platform' => ['id' => 1, 'nama_platform' => 'Instagram'], 'campaign' => ['id' => 1, 'nama_campaign' => 'Peluncuran Produk'], 'klien' => ['id' => 1, 'nama_brand' => 'Glow Skincare'],
                ]],
                'meta' => ['current_page' => 1, 'last_page' => 3],
            ]),
        ]);

        $html = $this->get('/')->assertOk()->assertSee('class="active">Dashboard', false)->assertDontSee('Server API internal tidak bisa dihubungi')->getContent();
        foreach (['12', '5', '9', '14'] as $angka) {
            $this->assertStringContainsString("<div class=\"value\">$angka</div>", $html);
        }

        $this->get('/posts/jadwal')->assertOk()
            ->assertSee('Countdown peluncuran')->assertSee('Glow Skincare')->assertSee('21 Nov 2026')
            ->assertSee('Halaman 1 dari 3')->assertSee('class="active">Jadwal', false)->assertDontSee('class="active">Post', false);

        Http::assertSent(fn ($request) => str_starts_with($request->url(), 'http://127.0.0.1:8011/api/posts')
            && $request['status'] === 'dijadwalkan' && $request['urut'] === 'terdekat');
    }

    public function test_tidak_error_500_saat_server_api_mati(): void
    {
        $this->actingAs($this->creator());

        Http::fake(fn () => throw new ConnectionException('mati'));
        $html = $this->get('/')->assertOk()->assertSee('Server API internal tidak bisa dihubungi')->getContent();
        $this->assertSame(4, substr_count($html, '<div class="value">0</div>'));
        $this->get('/posts/jadwal')->assertOk()->assertSee('Tidak ada post yang sedang dijadwalkan.')->assertSee('Server API internal tidak bisa dihubungi');

        Http::fake(['*' => Http::response('error', 500)]);
        $this->get('/')->assertOk()->assertSee('<div class="value">0</div>', false);
    }

    public function test_seeder_menghasilkan_data_konsisten(): void
    {
        $this->seed();
        $this->seed(UserSeeder::class);      // aman dijalankan ulang
        $this->seed(PlatformSeeder::class);

        $this->assertSame([3, 5, 12, 8, 30], [User::count(), Platform::count(), Client::count(), Campaign::count(), Post::count()]);
        $this->assertSame(0, Post::where('status', 'tayang')->whereNull('tanggal_ditayangkan')->count());
        $this->assertSame(0, Post::where('status', '!=', 'tayang')->whereNotNull('tanggal_ditayangkan')->count());
        $this->assertSame(0, Campaign::whereColumn('tanggal_selesai', '<', 'tanggal_mulai')->count());

        $this->actingAs(User::where('role', 'admin')->first());
        Http::fake(['*' => Http::response([], 500)]);
        foreach (['/clients', '/clients?page=2', '/campaigns', '/posts', '/posts?page=3', '/platforms', '/clients/1', '/campaigns/1', '/posts/1'] as $url) {
            $this->get($url)->assertOk();
        }
        $this->get('/posts')->assertSee('class="pagination"', false)->assertSee('Berikutnya &raquo;', false)->assertDontSee('<svg', false);
    }
}
