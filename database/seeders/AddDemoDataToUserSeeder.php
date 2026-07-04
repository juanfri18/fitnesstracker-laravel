<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Entrenamiento;
use App\Models\Ejercicio;
use Carbon\Carbon;

class AddDemoDataToUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = Usuario::firstOrCreate(
            ['correo' => 'Juanfranciscocort@gmail.com'],
            [
                'nombre' => 'Juanfrancisco',
                'contrasena' => bcrypt('12345678'),
            ]
        );

        // Crear o actualizar perfil
        \App\Models\PerfilUsuario::updateOrCreate(
            ['usuario_id' => $user->id],
            [
                'apellidos' => 'Cortejosa',
                'edad' => 22,
                'altura' => 171,
                'genero' => 'Hombre',
                'nivel_actividad' => 'Moderado',
            ]
        );

        // Crear racha si no existe
        \App\Models\Racha::firstOrCreate(['usuario_id' => $user->id]);

        // Crear métrica con el peso
        \App\Models\Metrica::firstOrCreate(
            ['usuario_id' => $user->id, 'fecha_registro' => now()->toDateString()],
            ['peso' => 72.5, 'altura' => 171]
        );

        // Asegurar que existan algunos ejercicios básicos
        $ej1 = Ejercicio::firstOrCreate(['nombre' => 'Press de Banca (Plano con barra)'], ['grupo_muscular' => 'pecho']);
        $ej2 = Ejercicio::firstOrCreate(['nombre' => 'Sentadillas Traseras (Squats clásicos)'], ['grupo_muscular' => 'pierna']);
        $ej3 = Ejercicio::firstOrCreate(['nombre' => 'Dominadas (Pronas/Supinas)'], ['grupo_muscular' => 'espalda']);
        $ej4 = Ejercicio::firstOrCreate(['nombre' => 'Peso Muerto (Convencional)'], ['grupo_muscular' => 'espalda']);
        $ej5 = Ejercicio::firstOrCreate(['nombre' => 'Press Militar (De pie con barra)'], ['grupo_muscular' => 'hombro']);

        $tipos = ['Fuerza', 'Carrera', 'Caminata'];
        
        $ejerciciosDisponibles = [$ej1, $ej2, $ej3, $ej4, $ej5];

        echo "Añadiendo entrenamientos...\n";
        
        // Generar 30 entrenamientos en los últimos 45 días
        for ($i = 0; $i < 30; $i++) {
            $tipo = $tipos[array_rand($tipos)];
            $fecha = Carbon::now()->subDays(rand(0, 45));
            $duracion = rand(20, 90);
            
            $calorias = 0;
            $distancia = 0;
            if ($tipo === 'Carrera') {
                $calorias = $duracion * 11;
                $distancia = round($duracion / 6, 1); // aprox
            } elseif ($tipo === 'Caminata') {
                $calorias = $duracion * 4.5;
                $distancia = round($duracion / 12, 1);
            } elseif ($tipo === 'Fuerza') {
                $calorias = $duracion * 6.5;
            }
            
            $sensacion = rand(5, 10);
            $notas = "Sensación: " . $sensacion . "/10";
            if ($distancia > 0) $notes = $notas .= " | Distancia: {$distancia}km";
            if ($calorias > 0) $notas .= " | Aprox: " . round($calorias) . " kcal";

            $entrenamiento = Entrenamiento::create([
                'usuario_id' => $user->id,
                'tipo' => $tipo,
                'fecha' => $fecha,
                'duracion_minutos' => $duracion,
                'calorias_estimadas' => $calorias,
                'notas' => $notas,
            ]);

            // Si es de fuerza, le añadimos detalles (2-3 ejercicios)
            if ($tipo === 'Fuerza') {
                $numEjercicios = rand(2, 3);
                $ejerciciosSeleccionados = (array) array_rand($ejerciciosDisponibles, $numEjercicios);
                
                foreach ($ejerciciosSeleccionados as $idx) {
                    $ej = $ejerciciosDisponibles[$idx];
                    $entrenamiento->ejercicios()->attach($ej->id, [
                        'series' => rand(3, 4),
                        'repeticiones' => rand(8, 12),
                        'carga_kg' => rand(20, 100),
                    ]);
                }
            }
        }
        
        // =============================================
        // ASIGNAR LOGROS AL USUARIO DEMO
        // =============================================
        $logrosModel = \App\Models\Logro::all();
        $numEntrenamientos = $user->entrenamientos()->count();
        if ($numEntrenamientos >= 1) {
            $l = $logrosModel->where('criterio', \App\Models\Logro::CRITERIO_PRIMER_ENTRENO)->first();
            if ($l) $user->logros()->syncWithoutDetaching([$l->id]);
        }
        
        $numFuerza = $user->entrenamientos()->where('tipo', 'Fuerza')->count();
        if ($numFuerza >= 5) {
            $l = $logrosModel->where('criterio', \App\Models\Logro::CRITERIO_5_SESIONES_FUERZA)->first();
            if ($l) $user->logros()->syncWithoutDetaching([$l->id]);
        }
        
        $minutosTotales = $user->entrenamientos()->sum('duracion_minutos');
        if ($minutosTotales >= 1000) {
            $l = $logrosModel->where('criterio', \App\Models\Logro::CRITERIO_1000_MINUTOS)->first();
            if ($l) $user->logros()->syncWithoutDetaching([$l->id]);
        }
        
        $racha = $user->calcularRacha();
        if ($racha >= 3) {
            $l = $logrosModel->where('criterio', \App\Models\Logro::CRITERIO_RACHA_3_DIAS)->first();
            if ($l) $user->logros()->syncWithoutDetaching([$l->id]);
        }

        echo "¡30 entrenamientos añadidos a {$user->correo}!\n";
    }
}
