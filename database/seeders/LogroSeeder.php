<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LogroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Logro::create([
            'nombre' => '¡Primer Paso!',
            'descripcion' => 'Has registrado tu primer entrenamiento. El inicio de un gran camino.',
            'icono' => 'fas fa-shoe-prints',
            'criterio' => 'primer_entreno',
            'puntos' => 50
        ]);

        \App\Models\Logro::create([
            'nombre' => 'Constancia Pura',
            'descripcion' => 'Entrenaste 3 días del tirón, demostrando tu fuerza de voluntad.',
            'icono' => 'fas fa-fire',
            'criterio' => 'racha_3_dias',
            'puntos' => 150
        ]);

        \App\Models\Logro::create([
            'nombre' => 'Levantador NATO',
            'descripcion' => 'Has completado 5 sesiones de fuerza. Estás construyendo puro músculo.',
            'icono' => 'fas fa-dumbbell',
            'criterio' => '5_sesiones_fuerza',
            'puntos' => 200
        ]);
        \App\Models\Logro::create([
            'nombre' => 'Maratonista',
            'descripcion' => 'Has superado los 10km de carrera en una sola sesión. ¡Imparable!',
            'icono' => 'fas fa-route', // Icono de ruta
            'criterio' => 'carrera_10km',
            'puntos' => 300
        ]);

        \App\Models\Logro::create([
            'nombre' => 'Leyenda del Sudor',
            'descripcion' => 'Has acumulado más de 1.000 minutos de entrenamiento total.',
            'icono' => 'fas fa-stopwatch', // Icono de cronómetro
            'criterio' => '1000_minutos',
            'puntos' => 500
        ]);
    }
}
