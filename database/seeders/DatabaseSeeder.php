<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Urutan mengikuti ketergantungan foreign key:
     * campaign butuh client + user, post butuh campaign + platform.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PlatformSeeder::class,
            ClientSeeder::class,
            CampaignSeeder::class,
            PostSeeder::class,
        ]);
    }
}
