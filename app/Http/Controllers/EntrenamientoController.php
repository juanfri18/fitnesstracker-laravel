<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Herramienta para hablar con la Base de Datos
use Illuminate\Support\Facades\Auth;

class EntrenamientoController extends Controller
{
    public function index()
    {
        $usuario_id = Auth::id(); 

        $actividades = \App\Models\Entrenamiento::where('usuario_id', $usuario_id)
                        ->orderBy('fecha', 'desc')
                        ->limit(5)
                        ->get();

        $actividades_array = json_decode(json_encode($actividades), true);

        return view('inicio', [
            'actividades' => $actividades_array
        ]);
    }
    public function store(Request $request)
    {
        // 1. VALIDACIÓN (Tarea FT-52): Laravel comprueba automáticamente que no vengan vacíos
        $request->validate([
            'fecha' => 'required|date',
            'modulo' => 'required|string',
        ]);

        // 2. LÓGICA DE CALORÍAS (Reciclada exactamente de tu PHP nativo)
        $tipo_db = 'Fuerza';
        if ($request->modulo === 'carrera') $tipo_db = 'Carrera';
        if ($request->modulo === 'caminata') $tipo_db = 'Caminata';

        $duracion = $request->filled('tiempo') ? floatval($request->tiempo) : 0;
        $sensacion = $request->filled('sensacion') ? intval($request->sensacion) : 5;
        $distancia = $request->filled('distancia') ? floatval($request->distancia) : 0;

        $calorias_calculadas = 0;
        if ($tipo_db === 'Carrera') {
            $calorias_calculadas = $duracion * 11; 
        } elseif ($tipo_db === 'Caminata') {
            $calorias_calculadas = $duracion * 4.5;
        } elseif ($tipo_db === 'Fuerza') {
            $calorias_calculadas = $duracion * 6.5;
        }

        // 3. GUARDAR EN BASE DE DATOS (Usando insertGetId para obtener el ID de la sesión)
        $entrenamiento_id = \App\Models\Entrenamiento::insertGetId([
            'usuario_id' => Auth::id(),
            'fecha' => $request->fecha,
            'tipo' => $tipo_db,
            'duracion_minutos' => $duracion,
            'sensacion' => $sensacion,
            'distancia_km' => $distancia,
            'calorias' => round($calorias_calculadas)
        ]);

        // 3.5 GUARDAR DETALLES DE MÚLTIPLES EJERCICIOS (Si es de Fuerza)
        if ($tipo_db === 'Fuerza' && $request->has('grupo_muscular')) {
            $grupos = $request->grupo_muscular;
            $ejercicios = $request->ejercicio;
            $series = $request->series;
            $reps = $request->reps;
            $cargas = $request->carga;

            $detalles = [];
            foreach ($grupos as $index => $grupo) {
                // Solo guardamos si realmente seleccionó un grupo y un ejercicio
                if (!empty($grupo) && !empty($ejercicios[$index])) {
                    $detalles[] = [
                        'entrenamiento_id' => $entrenamiento_id,
                        'grupo_muscular' => $grupo,
                        'ejercicio' => $ejercicios[$index],
                        'series' => empty($series[$index]) ? null : intval($series[$index]),
                        'repeticiones' => empty($reps[$index]) ? null : intval($reps[$index]),
                        'carga_kg' => empty($cargas[$index]) ? null : floatval($cargas[$index]),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            if (!empty($detalles)) {
                \App\Models\EntrenamientoDetalle::insert($detalles);
            }
        }

        // 4. REDIRECCIÓN ELEGANTE
        // Esto sustituye a tu antiguo: header("Location: index.php?msg=guardado");
        return redirect('/')->with('msg', '¡Actividad registrada con éxito!');
    }
    // 1. Mostrar el formulario de edición
    public function edit($id)
    {
        $usuario_id = Auth::id();
        $entreno = \App\Models\Entrenamiento::where('id', $id)->where('usuario_id', $usuario_id)->first();

        if (!$entreno) {
            return redirect('/');
        }

        return view('editar', ['entreno' => $entreno->toArray()]);
    }

    // 2. Guardar los cambios en la BD
    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha' => 'required|date',
            'modulo' => 'required|string',
        ]);

        $tipo_db = 'Fuerza';
        if ($request->modulo === 'carrera') $tipo_db = 'Carrera';
        if ($request->modulo === 'caminata') $tipo_db = 'Caminata';

        $duracion = $request->filled('tiempo') ? floatval($request->tiempo) : 0;
        $sensacion = $request->filled('sensacion') ? intval($request->sensacion) : 5;
        $distancia = $request->filled('distancia') ? floatval($request->distancia) : 0;

        $calorias = 0;
        if ($tipo_db === 'Carrera') $calorias = $duracion * 11; 
        elseif ($tipo_db === 'Caminata') $calorias = $duracion * 4.5;
        elseif ($tipo_db === 'Fuerza') $calorias = $duracion * 6.5;

        \App\Models\Entrenamiento::where('id', $id)
            ->where('usuario_id', Auth::id()) // Seguridad: solo el dueño puede editar
            ->update([
                'fecha' => $request->fecha,
                'tipo' => $tipo_db,
                'duracion_minutos' => $duracion,
                'sensacion' => $sensacion,
                'distancia_km' => $distancia,
                'calorias' => round($calorias)
            ]);

        // ACTUALIZAR DETALLES: Solo si se envían nuevos, o si se cambió el tipo de entrenamiento
        if ($tipo_db !== 'Fuerza') {
            \App\Models\EntrenamientoDetalle::where('entrenamiento_id', $id)->delete();
        } elseif ($request->has('grupo_muscular')) {
            \App\Models\EntrenamientoDetalle::where('entrenamiento_id', $id)->delete();
            $grupos = $request->grupo_muscular;
            $ejercicios = $request->ejercicio;
            $series = $request->series;
            $reps = $request->reps;
            $cargas = $request->carga;

            $detalles = [];
            foreach ($grupos as $index => $grupo) {
                if (!empty($grupo) && !empty($ejercicios[$index])) {
                    $detalles[] = [
                        'entrenamiento_id' => $id,
                        'grupo_muscular' => $grupo,
                        'ejercicio' => $ejercicios[$index],
                        'series' => empty($series[$index]) ? null : intval($series[$index]),
                        'repeticiones' => empty($reps[$index]) ? null : intval($reps[$index]),
                        'carga_kg' => empty($cargas[$index]) ? null : floatval($cargas[$index]),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            if (!empty($detalles)) {
                \App\Models\EntrenamientoDetalle::insert($detalles);
            }
        }

        return redirect('/')->with('msg', '¡Actividad actualizada correctamente!');
    }

    // 3. Eliminar de la BD
    public function destroy($id)
    {
        \App\Models\Entrenamiento::where('id', $id)
            ->where('usuario_id', Auth::id()) // Seguridad: solo el dueño puede borrar
            ->delete();

        return redirect('/')->with('msg', 'Entrenamiento eliminado.');
    }

    // 4. Mostrar Vista del Calendario
    public function calendario()
    {
        return view('calendario');
    }

    // 5. Devolver JSON estructurado para FullCalendar.js
    public function eventosAPI()
    {
        $usuario_id = Auth::id();
        $entrenamientos = \App\Models\Entrenamiento::where('usuario_id', $usuario_id)
                            ->get();

        $eventos = [];
        foreach ($entrenamientos as $entreno) {
            // Asignar colores por tipo
            $color = '#0d6efd'; // Azul por defecto
            if ($entreno->tipo === 'Fuerza') $color = '#dc3545'; // Rojo
            if ($entreno->tipo === 'Carrera') $color = '#198754'; // Verde
            if ($entreno->tipo === 'Caminata') $color = '#ffc107'; // Amarillo

            $eventos[] = [
                'title' => $entreno->tipo . ' (' . $entreno->duracion_minutos . 'm)',
                'start' => $entreno->fecha,
                'url' => url('/entrenamientos/' . $entreno->id . '/edit'),
                'color' => $color,
            ];
        }

        return response()->json($eventos);
    }
}