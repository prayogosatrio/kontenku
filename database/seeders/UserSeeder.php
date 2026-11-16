<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['email' => 'admin@kontenku.test', 'name' => 'Prayogo Adhi Satrio', 'role' => 'admin'],
            ['email' => 'creator1@kontenku.test', 'name' => 'Siti Rahmawati', 'role' => 'creator'],
            ['email' => 'creator2@kontenku.test', 'name' => 'Ahmad Fauzi', 'role' => 'creator'],
        ];

        foreach ($users as $user) {
            // updateOrCreate: aman dijalankan berkali-kali tanpa membuat baris duplikat
            User::updateOrCreate(
                ['email' => $user['email']],
                ['name' => $user['name'], 'password' => Hash::make('password'), 'role' => $user['role']]
            );
        }
    }
}
