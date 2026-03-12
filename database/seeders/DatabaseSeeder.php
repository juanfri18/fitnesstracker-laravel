<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear un usuario de prueba
        $user = \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Crear métricas para el usuario
        \App\Models\Metrica::create([
            'user_id' => $user->id,
            'peso' => 75.5,
            'altura' => 180,
            'fecha_registro' => now(),
        ]);

        // Crear ejercicios
        $ejercicio1 = \App\Models\Ejercicio::create(['nombre' => 'Press de Banca', 'grupo_muscular' => 'Pecho']);
        $ejercicio2 = \App\Models\Ejercicio::create(['nombre' => 'Sentadillas', 'grupo_muscular' => 'Piernas']);
        $ejercicio3 = \App\Models\Ejercicio::create(['nombre' => 'Dominadas', 'grupo_muscular' => 'Espalda']);

        // Crear entrenamientos
        $entrenamiento = \App\Models\Entrenamiento::create([
            'user_id' => $user->id,
            'tipo' => 'Fuerza',
            'fecha' => now()->subDay(),
            'duracion_minutos' => 60,
            'notas' => 'Buen entrenamiento de prueba',
        ]);

        // Asignar ejercicios (entrenamiento_detalles pivot)
        $entrenamiento->ejercicios()->attach($ejercicio1->id, [
            'grupo_muscular' => 'Pecho',
            'series' => 4,
            'repeticiones' => 10,
            'carga_kg' => 60,
        ]);
        
        $entrenamiento->ejercicios()->attach($ejercicio2->id, [
            'grupo_muscular' => 'Piernas',
            'series' => 4,
            'repeticiones' => 12,
            'carga_kg' => 80,
        ]);

        // Crear objetivo
        \App\Models\Objetivo::create([
            'user_id' => $user->id,
            'tipo_objetivo' => 'Frecuencia Semanal',
            'valor_objetivo' => 4,
            'estado' => 'en_progreso',
            'fecha_limite' => now()->addDays(30),
        ]);
    }
}
