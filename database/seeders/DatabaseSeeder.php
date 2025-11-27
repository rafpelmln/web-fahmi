<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create sample users
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Guru Budi',
            'email' => 'guru@example.com',
            'password' => bcrypt('password'),
            'role' => 'teacher',
        ]);

        User::factory()->create([
            'name' => 'Siswa Andi',
            'email' => 'siswa@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        // Jalankan PortfolioSeeder untuk membuat data portfolio
        $this->call(PortfolioSeeder::class);
    }
}
