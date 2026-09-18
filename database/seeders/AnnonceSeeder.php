<?php

namespace Database\Seeders;

use App\Models\Annonce;
use Database\Factories\AnnonceFactory;
use Illuminate\Database\Seeder;

class AnnonceSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Annonces validées
        Annonce::factory()
            ->valide()
            ->count(15)
            ->create();

        // Annonces en attente de validation
        Annonce::factory()
            ->count(5)
            ->create();
    }
}