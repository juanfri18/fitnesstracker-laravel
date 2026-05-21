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
                'apellidos' => 'Cortejosa',
                'contrasena' => bcrypt('12345678'),
                'edad' => 22,
                'altura' => 171,
                'peso' => 72.5,
                'nivel_actividad' => 'Moderado',
                'genero' => 'Hombre',
            ]
        );

        // Asegurar que existan algunos ejercicios básicos
        $ej1 = Ejercicio::firstOrCreate(['nombre' => 'Press de Banca'], ['grupo_muscular' => 'Pecho']);
        $ej2 = Ejercicio::firstOrCreate(['nombre' => 'Sentadillas'], ['grupo_muscular' => 'Piernas']);
        $ej3 = Ejercicio::firstOrCreate(['nombre' => 'Dominadas'], ['grupo_muscular' => 'Espalda']);
        $ej4 = Ejercicio::firstOrCreate(['nombre' => 'Peso Muerto'], ['grupo_muscular' => 'Piernas']);
        $ej5 = Ejercicio::firstOrCreate(['nombre' => 'Press Militar'], ['grupo_muscular' => 'Hombros']);

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
                        'grupo_muscular' => $ej->grupo_muscular,
                        'series' => rand(3, 4),
                        'repeticiones' => rand(8, 12),
                        'carga_kg' => rand(20, 100),
                    ]);
                }
            }
        }
        
        echo "¡30 entrenamientos añadidos a {$user->correo}!\n";
    }
}
