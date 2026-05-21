<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Usuario;
use App\Models\Entrenamiento;

class CaloriaTest extends TestCase
{
    use RefreshDatabase;

    public function test_calorias_carrera_se_calculan_correctamente(): void
    {
        $user = Usuario::factory()->create();

        $response = $this->actingAs($user)->post('/entrenamientos', [
            'fecha' => now()->toDateString(),
            'modulo' => 'carrera',
            'tiempo' => 30,
            'distancia' => 5,
            'sensacion' => 7,
        ]);

        $response->assertRedirect('/');

        $entreno = Entrenamiento::where('usuario_id', $user->id)->first();
        $this->assertNotNull($entreno);
        $this->assertEquals('Carrera', $entreno->tipo);
        $this->assertEquals(30, $entreno->duracion_minutos);

        // 30 * 11 = 330 kcal
        $this->assertStringContainsString('330 kcal', $entreno->notas);
    }

    public function test_calorias_fuerza_se_calculan_correctamente(): void
    {
        $user = Usuario::factory()->create();

        $response = $this->actingAs($user)->post('/entrenamientos', [
            'fecha' => now()->toDateString(),
            'modulo' => 'fuerza',
            'tiempo' => 60,
            'sensacion' => 8,
        ]);

        $response->assertRedirect('/');

        $entreno = Entrenamiento::where('usuario_id', $user->id)->first();
        $this->assertNotNull($entreno);
        $this->assertEquals('Fuerza', $entreno->tipo);

        // 60 * 6.5 = 390 kcal
        $this->assertStringContainsString('390 kcal', $entreno->notas);
    }

    public function test_calorias_caminata_se_calculan_correctamente(): void
    {
        $user = Usuario::factory()->create();

        $response = $this->actingAs($user)->post('/entrenamientos', [
            'fecha' => now()->toDateString(),
            'modulo' => 'caminata',
            'tiempo' => 45,
            'distancia' => 3,
            'sensacion' => 4,
        ]);

        $response->assertRedirect('/');

        $entreno = Entrenamiento::where('usuario_id', $user->id)->first();
        $this->assertNotNull($entreno);
        $this->assertEquals('Caminata', $entreno->tipo);

        // 45 * 4.5 = 202.5 → round = 203 o 202
        $this->assertMatchesRegularExpression('/20[23] kcal/', $entreno->notas);
    }

    public function test_calorias_cero_minutos_rechazado_por_validacion(): void
    {
        $user = Usuario::factory()->create();

        $response = $this->actingAs($user)->post('/entrenamientos', [
            'fecha' => now()->toDateString(),
            'modulo' => 'carrera',
            'tiempo' => 0,
            'sensacion' => 5,
        ]);

        // La validación exige min:1 en tiempo, así que 0 debe ser rechazado
        $response->assertSessionHasErrors('tiempo');
        $this->assertDatabaseCount('entrenamientos', 0);
    }
}
