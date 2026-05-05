<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entrenamiento>
 */
class EntrenamientoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'fecha' => fake()->date(),
            'tipo' => fake()->randomElement(['Fuerza', 'Carrera', 'Caminata']),
            'duracion_minutos' => fake()->numberBetween(10, 120),
        ];
    }
}
