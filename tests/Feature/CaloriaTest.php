<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Entrenamiento;

class CaloriaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prueba que el cálculo de calorías de Carrera es correcto (duración * 11).
     */
    public function test_calorias_carrera_se_calculan_correctamente(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/entrenamientos', [
            'fecha' => now()->toDateString(),
            'modulo' => 'carrera',
            'tiempo' => 30,      // 30 minutos
            'distancia' => 5,    // 5 km
            'sensacion' => 7,
        ]);

        $response->assertRedirect('/');

        // Verificar que se guardó en BD
        $entreno = Entrenamiento::where('user_id', $user->id)->first();
        $this->assertNotNull($entreno);
        $this->assertEquals('Carrera', $entreno->tipo);
        $this->assertEquals(30, $entreno->duracion_minutos);

        // Las calorías (30 * 11 = 330) deben estar en las notas
        $this->assertStringContainsString('330 kcal', $entreno->notas);
    }

    /**
     * Prueba que el cálculo de calorías de Fuerza es correcto (duración * 6.5).
     */
    public function test_calorias_fuerza_se_calculan_correctamente(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/entrenamientos', [
            'fecha' => now()->toDateString(),
            'modulo' => 'fuerza',
            'tiempo' => 60,      // 60 minutos
            'sensacion' => 8,
        ]);

        $response->assertRedirect('/');

        $entreno = Entrenamiento::where('user_id', $user->id)->first();
        $this->assertNotNull($entreno);
        $this->assertEquals('Fuerza', $entreno->tipo);

        // 60 * 6.5 = 390 kcal
        $this->assertStringContainsString('390 kcal', $entreno->notas);
    }

    /**
     * Prueba que el cálculo de calorías de Caminata es correcto (duración * 4.5).
     */
    public function test_calorias_caminata_se_calculan_correctamente(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/entrenamientos', [
            'fecha' => now()->toDateString(),
            'modulo' => 'caminata',
            'tiempo' => 45,      // 45 minutos
            'distancia' => 3,
            'sensacion' => 4,
        ]);

        $response->assertRedirect('/');

        $entreno = Entrenamiento::where('user_id', $user->id)->first();
        $this->assertNotNull($entreno);
        $this->assertEquals('Caminata', $entreno->tipo);

        // 45 * 4.5 = 202.5 → round = 203 o 202
        $this->assertMatchesRegularExpression('/20[23] kcal/', $entreno->notas);
    }

    /**
     * Caso límite: duración 0 minutos, las calorías deben ser 0 (no deben aparecer en notas).
     */
    public function test_calorias_cero_minutos_no_genera_kcal_en_notas(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/entrenamientos', [
            'fecha' => now()->toDateString(),
            'modulo' => 'carrera',
            'tiempo' => 0,
            'sensacion' => 5,
        ]);

        $response->assertRedirect('/');

        $entreno = Entrenamiento::where('user_id', $user->id)->first();
        $this->assertNotNull($entreno);

        // Con 0 minutos, no debería haber mención de kcal
        $this->assertStringNotContainsString('kcal', $entreno->notas);
    }
}
