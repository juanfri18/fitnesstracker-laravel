<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Entrenamiento;

class MetricaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prueba el caso normal: un usuario con entrenamientos.
     */
    public function test_metrica_calcula_correctamente_estadisticas_normales(): void
    {
        $user = User::factory()->create();

        // Crear 3 entrenamientos hoy de 30 minutos cada uno (Total: 90 mins)
        Entrenamiento::factory()->count(3)->create([
            'user_id' => $user->id,
            'duracion_minutos' => 30,
            'fecha' => now()->toDateString(),
            'tipo' => 'Fuerza',
        ]);

        $response = $this->actingAs($user)->get('/estadisticas');

        $response->assertStatus(200);
        
        // Verificar que la vista recibe los totales correctos
        $response->assertViewHas('totales', function ($totales) {
            return $totales['total_entrenos'] == 3 && $totales['total_min'] == 90;
        });

        $response->assertViewHas('semana', function ($semana) {
            return $semana['sem_entrenos'] == 3 && $semana['sem_min'] == 90;
        });
    }

    /**
     * Prueba el caso límite: un usuario nuevo sin datos (ceros).
     */
    public function test_metrica_no_falla_sin_datos_caso_limite(): void
    {
        $user = User::factory()->create();

        // No creamos entrenamientos para simular usuario virgen

        $response = $this->actingAs($user)->get('/estadisticas');

        $response->assertStatus(200);

        // Verificar que los totales son exactamente 0 en lugar de null/explotar
        $response->assertViewHas('totales', function ($totales) {
            return $totales['total_entrenos'] == 0 && $totales['total_min'] == 0;
        });

        $response->assertViewHas('tendencia_porcentaje', 0);
    }
}
