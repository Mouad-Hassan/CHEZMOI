<?php

namespace Database\Factories;

use App\Models\Annonce;
use App\Models\User;
use App\Models\TypeBien;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Annonce>
 */
class AnnonceFactory extends Factory
{
    protected $model = Annonce::class;

    public function definition(): array
    {
        return [
             'user_id' => User::where('role', User::ROLE_PROPRIETAIRE)
                ->inRandomOrder()
                ->first()
                ->id,
           'type_bien_id' => TypeBien::inRandomOrder()->first()->id,
            'titre' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'prix' => fake()->numberBetween(100000, 2000000),
            'surface' => fake()->numberBetween(30, 400),
            'nombre_chambres' => fake()->numberBetween(1, 6),
            'nombre_salles_bain' => fake()->numberBetween(1, 3),
            'ville' => fake()->randomElement(['Beni Mellal', 'Marrakech', 'Casablanca', 'Rabat']),
            'adresse' => fake()->streetAddress(),
            'statut_validation' => Annonce::STATUT_EN_ATTENTE,
            'date_publication' => now(),
        ];
    }

    public function valide(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut_validation' => Annonce::STATUT_VALIDE,
        ]);
    }
}
