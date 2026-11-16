<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Platform;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_posts(): void
    {
        $user = $this->creator();
        $campaign = $this->buatCampaign($user);
        $ig = Platform::create(['nama_platform' => 'Instagram']);
        $tt = Platform::create(['nama_platform' => 'TikTok']);
        $a = $this->buatPost($campaign, ['platform_id' => $ig->id, 'judul' => 'Teaser produk', 'tanggal_tayang' => '2026-11-20']);
        $this->buatPost($campaign, ['platform_id' => $tt->id, 'judul' => 'Tips harian', 'status' => 'draft', 'tanggal_tayang' => '2026-11-10']);

        $this->getJson('/api/posts')->assertOk()
            ->assertJsonStructure(['data' => [['id', 'judul', 'tanggal_tayang', 'status', 'platform' => ['id', 'nama_platform'], 'campaign' => ['id', 'nama_campaign'], 'klien' => ['id', 'nama_brand']]], 'links', 'meta' => ['current_page', 'last_page', 'total']])
            ->assertJsonPath('meta.total', 2)->assertJsonPath('data.0.tanggal_tayang', '2026-11-20');
        $this->getJson('/api/posts?urut=terdekat')->assertJsonPath('data.0.judul', 'Tips harian');
        $this->getJson('/api/posts?judul=teaser')->assertJsonCount(1, 'data')->assertJsonPath('data.0.klien.nama_brand', 'Kopi Senja');
        $this->getJson('/api/posts?status=draft')->assertJsonCount(1, 'data')->assertJsonPath('data.0.judul', 'Tips harian');
        $this->getJson("/api/posts?platform_id={$tt->id}")->assertJsonCount(1, 'data')->assertJsonPath('data.0.platform.nama_platform', 'TikTok');
        $this->getJson("/api/posts/{$a->id}")->assertOk()->assertJsonPath('data.judul', 'Teaser produk');
        $this->getJson('/api/posts/999')->assertNotFound();

        $this->postJson('/api/posts', [])->assertStatus(422)->assertJsonPath('errors.judul.0', 'Judul wajib diisi.');
        $body = ['campaign_id' => $campaign->id, 'platform_id' => $ig->id, 'judul' => 'Giveaway', 'caption' => 'Ikuti!', 'format' => 'feed', 'tanggal_tayang' => '2026-12-01', 'status' => 'dijadwalkan'];
        $id = $this->postJson('/api/posts', $body)->assertStatus(201)->assertJsonPath('data.status', 'dijadwalkan')->assertJsonPath('data.platform.nama_platform', 'Instagram')->json('data.id');
        $this->putJson("/api/posts/$id", ['judul' => 'Giveaway Akhir Tahun'] + $body)->assertOk()->assertJsonPath('data.judul', 'Giveaway Akhir Tahun');
        $this->putJson("/api/posts/$id", ['judul' => ''] + $body)->assertStatus(422);

        $this->putJson("/api/posts/$id/tayang")->assertOk()->assertJsonPath('data.status', 'tayang')->assertJsonPath('data.tanggal_ditayangkan', now()->toDateString());
        $this->putJson("/api/posts/$id/tayang")->assertStatus(409);
        $this->putJson("/api/posts/$id", $body)->assertStatus(409);
        $this->putJson('/api/posts/999/tayang')->assertNotFound();

        $this->deleteJson("/api/posts/$id")->assertOk()->assertExactJson(['message' => 'Post berhasil dihapus.']);
        $this->deleteJson('/api/posts/999')->assertNotFound();
    }

    public function test_api_clients_campaigns_dan_stats(): void
    {
        $user = $this->creator();
        $client = $this->buatKlien();

        $this->getJson('/api/clients')->assertOk()->assertJsonPath('data.0.nama_brand', 'Kopi Senja')->assertJsonPath('data.0.jumlah_campaign', 0)->assertJsonStructure(['links', 'meta']);
        $this->getJson("/api/clients/{$client->id}")->assertJsonPath('data.email', 'halo@kopisenja.co.id');
        $this->getJson('/api/clients/999')->assertNotFound();

        $this->postJson('/api/campaigns', [])->assertStatus(422)->assertJsonPath('errors.user_id.0', 'PIC (user_id) wajib diisi.');
        $body = ['client_id' => $client->id, 'user_id' => $user->id, 'nama_campaign' => 'Harbolnas', 'tanggal_mulai' => '2026-12-01', 'tanggal_selesai' => '2026-12-12'];
        $this->postJson('/api/campaigns', ['tanggal_selesai' => '2026-11-01'] + $body)->assertStatus(422)->assertJsonPath('errors.tanggal_selesai.0', 'Tanggal selesai tidak boleh sebelum tanggal mulai.');
        $id = $this->postJson('/api/campaigns', $body)->assertStatus(201)
            ->assertJsonPath('data.status', 'draft')->assertJsonPath('data.pic.name', 'Creator Uji')->assertJsonPath('data.klien.nama_brand', 'Kopi Senja')
            ->assertJsonPath('data.tanggal_mulai', '2026-12-01')->assertJsonMissingPath('data.pic.password')->json('data.id');

        $campaign = Campaign::find($id);
        $campaign->update(['status' => 'berjalan']);
        $this->buatPost($campaign);
        $this->buatPost($campaign, ['status' => 'tayang', 'tanggal_ditayangkan' => '2026-11-20']);

        $this->getJson('/api/campaigns')->assertOk()->assertJsonCount(2, 'data.0.posts')->assertJsonPath('data.0.posts.0.platform', 'Instagram');
        $this->getJson('/api/campaigns?status=selesai')->assertJsonCount(0, 'data');
        $this->getJson("/api/campaigns/$id")->assertJsonPath('data.nama_campaign', 'Harbolnas');

        $this->getJson('/api/stats')->assertExactJson(['total_klien' => 1, 'campaign_berjalan' => 1, 'post_dijadwalkan' => 1, 'post_tayang' => 1]);
    }

    public function test_eager_loading_mencegah_n_plus_1(): void
    {
        $campaign = $this->buatCampaign($this->creator());
        foreach (range(1, 8) as $i) {
            $this->buatPost($campaign, ['judul' => "Post $i"]);
        }

        DB::enableQueryLog();
        foreach (Post::all() as $post) {
            $post->platform->nama_platform;
        }
        $tanpa = count(DB::getQueryLog());

        DB::flushQueryLog();
        foreach (Post::with('platform')->get() as $post) {
            $post->platform->nama_platform;
        }
        $dengan = count(DB::getQueryLog());

        $this->assertSame([9, 2], [$tanpa, $dengan]);
    }
}
