<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    // Nama brand dan industri dikurasi manual: Faker tidak punya generator "nama brand" yang realistis.
    protected array $brands = [
        'Kopi Senja' => 'Kuliner',
        'Batik Larasati' => 'Fashion',
        'Hijab Nayara' => 'Fashion',
        'Dapur Bu Rini' => 'Kuliner',
        'Tani Makmur' => 'Agrikultur',
        'Klinik Sehat Sentosa' => 'Kesehatan',
        'Bimbel Cendekia' => 'Pendidikan',
        'Rumah Tenun Nusantara' => 'Kerajinan',
        'Sepatu Langkah' => 'Fashion',
        'Travel Jelajah Negeri' => 'Pariwisata',
        'Glow Skincare' => 'Kecantikan',
        'Bengkel Maju Jaya' => 'Otomotif',
    ];

    public function definition(): array
    {
        $brand = fake()->unique()->randomElement(array_keys($this->brands));

        return [
            'nama_brand' => $brand,
            'industri' => $this->brands[$brand],
            'nama_kontak' => fake()->firstName().' '.fake()->lastName(),
            'email' => 'halo@'.Str::slug($brand, '').'.co.id',
            'nomor_telepon' => fake()->numerify('08##########'),
            'status' => fake()->randomElement(['aktif', 'aktif', 'aktif', 'nonaktif']),
        ];
    }
}
