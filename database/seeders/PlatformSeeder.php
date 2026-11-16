<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    public function run(): void
    {
        $platforms = [
            ['nama_platform' => 'Instagram', 'deskripsi' => 'Konten visual: feed, reels, dan story.'],
            ['nama_platform' => 'TikTok', 'deskripsi' => 'Video pendek vertikal.'],
            ['nama_platform' => 'LinkedIn', 'deskripsi' => 'Konten profesional dan B2B.'],
            ['nama_platform' => 'X (Twitter)', 'deskripsi' => 'Teks singkat dan percakapan real-time.'],
            ['nama_platform' => 'YouTube', 'deskripsi' => 'Video panjang dan Shorts.'],
        ];

        foreach ($platforms as $platform) {
            Platform::updateOrCreate(['nama_platform' => $platform['nama_platform']], $platform);
        }
    }
}
