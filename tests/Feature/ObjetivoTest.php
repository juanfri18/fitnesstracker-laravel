<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Objetivo;

class ObjetivoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prueba el caso normal de creación y redirección de metas.
     */
    public function test_usuario_puede_crear_objetivo_validado(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/objetivos', [
            'tipo_objetivo' => 'Frecuencia Semanal',
            'valor_objetivo' => 4,
            'fecha_limite' => now()->addDays(10)->toDateString()
        ]);

        // Redirige correctamente tras crear
        $response->assertRedirect('/objetivos');
        
        // Verifica en base de datos
        $this->assertDatabaseHas('objetivos', [
            'user_id' => $user->id,
            'tipo_objetivo' => 'Frecuencia Semanal',
            'valor_objetivo' => 4,
        ]);
    }

    /**
     * Prueba el caso límite: evitar metas con valores inválidos (0 o negativos).
     */
    public function test_objetivo_falla_con_valores_negativos_o_cero(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/objetivos', [
            'tipo_objetivo' => 'Volumen Mensual',
            'valor_objetivo' => 0, // Fallo a propósito
            'fecha_limite' => now()->addDays(10)->toDateString()
        ]);

        // Debe saltar el error de validación (sessionHasErrors)
        $response->assertSessionHasErrors('valor_objetivo');
        
        // Verifica que NO se guardó en BD
        $this->assertDatabaseCount('objetivos', 0);
    }
}
