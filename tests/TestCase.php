<?php

namespace Tests;

use App\Models\Campaign;
use App\Models\Client;
use App\Models\Platform;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function admin(): User
    {
        return User::create(['name' => 'Admin Uji', 'email' => 'admin@uji.test', 'password' => 'password', 'role' => 'admin']);
    }

    protected function creator(): User
    {
        return User::create(['name' => 'Creator Uji', 'email' => 'creator@uji.test', 'password' => 'password', 'role' => 'creator']);
    }

    protected function buatKlien(array $data = []): Client
    {
        return Client::create($data + [
            'nama_brand' => 'Kopi Senja', 'industri' => 'Kuliner', 'nama_kontak' => 'Budi Santoso',
            'email' => 'halo@kopisenja.co.id', 'nomor_telepon' => '081234567890', 'status' => 'aktif',
        ]);
    }

    protected function buatCampaign(User $user, array $data = []): Campaign
    {
        return Campaign::create($data + [
            'client_id' => $data['client_id'] ?? $this->buatKlien()->id, 'user_id' => $user->id,
            'nama_campaign' => 'Promo Akhir Tahun', 'tanggal_mulai' => '2026-11-01',
            'tanggal_selesai' => '2026-12-31', 'status' => 'berjalan',
        ]);
    }

    protected function buatPost(Campaign $campaign, array $data = []): Post
    {
        return Post::create($data + [
            'campaign_id' => $campaign->id,
            'platform_id' => $data['platform_id'] ?? Platform::firstOrCreate(['nama_platform' => 'Instagram'])->id,
            'judul' => 'Teaser produk baru', 'caption' => 'Segera hadir!', 'format' => 'reels',
            'tanggal_tayang' => '2026-11-20', 'status' => 'dijadwalkan',
        ]);
    }
}
