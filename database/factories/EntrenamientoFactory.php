<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entrenamiento>
 */
class EntrenamientoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'usuario_id' => \App\Models\Usuario::factory(),
            'fecha' => fake()->date(),
            'tipo' => fake()->randomElement(['Fuerza', 'Carrera', 'Caminata']),
            'duracion_minutos' => fake()->numberBetween(10, 120),
        ];
    }
}
