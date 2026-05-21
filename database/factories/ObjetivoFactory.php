<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Objetivo>
 */
class ObjetivoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'usuario_id' => \App\Models\Usuario::factory(),
            'tipo_objetivo' => fake()->randomElement(['Días Entrenados', 'Volumen (kg levantados)', 'Peso Corporal']),
            'valor_objetivo' => fake()->numberBetween(1, 100),
            'fecha_limite' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'estado' => 'en_progreso',
        ];
    }
}
