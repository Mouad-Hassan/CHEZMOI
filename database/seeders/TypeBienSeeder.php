<?php

namespace Database\Seeders;

use App\Models\TypeBien;
use Database\Factories\TypeBienFactory;
use Illuminate\Database\Seeder;

class TypeBienSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        TypeBien::factory()
            ->count(3)
            ->create();
    }
}