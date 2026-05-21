<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Usuario;
use App\Models\Objetivo;

class ObjetivoTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_crear_objetivo_validado(): void
    {
        $user = Usuario::factory()->create();

        $response = $this->actingAs($user)->post('/objetivos', [
            'tipo_objetivo' => 'Días Entrenados',
            'valor_objetivo' => 4,
            'fecha_limite' => now()->addDays(10)->toDateString()
        ]);

        $response->assertRedirect('/objetivos');

        $this->assertDatabaseHas('objetivos', [
            'usuario_id' => $user->id,
            'tipo_objetivo' => 'Días Entrenados',
            'valor_objetivo' => 4,
        ]);
    }

    public function test_objetivo_falla_con_valores_negativos_o_cero(): void
    {
        $user = Usuario::factory()->create();

        $response = $this->actingAs($user)->post('/objetivos', [
            'tipo_objetivo' => 'Volumen (kg levantados)',
            'valor_objetivo' => 0,
            'fecha_limite' => now()->addDays(10)->toDateString()
        ]);

        $response->assertSessionHasErrors('valor_objetivo');

        $this->assertDatabaseCount('objetivos', 0);
    }
}
