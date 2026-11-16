<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\Platform;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected array $judul = [
        'Teaser produk baru', 'Behind the scenes', 'Tips penggunaan produk', 'Testimoni pelanggan',
        'Pengumuman giveaway', 'Promo spesial minggu ini', 'Cerita di balik brand', 'Tanya jawab dengan followers',
        'Countdown peluncuran', 'Kolaborasi dengan kreator', 'Rekap kegiatan bulan ini', 'Ucapan hari besar',
    ];

    public function definition(): array
    {
        $campaign = Campaign::inRandomOrder()->first();
        $judul = fake()->randomElement($this->judul);

        // Tanggal tayang diambil dari dalam periode campaign, dan status dibuat konsisten:
        // yang sudah lewat = tayang, yang hari ini atau akan datang = draft/dijadwalkan.
        $tanggalTayang = fake()->dateTimeBetween($campaign->tanggal_mulai, $campaign->tanggal_selesai);
        $sudahLewat = $tanggalTayang->format('Y-m-d') < now()->toDateString();
        $status = $sudahLewat ? 'tayang' : fake()->randomElement(['draft', 'dijadwalkan', 'dijadwalkan']);

        return [
            'campaign_id' => $campaign->id,
            'platform_id' => Platform::inRandomOrder()->value('id'),
            'judul' => $judul,
            'caption' => $judul.' dari '.$campaign->client->nama_brand.'. Jangan sampai ketinggalan, cek selengkapnya di profil kami!',
            'format' => fake()->randomElement(Post::FORMATS),
            'tanggal_tayang' => $tanggalTayang->format('Y-m-d'),
            'tanggal_ditayangkan' => $status === 'tayang' ? $tanggalTayang->format('Y-m-d') : null,
            'status' => $status,
        ];
    }
}
