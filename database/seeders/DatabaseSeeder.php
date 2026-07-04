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
        // Cargar el catálogo global de logros
        $this->call(LogroSeeder::class);

        // =============================================
        // 1. CREAR 5 USUARIOS DE PRUEBA
        // =============================================
        $user1 = \App\Models\Usuario::factory()->create([
            'nombre' => 'Test User',
            'correo' => 'test@example.com',
            'contrasena' => bcrypt('password'),
        ]);
        \App\Models\PerfilUsuario::create(['usuario_id' => $user1->id]);
        \App\Models\Racha::create(['usuario_id' => $user1->id]);

        $user2 = \App\Models\Usuario::factory()->create([
            'nombre' => 'María López',
            'correo' => 'maria@example.com',
            'contrasena' => bcrypt('password'),
        ]);
        \App\Models\PerfilUsuario::create(['usuario_id' => $user2->id]);
        \App\Models\Racha::create(['usuario_id' => $user2->id]);

        $user3 = \App\Models\Usuario::factory()->create([
            'nombre' => 'Carlos García',
            'correo' => 'carlos@example.com',
            'contrasena' => bcrypt('password'),
        ]);
        \App\Models\PerfilUsuario::create(['usuario_id' => $user3->id]);
        \App\Models\Racha::create(['usuario_id' => $user3->id]);

        $user4 = \App\Models\Usuario::factory()->create([
            'nombre' => 'Laura Fernández',
            'correo' => 'laura@example.com',
            'contrasena' => bcrypt('password'),
        ]);
        \App\Models\PerfilUsuario::create(['usuario_id' => $user4->id]);
        \App\Models\Racha::create(['usuario_id' => $user4->id]);

        $user5 = \App\Models\Usuario::factory()->create([
            'nombre' => 'Pedro Martínez',
            'correo' => 'pedro@example.com',
            'contrasena' => bcrypt('password'),
        ]);
        \App\Models\PerfilUsuario::create(['usuario_id' => $user5->id]);
        \App\Models\Racha::create(['usuario_id' => $user5->id]);

        $usuarios = [$user1, $user2, $user3, $user4, $user5];

        // Crear métricas para el usuario principal
        \App\Models\Metrica::create([
            'usuario_id' => $user1->id,
            'peso' => 75.5,
            'altura' => 180,
            'fecha_registro' => now(),
        ]);

        // =============================================
        // 2. CREAR 20+ EJERCICIOS
        // =============================================
        $ejerciciosData = [
            // Pecho (15)
            ['nombre' => 'Press de Banca (Plano con barra)', 'grupo_muscular' => 'pecho'],
            ['nombre' => 'Press Inclinado (Inclinado con barra)', 'grupo_muscular' => 'pecho'],
            ['nombre' => 'Press Declinado (Declinado con barra)', 'grupo_muscular' => 'pecho'],
            ['nombre' => 'Aperturas con Mancuernas', 'grupo_muscular' => 'pecho'],
            ['nombre' => 'Cruce de Poleas (En polea alta/media)', 'grupo_muscular' => 'pecho'],
            ['nombre' => 'Fondos en Paralelas (Pecho) (Inclinación hacia adelante)', 'grupo_muscular' => 'pecho'],
            ['nombre' => 'Flexiones de Pecho (Lagartijas)', 'grupo_muscular' => 'pecho'],
            ['nombre' => 'Pec Deck (Aperturas en máquina contactor)', 'grupo_muscular' => 'pecho'],
            ['nombre' => 'Press de Banca con Mancuernas (Plano)', 'grupo_muscular' => 'pecho'],
            ['nombre' => 'Press Inclinado con Mancuernas', 'grupo_muscular' => 'pecho'],
            ['nombre' => 'Press de Banca con Agarre Cerrado (Enfoque tricep/pecho interno)', 'grupo_muscular' => 'pecho'],
            ['nombre' => 'Cruce de Poleas Desde Abajo (Enfoque pectoral superior)', 'grupo_muscular' => 'pecho'],
            ['nombre' => 'Press de Pecho en Máquina (Chest Press Machine)', 'grupo_muscular' => 'pecho'],
            ['nombre' => 'Flexiones declinadas (Pies elevados)', 'grupo_muscular' => 'pecho'],
            ['nombre' => 'Landmine Press (Prensa con barra apoyada en suelo)', 'grupo_muscular' => 'pecho'],

            // Espalda (16)
            ['nombre' => 'Dominadas (Pronas/Supinas)', 'grupo_muscular' => 'espalda'],
            ['nombre' => 'Jalón al Pecho (Polea alta)', 'grupo_muscular' => 'espalda'],
            ['nombre' => 'Remo con Barra (Inclinado)', 'grupo_muscular' => 'espalda'],
            ['nombre' => 'Remo con Mancuerna (A una mano)', 'grupo_muscular' => 'espalda'],
            ['nombre' => 'Remo en Polea Baja (Grip en V / remo Gironda)', 'grupo_muscular' => 'espalda'],
            ['nombre' => 'Peso Muerto (Convencional)', 'grupo_muscular' => 'espalda'],
            ['nombre' => 'Hiperextensiones (En banco de 45º)', 'grupo_muscular' => 'espalda'],
            ['nombre' => 'Pull-over con Polea Alta (Brazos rectos)', 'grupo_muscular' => 'espalda'],
            ['nombre' => 'Remo Meadows (Remo unilateral con barra apoyada)', 'grupo_muscular' => 'espalda'],
            ['nombre' => 'Remo apoyado en banco (Seal Row / Incline Bench Row)', 'grupo_muscular' => 'espalda'],
            ['nombre' => 'Jalón al pecho con agarre supino (o agarre estrecho neutro)', 'grupo_muscular' => 'espalda'],
            ['nombre' => 'Remo en máquina T-Bar', 'grupo_muscular' => 'espalda'],
            ['nombre' => 'Pull-over con mancuerna (En banco plano)', 'grupo_muscular' => 'espalda'],
            ['nombre' => 'Dominadas con agarre neutro', 'grupo_muscular' => 'espalda'],
            ['nombre' => 'Rack Pulls (Peso muerto parcial desde soportes)', 'grupo_muscular' => 'espalda'],
            ['nombre' => 'Jalón unilateral en polea (Enfoque dorsal interno)', 'grupo_muscular' => 'espalda'],

            // Pierna (19)
            ['nombre' => 'Sentadillas Traseras (Squats clásicos)', 'grupo_muscular' => 'pierna'],
            ['nombre' => 'Sentadillas Frontales', 'grupo_muscular' => 'pierna'],
            ['nombre' => 'Prensa de Piernas (Prensa inclinada a 45º)', 'grupo_muscular' => 'pierna'],
            ['nombre' => 'Zancadas con Mancuernas (Desplantes)', 'grupo_muscular' => 'pierna'],
            ['nombre' => 'Extensión de Cuádriceps (En máquina)', 'grupo_muscular' => 'pierna'],
            ['nombre' => 'Curl Femoral Tumbado (En máquina)', 'grupo_muscular' => 'pierna'],
            ['nombre' => 'Peso Muerto Rumano (Para femorales y glúteos)', 'grupo_muscular' => 'pierna'],
            ['nombre' => 'Elevación de Gemelos de Pie', 'grupo_muscular' => 'pierna'],
            ['nombre' => 'Elevación de Gemelos Sentado', 'grupo_muscular' => 'pierna'],
            ['nombre' => 'Aductores en Máquina', 'grupo_muscular' => 'pierna'],
            ['nombre' => 'Sentadilla Búlgara (Split Squat con pie trasero elevado)', 'grupo_muscular' => 'pierna'],
            ['nombre' => 'Sentadilla Hack (En máquina)', 'grupo_muscular' => 'pierna'],
            ['nombre' => 'Hip Thrust (Empuje de cadera con barra/máquina)', 'grupo_muscular' => 'pierna'],
            ['nombre' => 'Curl Femoral Sentado (En máquina)', 'grupo_muscular' => 'pierna'],
            ['nombre' => 'Zancadas inversas (Reverse lunges)', 'grupo_muscular' => 'pierna'],
            ['nombre' => 'Sentadilla Goblet (Con mancuerna/kettlebell al pecho)', 'grupo_muscular' => 'pierna'],
            ['nombre' => 'Abductores en Máquina (Apertura exterior)', 'grupo_muscular' => 'pierna'],
            ['nombre' => 'Elevación de Gemelos tipo Prensa (Leg Press Calf Raise)', 'grupo_muscular' => 'pierna'],
            ['nombre' => 'Zancadas caminando (Walking Lunges)', 'grupo_muscular' => 'pierna'],

            // Hombro (13)
            ['nombre' => 'Press Militar (De pie con barra)', 'grupo_muscular' => 'hombro'],
            ['nombre' => 'Press Arnold (Giro con mancuernas)', 'grupo_muscular' => 'hombro'],
            ['nombre' => 'Elevaciones Laterales (Con mancuernas/polea)', 'grupo_muscular' => 'hombro'],
            ['nombre' => 'Pájaros (Hombro Posterior) (Inclinado para deltoide posterior)', 'grupo_muscular' => 'hombro'],
            ['nombre' => 'Elevaciones Frontales (Con mancuerna/disco)', 'grupo_muscular' => 'hombro'],
            ['nombre' => 'Encogimientos de Hombros (Para trapecios)', 'grupo_muscular' => 'hombro'],
            ['nombre' => 'Face Pulls (En polea alta con cuerda)', 'grupo_muscular' => 'hombro'],
            ['nombre' => 'Press de Hombros con Mancuernas (Sentado)', 'grupo_muscular' => 'hombro'],
            ['nombre' => 'Elevaciones Laterales en Polea (Detrás de la espalda)', 'grupo_muscular' => 'hombro'],
            ['nombre' => 'Remo al Mentón (Upright Row con barra o polea)', 'grupo_muscular' => 'hombro'],
            ['nombre' => 'Pájaros en máquina (Rear Delt Fly Machine)', 'grupo_muscular' => 'hombro'],
            ['nombre' => 'Press Overhead con Mancuernas (De pie)', 'grupo_muscular' => 'hombro'],
            ['nombre' => 'Elevaciones en Y (Y-Raisis inclinadas en banco)', 'grupo_muscular' => 'hombro'],

            // Brazo (18)
            ['nombre' => 'Curl de Bíceps con Barra (Barra recta o Z)', 'grupo_muscular' => 'brazo'],
            ['nombre' => 'Curl de Bíceps con Mancuernas (Supinación alterno)', 'grupo_muscular' => 'brazo'],
            ['nombre' => 'Curl Martillo (Agarre neutro)', 'grupo_muscular' => 'brazo'],
            ['nombre' => 'Curl Concentrado (Apoyo en muslo)', 'grupo_muscular' => 'brazo'],
            ['nombre' => 'Curl Predicador (Banco Scott)', 'grupo_muscular' => 'brazo'],
            ['nombre' => 'Press Francés (Rompecráneos con barra Z)', 'grupo_muscular' => 'brazo'],
            ['nombre' => 'Extensión de Tríceps en Polea (Con barra recta o cuerda)', 'grupo_muscular' => 'brazo'],
            ['nombre' => 'Fondos de Tríceps (En banco o paralelas con torso vertical)', 'grupo_muscular' => 'brazo'],
            ['nombre' => 'Copa de Tríceps con Mancuerna (A dos manos tras nuca)', 'grupo_muscular' => 'brazo'],
            ['nombre' => 'Patada de Tríceps (Con mancuerna)', 'grupo_muscular' => 'brazo'],
            ['nombre' => 'Curl de Bíceps en Polea Baja (Con barra recta o cuerda)', 'grupo_muscular' => 'brazo'],
            ['nombre' => 'Curl Inclinado con Mancuernas (En banco a 45º)', 'grupo_muscular' => 'brazo'],
            ['nombre' => 'Curl Araña (Spider Curl en banco inclinado)', 'grupo_muscular' => 'brazo'],
            ['nombre' => 'Curl Zottman (Subida supinada, bajada pronada)', 'grupo_muscular' => 'brazo'],
            ['nombre' => 'Extensión de Tríceps Tras Nuca en Polea (Con cuerda)', 'grupo_muscular' => 'brazo'],
            ['nombre' => 'Press California (Híbrido banca/rompecráneos)', 'grupo_muscular' => 'brazo'],
            ['nombre' => 'Extensión de Tríceps Unilateral en Polea (Invertido)', 'grupo_muscular' => 'brazo'],
            ['nombre' => 'Curl de Antebrazo con Barra (Supinación/Pronación)', 'grupo_muscular' => 'brazo'],

            // Core (15)
            ['nombre' => 'Plancha Abdominal (Estático sobre antebrazos)', 'grupo_muscular' => 'core'],
            ['nombre' => 'Crunch Abdominal (Encogimientos clásicos)', 'grupo_muscular' => 'core'],
            ['nombre' => 'Elevación de Piernas Colgado (En barra de dominadas)', 'grupo_muscular' => 'core'],
            ['nombre' => 'Rueda Abdominal (Rollout con rodillo)', 'grupo_muscular' => 'core'],
            ['nombre' => 'Giros Rusos (Russian twists con/sin carga)', 'grupo_muscular' => 'core'],
            ['nombre' => 'Crunch Oblicuo (Torsión alternada)', 'grupo_muscular' => 'core'],
            ['nombre' => 'Abdominales Tijeras (Flutter kicks)', 'grupo_muscular' => 'core'],
            ['nombre' => 'Lumbar Supermán (Extensiones lumbares en suelo)', 'grupo_muscular' => 'core'],
            ['nombre' => 'Plancha Lateral (Side plank estática/dinámica)', 'grupo_muscular' => 'core'],
            ['nombre' => 'Crunch en Polea Alta (Kneeling Cable Crunch)', 'grupo_muscular' => 'core'],
            ['nombre' => 'Elevaciones de piernas en banco inclinado (Dragon flags parciales)', 'grupo_muscular' => 'core'],
            ['nombre' => 'Leñador en Polea (Woodchoppers para oblicuos)', 'grupo_muscular' => 'core'],
            ['nombre' => 'Dead Bug (Bicho muerto - control lumbopélvico)', 'grupo_muscular' => 'core'],
            ['nombre' => 'Plancha con toques de hombro (Shoulder taps)', 'grupo_muscular' => 'core'],
            ['nombre' => 'Elevación de rodillas en paralelas (Captain\'s Chair)', 'grupo_muscular' => 'core'],
        ];

        foreach ($ejerciciosData as $ej) {
            \App\Models\Ejercicio::create($ej);
        }

        // =============================================
        // 3. CREAR 10+ ENTRENAMIENTOS (repartidos entre usuarios)
        // =============================================
        $ejPressBanca = \App\Models\Ejercicio::where('nombre', 'Press de Banca (Plano con barra)')->first();
        $ejSentadillas = \App\Models\Ejercicio::where('nombre', 'Sentadillas Traseras (Squats clásicos)')->first();
        $ejDominadas = \App\Models\Ejercicio::where('nombre', 'Dominadas (Pronas/Supinas)')->first();
        $ejRemoBarra = \App\Models\Ejercicio::where('nombre', 'Remo con Barra (Inclinado)')->first();
        $ejPressMilitar = \App\Models\Ejercicio::where('nombre', 'Press Militar (De pie con barra)')->first();
        $ejCurlBarra = \App\Models\Ejercicio::where('nombre', 'Curl de Bíceps con Barra (Barra recta o Z)')->first();

        // -- User 1: 3 entrenamientos --
        $entrenamiento1 = \App\Models\Entrenamiento::create([
            'usuario_id' => $user1->id,
            'tipo' => 'Fuerza',
            'fecha' => now()->subDay(),
            'duracion_minutos' => 60,
            'calorias_estimadas' => 390,
            'notas' => 'Sensación: 8/10 | Aprox: 390 kcal',
        ]);
        $entrenamiento1->ejercicios()->attach($ejPressBanca->id, [
            'series' => 4, 'repeticiones' => 10, 'carga_kg' => 60,
        ]);
        $entrenamiento1->ejercicios()->attach($ejSentadillas->id, [
            'series' => 4, 'repeticiones' => 12, 'carga_kg' => 80,
        ]);

        \App\Models\Entrenamiento::create([
            'usuario_id' => $user1->id,
            'tipo' => 'Carrera',
            'fecha' => now()->subDays(2),
            'duracion_minutos' => 45,
            'calorias_estimadas' => 495,
            'notas' => 'Distancia: 5km | Sensación: 8/10 | Aprox: 495 kcal',
        ]);

        \App\Models\Entrenamiento::create([
            'usuario_id' => $user1->id,
            'tipo' => 'Caminata',
            'fecha' => now()->subDays(4),
            'duracion_minutos' => 30,
            'calorias_estimadas' => 135,
            'notas' => 'Sensación: 6/10 | Aprox: 135 kcal',
        ]);

        // -- User 2: 3 entrenamientos --
        $ent4 = \App\Models\Entrenamiento::create([
            'usuario_id' => $user2->id,
            'tipo' => 'Fuerza',
            'fecha' => now()->subDays(1),
            'duracion_minutos' => 50,
            'calorias_estimadas' => 325,
            'notas' => 'Sensación: 7/10 | Aprox: 325 kcal',
        ]);
        $ent4->ejercicios()->attach($ejDominadas->id, [
            'series' => 3, 'repeticiones' => 10, 'carga_kg' => 0,
        ]);
        $ent4->ejercicios()->attach($ejRemoBarra->id, [
            'series' => 4, 'repeticiones' => 8, 'carga_kg' => 50,
        ]);

        \App\Models\Entrenamiento::create([
            'usuario_id' => $user2->id,
            'tipo' => 'Carrera',
            'fecha' => now()->subDays(3),
            'duracion_minutos' => 35,
            'calorias_estimadas' => 385,
            'notas' => 'Distancia: 4km | Sensación: 7/10 | Aprox: 385 kcal',
        ]);

        \App\Models\Entrenamiento::create([
            'usuario_id' => $user2->id,
            'tipo' => 'Caminata',
            'fecha' => now()->subDays(5),
            'duracion_minutos' => 40,
            'calorias_estimadas' => 180,
            'notas' => 'Sensación: 5/10 | Aprox: 180 kcal',
        ]);

        // -- User 3: 2 entrenamientos --
        $ent7 = \App\Models\Entrenamiento::create([
            'usuario_id' => $user3->id,
            'tipo' => 'Fuerza',
            'fecha' => now()->subDays(2),
            'duracion_minutos' => 70,
            'calorias_estimadas' => 455,
            'notas' => 'Sensación: 9/10 | Aprox: 455 kcal',
        ]);
        $ent7->ejercicios()->attach($ejPressMilitar->id, [
            'series' => 4, 'repeticiones' => 10, 'carga_kg' => 40,
        ]);

        \App\Models\Entrenamiento::create([
            'usuario_id' => $user3->id,
            'tipo' => 'Carrera',
            'fecha' => now()->subDays(6),
            'duracion_minutos' => 55,
            'calorias_estimadas' => 605,
            'notas' => 'Distancia: 8km | Sensación: 9/10 | Aprox: 605 kcal',
        ]);

        // -- User 4: 2 entrenamientos --
        \App\Models\Entrenamiento::create([
            'usuario_id' => $user4->id,
            'tipo' => 'Caminata',
            'fecha' => now()->subDays(1),
            'duracion_minutos' => 60,
            'calorias_estimadas' => 270,
            'notas' => 'Sensación: 7/10 | Aprox: 270 kcal',
        ]);

        $ent10 = \App\Models\Entrenamiento::create([
            'usuario_id' => $user4->id,
            'tipo' => 'Fuerza',
            'fecha' => now()->subDays(3),
            'duracion_minutos' => 45,
            'calorias_estimadas' => 293,
            'notas' => 'Sensación: 6/10 | Aprox: 293 kcal',
        ]);
        $ent10->ejercicios()->attach($ejCurlBarra->id, [
            'series' => 3, 'repeticiones' => 12, 'carga_kg' => 15,
        ]);

        // -- User 5: 2 entrenamientos --
        \App\Models\Entrenamiento::create([
            'usuario_id' => $user5->id,
            'tipo' => 'Carrera',
            'fecha' => now()->subDays(1),
            'duracion_minutos' => 25,
            'calorias_estimadas' => 275,
            'notas' => 'Distancia: 3km | Sensación: 6/10 | Aprox: 275 kcal',
        ]);

        \App\Models\Entrenamiento::create([
            'usuario_id' => $user5->id,
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
            'usuario_id' => $user1->id,
            'tipo_objetivo' => 'Días Entrenados',
            'valor_objetivo' => 4,
            'estado' => 'en_progreso',
            'fecha_inicio' => now(),
            'fecha_limite' => now()->addDays(30),
        ]);

        \App\Models\Objetivo::create([
            'usuario_id' => $user1->id,
            'tipo_objetivo' => 'Peso Corporal',
            'valor_objetivo' => 70,
            'estado' => 'en_progreso',
            'fecha_inicio' => now(),
            'fecha_limite' => now()->addDays(60),
        ]);

        \App\Models\Objetivo::create([
            'usuario_id' => $user2->id,
            'tipo_objetivo' => 'Volumen (kg levantados)',
            'valor_objetivo' => 500,
            'estado' => 'en_progreso',
            'fecha_inicio' => now(),
            'fecha_limite' => now()->addDays(30),
        ]);

        \App\Models\Objetivo::create([
            'usuario_id' => $user3->id,
            'tipo_objetivo' => 'Días Entrenados',
            'valor_objetivo' => 10,
            'estado' => 'en_progreso',
            'fecha_inicio' => now(),
            'fecha_limite' => now()->addDays(45),
        ]);

        \App\Models\Objetivo::create([
            'usuario_id' => $user4->id,
            'tipo_objetivo' => 'Peso Corporal',
            'valor_objetivo' => 65,
            'estado' => 'en_progreso',
            'fecha_inicio' => now(),
            'fecha_limite' => now()->addDays(90),
        ]);

        // =============================================
        // 5. ASIGNAR LOGROS A USUARIOS SEMBRADOS
        // =============================================
        $usuariosModel = \App\Models\Usuario::all();
        $logrosModel = \App\Models\Logro::all();
        
        foreach ($usuariosModel as $u) {
            $numEntrenamientos = $u->entrenamientos()->count();
            if ($numEntrenamientos >= 1) {
                $l = $logrosModel->where('criterio', \App\Models\Logro::CRITERIO_PRIMER_ENTRENO)->first();
                if ($l) $u->logros()->syncWithoutDetaching([$l->id]);
            }
            
            $numFuerza = $u->entrenamientos()->where('tipo', 'Fuerza')->count();
            if ($numFuerza >= 5) {
                $l = $logrosModel->where('criterio', \App\Models\Logro::CRITERIO_5_SESIONES_FUERZA)->first();
                if ($l) $u->logros()->syncWithoutDetaching([$l->id]);
            }
            
            $minutosTotales = $u->entrenamientos()->sum('duracion_minutos');
            if ($minutosTotales >= 1000) {
                $l = $logrosModel->where('criterio', \App\Models\Logro::CRITERIO_1000_MINUTOS)->first();
                if ($l) $u->logros()->syncWithoutDetaching([$l->id]);
            }
            
            $racha = $u->calcularRacha();
            if ($racha >= 3) {
                $l = $logrosModel->where('criterio', \App\Models\Logro::CRITERIO_RACHA_3_DIAS)->first();
                if ($l) $u->logros()->syncWithoutDetaching([$l->id]);
            }
        }
    }
}
