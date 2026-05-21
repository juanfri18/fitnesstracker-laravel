<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Usuario;
use App\Models\Entrenamiento;

class MetricaTest extends TestCase
{
    use RefreshDatabase;

    public function test_metrica_calcula_correctamente_estadisticas_normales(): void
    {
        $user = Usuario::factory()->create();

        Entrenamiento::factory()->count(3)->create([
            'usuario_id' => $user->id,
            'duracion_minutos' => 30,
            'fecha' => now()->toDateString(),
            'tipo' => 'Fuerza',
        ]);

        $response = $this->actingAs($user)->get('/estadisticas');

        $response->assertStatus(200);

        $response->assertViewHas('totales', function ($totales) {
            return $totales['total_entrenos'] == 3 && $totales['total_min'] == 90;
        });

        $response->assertViewHas('semana', function ($semana) {
            return $semana['sem_entrenos'] == 3 && $semana['sem_min'] == 90;
        });
    }

    public function test_metrica_no_falla_sin_datos_caso_limite(): void
    {
        $user = Usuario::factory()->create();

        $response = $this->actingAs($user)->get('/estadisticas');

        $response->assertStatus(200);

        $response->assertViewHas('totales', function ($totales) {
            return $totales['total_entrenos'] == 0 && $totales['total_min'] == 0;
        });

        $response->assertViewHas('tendencia_porcentaje', 0);
    }
}
