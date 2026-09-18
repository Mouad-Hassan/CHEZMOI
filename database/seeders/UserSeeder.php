<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Administrateur
        User::factory()
            ->admin()
            ->create([
                'nom' => 'Administrateur',
                'email' => 'admin@example.com',
            ]);

        // Propriétaires
        User::factory()
            ->proprietaire()
            ->count(5)
            ->create();

        // Acheteurs
        User::factory()
            ->acheteur()
            ->count(10)
            ->create();
    }
}