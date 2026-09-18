<?php

namespace Database\Factories;

use App\Models\TypeBien;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TypeBien>
 */
class TypeBienFactory extends Factory
{
    protected $model = TypeBien::class;

    public function definition(): array
    {
        return [
            'type' => fake()->unique()->randomElement([
                'Appartement',
                'Maison',
                'Villa',
            ]),
        ];
    }
}