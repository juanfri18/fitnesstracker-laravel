<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Objetivo>
 */
class ObjetivoFactory extends Factory
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
            'tipo_objetivo' => fake()->randomElement(['Frecuencia Semanal', 'Volumen Mensual', 'Peso Corporal']),
            'valor_objetivo' => fake()->numberBetween(1, 100),
            'fecha_limite' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'estado' => 'en_progreso',
        ];
    }
}
