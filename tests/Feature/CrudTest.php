<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Client;
use App\Models\Platform;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_crud_platform(): void
    {
        $this->actingAs($this->admin());

        $this->followingRedirects()->from('/platforms/create')->post('/platforms', [])->assertSee('Nama platform wajib diisi.');
        $this->followingRedirects()->post('/platforms', ['nama_platform' => 'Instagram', 'deskripsi' => 'Feed dan reels'])
            ->assertSee('Platform &quot;Instagram&quot; berhasil ditambahkan.', false)->assertSee('Feed dan reels');
        $this->followingRedirects()->from('/platforms/create')->post('/platforms', ['nama_platform' => 'Instagram'])
            ->assertSee('Nama platform sudah terdaftar.');

        $platform = Platform::first();
        $this->get("/platforms/{$platform->id}/edit")->assertOk()->assertSee('value="Instagram"', false);
        // nama tidak berubah: tidak boleh ditolak oleh aturan unique
        $this->followingRedirects()->put("/platforms/{$platform->id}", ['nama_platform' => 'Instagram', 'deskripsi' => 'Baru'])
            ->assertSee('berhasil diperbarui');
        $this->followingRedirects()->from("/platforms/{$platform->id}/edit")->put("/platforms/{$platform->id}", ['nama_platform' => ''])
            ->assertSee('Nama platform wajib diisi.');

        // platform yang masih dipakai post tidak bisa dihapus
        $this->buatPost($this->buatCampaign(auth()->user()), ['platform_id' => $platform->id]);
        $this->followingRedirects()->delete("/platforms/{$platform->id}")->assertSee('tidak bisa dihapus karena masih dipakai 1 post');
        $this->assertSame(1, Platform::count());

        Post::query()->delete();
        $this->followingRedirects()->delete("/platforms/{$platform->id}")->assertSee('Platform berhasil dihapus.');
        $this->assertSame(0, Platform::count());
        $this->get('/platforms/999/edit')->assertNotFound();
    }

    public function test_crud_klien_dan_pencarian(): void
    {
        $this->actingAs($this->creator());
        $data = ['nama_brand' => 'Batik Larasati', 'industri' => 'Fashion', 'nama_kontak' => 'Sari', 'email' => 'halo@larasati.co.id', 'nomor_telepon' => '0812', 'status' => 'aktif'];

        $kosong = $this->followingRedirects()->from('/clients/create')->post('/clients', []);
        foreach (['Nama brand wajib diisi.', 'Industri wajib diisi.', 'Nama kontak wajib diisi.', 'Email wajib diisi.', 'Nomor telepon wajib diisi.', 'Status wajib diisi.'] as $pesan) {
            $kosong->assertSee($pesan);
        }
        $this->followingRedirects()->from('/clients/create')->post('/clients', ['email' => 'bukan-email', 'nama_brand' => 'Coba'] + $data)
            ->assertSee('Format Email tidak valid.')->assertSee('value="Coba"', false);

        $this->followingRedirects()->post('/clients', $data)->assertSee('Klien &quot;Batik Larasati&quot; berhasil ditambahkan.', false);
        $this->followingRedirects()->from('/clients/create')->post('/clients', $data)->assertSee('Email sudah terdaftar.');

        $client = Client::first();
        $this->get("/clients/{$client->id}")->assertOk()->assertSee('Klien ini belum punya campaign.');
        $this->get("/clients/{$client->id}/edit")->assertSee('value="halo@larasati.co.id"', false);
        $this->followingRedirects()->put("/clients/{$client->id}", ['status' => 'nonaktif'] + $data)->assertSee('berhasil diperbarui');
        $this->assertSame('nonaktif', $client->fresh()->status);

        // pencarian + pagination tetap membawa ?search=
        foreach (range(1, 12) as $i) {
            $this->buatKlien(['nama_brand' => "Kopi $i", 'email' => "kopi$i@x.id"]);
        }
        $this->get('/clients?search=batik')->assertSee('Batik Larasati')->assertDontSee('Kopi 1')->assertSee('1 klien ditemukan');
        $this->get('/clients?search=kopi')->assertSee('search=kopi&amp;page=2', false)->assertDontSee('Batik Larasati');
        $this->get('/clients?search=zzz')->assertSee('Tidak ada klien dengan nama');

        // klien yang punya campaign tidak bisa dihapus
        $this->buatCampaign(auth()->user(), ['client_id' => $client->id]);
        $this->followingRedirects()->delete("/clients/{$client->id}")->assertSee('tidak bisa dihapus karena masih punya 1 campaign');
        $this->assertNotNull($client->fresh());
        $this->get("/clients/{$client->id}")->assertSee('Promo Akhir Tahun')->assertSee('Creator Uji');
    }

    public function test_crud_campaign(): void
    {
        $user = $this->creator();
        $this->actingAs($user);
        $client = $this->buatKlien();
        $data = ['client_id' => $client->id, 'nama_campaign' => 'Kampanye Ramadan', 'tanggal_mulai' => '2026-11-01', 'tanggal_selesai' => '2026-11-30', 'status' => 'draft'];

        $this->get('/campaigns/create')->assertOk()->assertSee('Kopi Senja (Kuliner)')->assertSee('otomatis dari akun yang login');
        $this->followingRedirects()->from('/campaigns/create')->post('/campaigns', [])->assertSee('Klien wajib diisi.')->assertSee('Nama campaign wajib diisi.');
        $this->followingRedirects()->from('/campaigns/create')->post('/campaigns', ['tanggal_selesai' => '2026-10-01'] + $data)
            ->assertSee('Tanggal selesai tidak boleh sebelum tanggal mulai.');
        $this->followingRedirects()->from('/campaigns/create')->post('/campaigns', ['client_id' => 999] + $data)->assertSee('Klien yang dipilih tidak valid.');

        // user_id dari form diabaikan: PIC selalu akun yang login
        $lain = $this->admin();
        $this->followingRedirects()->post('/campaigns', $data + ['user_id' => $lain->id])
            ->assertSee('Campaign &quot;Kampanye Ramadan&quot; berhasil dibuat.', false)->assertSee('Campaign ini belum punya post.');
        $campaign = Campaign::first();
        $this->assertSame($user->id, $campaign->user_id);

        $this->get('/campaigns')->assertSee('Kampanye Ramadan')->assertSee('Kopi Senja')->assertSee('<span class="badge badge-draft">Draft</span>', false);
        $this->get('/campaigns?status=selesai')->assertDontSee('Kampanye Ramadan');
        $this->get("/campaigns/{$campaign->id}/edit")->assertSee('value="2026-11-01"', false);
        $this->followingRedirects()->put("/campaigns/{$campaign->id}", ['status' => 'berjalan'] + $data)->assertSee('berhasil diperbarui');
        $this->assertSame('berjalan', $campaign->fresh()->status);

        // hapus campaign ikut menghapus post-nya
        $this->buatPost($campaign);
        $this->followingRedirects()->delete("/campaigns/{$campaign->id}")->assertSee('berhasil dihapus');
        $this->assertSame([0, 0], [Campaign::count(), Post::count()]);
        $this->get('/campaigns/999')->assertNotFound();
    }

    public function test_crud_post_filter_dan_tandai_tayang(): void
    {
        $this->actingAs($this->creator());
        $campaign = $this->buatCampaign(auth()->user());
        $ig = Platform::create(['nama_platform' => 'Instagram']);
        $tt = Platform::create(['nama_platform' => 'TikTok']);
        $data = ['campaign_id' => $campaign->id, 'platform_id' => $ig->id, 'judul' => 'Behind the scenes', 'caption' => 'Intip dapur kami', 'format' => 'reels', 'tanggal_tayang' => '2026-11-25', 'status' => 'dijadwalkan'];

        // ?campaign_id= di URL membuat campaign langsung terpilih
        $form = $this->get("/posts/create?campaign_id={$campaign->id}")->assertOk()->getContent();
        $this->assertMatchesRegularExpression('/value="'.$campaign->id.'"\s+selected/', $form);

        $kosong = $this->followingRedirects()->from('/posts/create')->post('/posts', []);
        foreach (['Campaign wajib diisi.', 'Platform wajib diisi.', 'Judul wajib diisi.', 'Caption wajib diisi.', 'Tanggal tayang wajib diisi.'] as $pesan) {
            $kosong->assertSee($pesan);
        }
        // status "tayang" tidak boleh diisi lewat form
        $this->from('/posts/create')->post('/posts', ['status' => 'tayang'] + $data)->assertSessionHasErrors('status');

        $this->followingRedirects()->post('/posts', $data)->assertSee('Post &quot;Behind the scenes&quot; berhasil ditambahkan.', false)->assertSee('Tandai Tayang');
        $post = Post::first();
        $this->buatPost($campaign, ['judul' => 'Draft TikTok', 'platform_id' => $tt->id, 'status' => 'draft']);

        $this->get('/posts')->assertSee('Behind the scenes')->assertSee('Draft TikTok')->assertSee('Kopi Senja');
        $this->get('/posts?status=draft')->assertSee('Draft TikTok')->assertDontSee('Behind the scenes');
        $this->get("/posts?platform_id={$ig->id}")->assertSee('Behind the scenes')->assertDontSee('Draft TikTok');
        $this->get('/posts?search=behind')->assertSee('Behind the scenes')->assertDontSee('Draft TikTok');

        $this->get("/posts/{$post->id}/edit")->assertSee('value="Behind the scenes"', false)->assertSee('value="2026-11-25"', false);
        $this->followingRedirects()->put("/posts/{$post->id}", ['judul' => 'BTS dapur'] + $data)->assertSee('berhasil diperbarui')->assertSee('BTS dapur');

        // tandai tayang
        $this->followingRedirects()->from('/posts')->put("/posts/{$post->id}/tayang")->assertSee('ditandai sudah tayang');
        $post->refresh();
        $this->assertSame('tayang', $post->status);
        $this->assertSame(now()->toDateString(), $post->tanggal_ditayangkan->toDateString());

        // yang sudah tayang: tidak bisa ditayangkan ulang atau diedit
        $this->followingRedirects()->from('/posts')->put("/posts/{$post->id}/tayang")->assertSee('Hanya post berstatus');
        $this->followingRedirects()->get("/posts/{$post->id}/edit")->assertSee('Post yang sudah tayang tidak bisa diedit lagi.');
        $this->followingRedirects()->put("/posts/{$post->id}", $data)->assertSee('Post yang sudah tayang tidak bisa diedit lagi.');
        $this->assertSame('BTS dapur', $post->fresh()->judul);

        $this->followingRedirects()->delete("/posts/{$post->id}")->assertSee('Post berhasil dihapus.');
        $this->get("/posts/{$post->id}")->assertNotFound();
    }

    public function test_caption_di_escape_dari_xss(): void
    {
        $this->actingAs($this->creator());
        $post = $this->buatPost($this->buatCampaign(auth()->user()), ['caption' => "<script>alert('xss')</script>\nbaris dua"]);

        $this->get("/posts/{$post->id}")->assertDontSee("<script>alert('xss')</script>", false)->assertSee('&lt;script&gt;', false);
    }
}
