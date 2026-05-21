<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Entrenamiento;
use App\Models\Ejercicio;
use Carbon\Carbon;

class CienEntrenamientosSeeder extends Seeder
{
    public function run(): void
    {
        $user = Usuario::whereRaw('LOWER(correo) = ?', ['juanfranciscocort@gmail.com'])->first();

        if (!$user) {
            $this->command->error('Usuario no encontrado: juanfranciscocort@gmail.com');
            return;
        }

        $this->command->info("Usuario encontrado: {$user->correo} (ID: {$user->id})");

        // Asegurar ejercicios básicos para sesiones de fuerza
        $ejercicios = [
            Ejercicio::firstOrCreate(['nombre' => 'Press de Banca'],    ['grupo_muscular' => 'Pecho']),
            Ejercicio::firstOrCreate(['nombre' => 'Sentadillas'],        ['grupo_muscular' => 'Piernas']),
            Ejercicio::firstOrCreate(['nombre' => 'Dominadas'],          ['grupo_muscular' => 'Espalda']),
            Ejercicio::firstOrCreate(['nombre' => 'Peso Muerto'],        ['grupo_muscular' => 'Piernas']),
            Ejercicio::firstOrCreate(['nombre' => 'Press Militar'],      ['grupo_muscular' => 'Hombros']),
            Ejercicio::firstOrCreate(['nombre' => 'Curl de Bíceps'],     ['grupo_muscular' => 'Bíceps']),
            Ejercicio::firstOrCreate(['nombre' => 'Extensión de Tríceps'], ['grupo_muscular' => 'Tríceps']),
            Ejercicio::firstOrCreate(['nombre' => 'Remo con Barra'],     ['grupo_muscular' => 'Espalda']),
            Ejercicio::firstOrCreate(['nombre' => 'Hip Thrust'],         ['grupo_muscular' => 'Glúteos']),
            Ejercicio::firstOrCreate(['nombre' => 'Fondos en Paralelas'], ['grupo_muscular' => 'Tríceps']),
        ];

        // 100 tipos: 50 Fuerza, 35 Caminata, 15 Carrera
        $tipos = array_merge(
            array_fill(0, 50, 'Fuerza'),
            array_fill(0, 35, 'Caminata'),
            array_fill(0, 15, 'Carrera')
        );
        shuffle($tipos);

        // Generar las 100 fechas
        $fechas = $this->generarFechas();

        $creados = 0;
        foreach ($fechas as $i => $fecha) {
            $tipo = $tipos[$i];
            $duracion = rand(25, 90);

            [$calorias, $notas] = $this->calcularCaloriasYNotas($tipo, $duracion);

            $entrenamiento = Entrenamiento::create([
                'usuario_id'         => $user->id,
                'tipo'               => $tipo,
                'fecha'              => $fecha->toDateString(),
                'duracion_minutos'   => $duracion,
                'calorias_estimadas' => $calorias,
                'notas'              => $notas,
            ]);

            if ($tipo === 'Fuerza') {
                $numEj = rand(2, 4);
                $keys = array_rand($ejercicios, $numEj);
                foreach ((array) $keys as $k) {
                    $ej = $ejercicios[$k];
                    $entrenamiento->ejercicios()->attach($ej->id, [
                        'grupo_muscular' => $ej->grupo_muscular,
                        'series'         => rand(3, 5),
                        'repeticiones'   => rand(6, 15),
                        'carga_kg'       => rand(20, 120),
                    ]);
                }
            }

            $creados++;
        }

        $this->command->info("¡{$creados} entrenamientos creados para {$user->correo}!");
    }

    private function generarFechas(): array
    {
        $fechas = [];

        // 20 entrenamientos: 2026-05-01 → 2026-05-22 (mañana)
        $inicio = Carbon::create(2026, 5, 1);
        $fin    = Carbon::create(2026, 5, 22);
        $rangoReciente = $fin->diffInDays($inicio); // 21 días
        for ($i = 0; $i < 20; $i++) {
            $fechas[] = $inicio->copy()->addDays((int) round($i * $rangoReciente / 19));
        }

        // 80 entrenamientos: julio 2025 → abril 2026 (10 meses, 8 por mes)
        $meses = [];
        for ($m = 0; $m < 10; $m++) {
            // mes 0 = julio 2025, mes 9 = abril 2026
            $meses[] = Carbon::create(2025, 7, 1)->addMonths($m);
        }

        foreach ($meses as $mesInicio) {
            $diasEnMes = $mesInicio->daysInMonth;
            // Repartir los 8 días equitativamente dentro del mes
            for ($j = 0; $j < 8; $j++) {
                $dia = (int) round(1 + $j * ($diasEnMes - 1) / 7);
                $fechas[] = $mesInicio->copy()->setDay($dia);
            }
        }

        // Mezclar para que los tipos queden distribuidos sin sesgo de fecha
        shuffle($fechas);

        return $fechas;
    }

    private function calcularCaloriasYNotas(string $tipo, int $duracion): array
    {
        $sensacion = rand(5, 10);

        if ($tipo === 'Carrera') {
            $calorias  = (int) round($duracion * 11);
            $distancia = round($duracion / 6, 1);
            $notas = "Sensación: {$sensacion}/10 | Distancia: {$distancia}km | Aprox: {$calorias} kcal";
        } elseif ($tipo === 'Caminata') {
            $calorias  = (int) round($duracion * 4.5);
            $distancia = round($duracion / 12, 1);
            $notas = "Sensación: {$sensacion}/10 | Distancia: {$distancia}km | Aprox: {$calorias} kcal";
        } else {
            $calorias = (int) round($duracion * 6.5);
            $notas = "Sensación: {$sensacion}/10 | Aprox: {$calorias} kcal";
        }

        return [$calorias, $notas];
    }
}
