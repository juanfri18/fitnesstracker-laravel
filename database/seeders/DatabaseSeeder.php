<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Genera datos de prueba suficientes para cumplir los requisitos mínimos:
     *   - 5 usuarios
     *   - 10+ entrenamientos
     *   - 20+ ejercicios
     *   - 5+ objetivos
     */
    public function run(): void
    {
        // =============================================
        // 1. CREAR 5 USUARIOS DE PRUEBA
        // =============================================
        $user1 = \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $user2 = \App\Models\User::factory()->create([
            'name' => 'María López',
            'email' => 'maria@example.com',
            'password' => bcrypt('password'),
        ]);

        $user3 = \App\Models\User::factory()->create([
            'name' => 'Carlos García',
            'email' => 'carlos@example.com',
            'password' => bcrypt('password'),
        ]);

        $user4 = \App\Models\User::factory()->create([
            'name' => 'Laura Fernández',
            'email' => 'laura@example.com',
            'password' => bcrypt('password'),
        ]);

        $user5 = \App\Models\User::factory()->create([
            'name' => 'Pedro Martínez',
            'email' => 'pedro@example.com',
            'password' => bcrypt('password'),
        ]);

        $usuarios = [$user1, $user2, $user3, $user4, $user5];

        // Crear métricas para el usuario principal
        \App\Models\Metrica::create([
            'user_id' => $user1->id,
            'peso' => 75.5,
            'altura' => 180,
            'fecha_registro' => now(),
        ]);

        // =============================================
        // 2. CREAR 20+ EJERCICIOS
        // =============================================
        $ejerciciosData = [
            // Pecho (4)
            ['nombre' => 'Press de Banca',       'grupo_muscular' => 'Pecho'],
            ['nombre' => 'Press Inclinado',       'grupo_muscular' => 'Pecho'],
            ['nombre' => 'Aperturas con Mancuerna','grupo_muscular' => 'Pecho'],
            ['nombre' => 'Fondos en Paralelas',   'grupo_muscular' => 'Pecho'],
            // Piernas (4)
            ['nombre' => 'Sentadillas',           'grupo_muscular' => 'Piernas'],
            ['nombre' => 'Peso Muerto',           'grupo_muscular' => 'Piernas'],
            ['nombre' => 'Prensa de Piernas',     'grupo_muscular' => 'Piernas'],
            ['nombre' => 'Zancadas',              'grupo_muscular' => 'Piernas'],
            // Espalda (4)
            ['nombre' => 'Dominadas',             'grupo_muscular' => 'Espalda'],
            ['nombre' => 'Remo con Barra',        'grupo_muscular' => 'Espalda'],
            ['nombre' => 'Jalón al Pecho',        'grupo_muscular' => 'Espalda'],
            ['nombre' => 'Remo con Mancuerna',    'grupo_muscular' => 'Espalda'],
            // Hombros (3)
            ['nombre' => 'Press Militar',         'grupo_muscular' => 'Hombros'],
            ['nombre' => 'Elevaciones Laterales', 'grupo_muscular' => 'Hombros'],
            ['nombre' => 'Pájaros',               'grupo_muscular' => 'Hombros'],
            // Bíceps (2)
            ['nombre' => 'Curl con Barra',        'grupo_muscular' => 'Bíceps'],
            ['nombre' => 'Curl Martillo',         'grupo_muscular' => 'Bíceps'],
            // Tríceps (2)
            ['nombre' => 'Extensión de Tríceps',  'grupo_muscular' => 'Tríceps'],
            ['nombre' => 'Press Francés',         'grupo_muscular' => 'Tríceps'],
            // Core (2)
            ['nombre' => 'Plancha Abdominal',     'grupo_muscular' => 'Core'],
            ['nombre' => 'Crunch',                'grupo_muscular' => 'Core'],
        ];

        $ejercicios = [];
        foreach ($ejerciciosData as $ej) {
            $ejercicios[] = \App\Models\Ejercicio::create($ej);
        }

        // =============================================
        // 3. CREAR 10+ ENTRENAMIENTOS (repartidos entre usuarios)
        // =============================================

        // -- User 1: 3 entrenamientos --
        $entrenamiento1 = \App\Models\Entrenamiento::create([
            'user_id' => $user1->id,
            'tipo' => 'Fuerza',
            'fecha' => now()->subDay(),
            'duracion_minutos' => 60,
            'calorias_estimadas' => 390,
            'notas' => 'Sensación: 8/10 | Aprox: 390 kcal',
        ]);
        $entrenamiento1->ejercicios()->attach($ejercicios[0]->id, [
            'grupo_muscular' => 'Pecho', 'series' => 4, 'repeticiones' => 10, 'carga_kg' => 60,
        ]);
        $entrenamiento1->ejercicios()->attach($ejercicios[4]->id, [
            'grupo_muscular' => 'Piernas', 'series' => 4, 'repeticiones' => 12, 'carga_kg' => 80,
        ]);

        \App\Models\Entrenamiento::create([
            'user_id' => $user1->id,
            'tipo' => 'Carrera',
            'fecha' => now()->subDays(2),
            'duracion_minutos' => 45,
            'calorias_estimadas' => 495,
            'notas' => 'Distancia: 5km | Sensación: 8/10 | Aprox: 495 kcal',
        ]);

        \App\Models\Entrenamiento::create([
            'user_id' => $user1->id,
            'tipo' => 'Caminata',
            'fecha' => now()->subDays(4),
            'duracion_minutos' => 30,
            'calorias_estimadas' => 135,
            'notas' => 'Sensación: 6/10 | Aprox: 135 kcal',
        ]);

        // -- User 2: 3 entrenamientos --
        $ent4 = \App\Models\Entrenamiento::create([
            'user_id' => $user2->id,
            'tipo' => 'Fuerza',
            'fecha' => now()->subDays(1),
            'duracion_minutos' => 50,
            'calorias_estimadas' => 325,
            'notas' => 'Sensación: 7/10 | Aprox: 325 kcal',
        ]);
        $ent4->ejercicios()->attach($ejercicios[8]->id, [
            'grupo_muscular' => 'Espalda', 'series' => 3, 'repeticiones' => 10, 'carga_kg' => 0,
        ]);
        $ent4->ejercicios()->attach($ejercicios[9]->id, [
            'grupo_muscular' => 'Espalda', 'series' => 4, 'repeticiones' => 8, 'carga_kg' => 50,
        ]);

        \App\Models\Entrenamiento::create([
            'user_id' => $user2->id,
            'tipo' => 'Carrera',
            'fecha' => now()->subDays(3),
            'duracion_minutos' => 35,
            'calorias_estimadas' => 385,
            'notas' => 'Distancia: 4km | Sensación: 7/10 | Aprox: 385 kcal',
        ]);

        \App\Models\Entrenamiento::create([
            'user_id' => $user2->id,
            'tipo' => 'Caminata',
            'fecha' => now()->subDays(5),
            'duracion_minutos' => 40,
            'calorias_estimadas' => 180,
            'notas' => 'Sensación: 5/10 | Aprox: 180 kcal',
        ]);

        // -- User 3: 2 entrenamientos --
        $ent7 = \App\Models\Entrenamiento::create([
            'user_id' => $user3->id,
            'tipo' => 'Fuerza',
            'fecha' => now()->subDays(2),
            'duracion_minutos' => 70,
            'calorias_estimadas' => 455,
            'notas' => 'Sensación: 9/10 | Aprox: 455 kcal',
        ]);
        $ent7->ejercicios()->attach($ejercicios[12]->id, [
            'grupo_muscular' => 'Hombros', 'series' => 4, 'repeticiones' => 10, 'carga_kg' => 40,
        ]);

        \App\Models\Entrenamiento::create([
            'user_id' => $user3->id,
            'tipo' => 'Carrera',
            'fecha' => now()->subDays(6),
            'duracion_minutos' => 55,
            'calorias_estimadas' => 605,
            'notas' => 'Distancia: 8km | Sensación: 9/10 | Aprox: 605 kcal',
        ]);

        // -- User 4: 2 entrenamientos --
        \App\Models\Entrenamiento::create([
            'user_id' => $user4->id,
            'tipo' => 'Caminata',
            'fecha' => now()->subDays(1),
            'duracion_minutos' => 60,
            'calorias_estimadas' => 270,
            'notas' => 'Sensación: 7/10 | Aprox: 270 kcal',
        ]);

        $ent10 = \App\Models\Entrenamiento::create([
            'user_id' => $user4->id,
            'tipo' => 'Fuerza',
            'fecha' => now()->subDays(3),
            'duracion_minutos' => 45,
            'calorias_estimadas' => 293,
            'notas' => 'Sensación: 6/10 | Aprox: 293 kcal',
        ]);
        $ent10->ejercicios()->attach($ejercicios[15]->id, [
            'grupo_muscular' => 'Bíceps', 'series' => 3, 'repeticiones' => 12, 'carga_kg' => 15,
        ]);

        // -- User 5: 2 entrenamientos --
        \App\Models\Entrenamiento::create([
            'user_id' => $user5->id,
            'tipo' => 'Carrera',
            'fecha' => now()->subDays(1),
            'duracion_minutos' => 25,
            'calorias_estimadas' => 275,
            'notas' => 'Distancia: 3km | Sensación: 6/10 | Aprox: 275 kcal',
        ]);

        \App\Models\Entrenamiento::create([
            'user_id' => $user5->id,
            'tipo' => 'Caminata',
            'fecha' => now()->subDays(7),
            'duracion_minutos' => 50,
            'calorias_estimadas' => 225,
            'notas' => 'Sensación: 5/10 | Aprox: 225 kcal',
        ]);

        // =============================================
        // 4. CREAR 5+ OBJETIVOS (repartidos entre usuarios)
        // =============================================
        \App\Models\Objetivo::create([
            'user_id' => $user1->id,
            'tipo_objetivo' => 'Días Entrenados',
            'valor_objetivo' => 4,
            'estado' => 'en_progreso',
            'fecha_inicio' => now(),
            'fecha_limite' => now()->addDays(30),
        ]);

        \App\Models\Objetivo::create([
            'user_id' => $user1->id,
            'tipo_objetivo' => 'Peso Corporal',
            'valor_objetivo' => 70,
            'estado' => 'en_progreso',
            'fecha_inicio' => now(),
            'fecha_limite' => now()->addDays(60),
        ]);

        \App\Models\Objetivo::create([
            'user_id' => $user2->id,
            'tipo_objetivo' => 'Volumen (kg levantados)',
            'valor_objetivo' => 500,
            'estado' => 'en_progreso',
            'fecha_inicio' => now(),
            'fecha_limite' => now()->addDays(30),
        ]);

        \App\Models\Objetivo::create([
            'user_id' => $user3->id,
            'tipo_objetivo' => 'Días Entrenados',
            'valor_objetivo' => 10,
            'estado' => 'en_progreso',
            'fecha_inicio' => now(),
            'fecha_limite' => now()->addDays(45),
        ]);

        \App\Models\Objetivo::create([
            'user_id' => $user4->id,
            'tipo_objetivo' => 'Peso Corporal',
            'valor_objetivo' => 65,
            'estado' => 'en_progreso',
            'fecha_inicio' => now(),
            'fecha_limite' => now()->addDays(90),
        ]);
    }
}
