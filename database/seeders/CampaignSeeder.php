<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $tema = [
            'Promo Akhir Tahun', 'Peluncuran Produk Baru', 'Kampanye Ramadan', 'Brand Awareness',
            'Giveaway Followers', 'Edukasi Produk', 'Testimoni Pelanggan', 'Harbolnas 12.12',
        ];

        $userIds = User::pluck('id');

        // Ditulis manual (bukan Factory) karena tanggal dan status harus saling konsisten.
        foreach (Client::inRandomOrder()->take(8)->get() as $i => $client) {
            $mulai = now()->subDays(fake()->numberBetween(0, 40));
            $selesai = $mulai->copy()->addDays(fake()->numberBetween(14, 45));

            Campaign::create([
                'client_id' => $client->id,
                'user_id' => $userIds->random(),
                'nama_campaign' => $tema[$i].' '.$client->nama_brand,
                'tujuan' => 'Meningkatkan interaksi dan penjualan '.$client->nama_brand.' lewat media sosial.',
                'tanggal_mulai' => $mulai->toDateString(),
                'tanggal_selesai' => $selesai->toDateString(),
                'status' => $selesai->isPast() ? 'selesai' : fake()->randomElement(['draft', 'berjalan', 'berjalan']),
            ]);
        }
    }
}
