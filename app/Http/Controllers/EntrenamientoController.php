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

        $actividades = \App\Models\Entrenamiento::with(['detalles', 'detalles.ejercicio'])
                        ->where('usuario_id', $usuario_id)
                        ->orderBy('fecha', 'desc')
                        ->limit(5)
                        ->get();

        $actividades_array = json_decode(json_encode($actividades), true);

        return view('inicio', [
            'actividades' => $actividades_array
        ]);
    }

    public function historial(Request $request)
    {
        $usuario_id = Auth::id(); 
        $query = \App\Models\Entrenamiento::with(['detalles', 'detalles.ejercicio'])
                        ->where('usuario_id', $usuario_id);

        // #4: Filtro por tipo
        if ($request->filled('tipo') && in_array($request->tipo, ['Fuerza', 'Carrera', 'Caminata'])) {
            $query->where('tipo', $request->tipo);
        }

        $actividades = $query->orderBy('fecha', 'desc')->paginate(20);

        $coleccion_transformada = json_decode(json_encode($actividades->items()), true);
        $actividades->setCollection(collect($coleccion_transformada));

        return view('entrenamientos', [
            'actividades' => $actividades
        ]);
    }

    /**
     * #28: Exportar historial a CSV
     */
    public function exportCSV()
    {
        $usuario_id = Auth::id();
        $entrenamientos = \App\Models\Entrenamiento::where('usuario_id', $usuario_id)
            ->orderBy('fecha', 'desc')
            ->get();

        $csv = "Fecha,Tipo,Duración (min),Notas\n";
        foreach ($entrenamientos as $e) {
            $notas = str_replace('"', '""', $e->notas ?? '');
            $csv .= "{$e->fecha},{$e->tipo},{$e->duracion_minutos},\"{$notas}\"\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="historial_sinergyfit.csv"');
    }
    public function store(Request $request)
    {
        // 1. VALIDACIÓN 
        $request->validate([
            'fecha' => 'required|date',
            'modulo' => 'required|string',
            'tiempo' => 'required|numeric|min:1',
        ]);

        // 2. LÓGICA DE CALORÍAS 
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

        // 3. GUARDAR EN BASE DE DATOS
        $notas = "Sensación: " . $sensacion . "/10";
        if ($distancia > 0) $notas .= " | Distancia: {$distancia}km";
        if ($calorias_calculadas > 0) $notas .= " | Aprox: " . round($calorias_calculadas) . " kcal";

        $entrenamiento = \App\Models\Entrenamiento::create([
            'usuario_id' => Auth::id(),
            'fecha' => $request->fecha,
            'tipo' => $tipo_db,
            'duracion_minutos' => $duracion,
            'calorias_estimadas' => $calorias_calculadas > 0 ? round($calorias_calculadas) : null,
            'notas' => $notas
        ]);
        $entrenamiento_id = $entrenamiento->id;

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
                    // Mapear el string a ejercicio_id
                    $ej_model = \App\Models\Ejercicio::firstOrCreate(
                        ['nombre' => $ejercicios[$index]],
                        ['grupo_muscular' => $grupo]
                    );

                    $detalles[] = [
                        'entrenamiento_id' => $entrenamiento_id,
                        'grupo_muscular' => $grupo,
                        'ejercicio_id' => $ej_model->id,
                        'series' => empty($series[$index]) ? null : intval($series[$index]),
                        'repeticiones' => empty($reps[$index]) ? null : intval($reps[$index]),
                        'carga_kg' => empty($cargas[$index]) ? null : floatval($cargas[$index]),
                        'creado_en' => now(),
                        'actualizado_en' => now(),
                    ];
                }
            }
            
            if (!empty($detalles)) {
                \App\Models\EntrenamientoDetalle::insert($detalles);
            }
        }

        // --- 3.5. GESTIÓN DE RACHAS (STREAKS) ---
        $usuario = \App\Models\Usuario::find(Auth::id());
        $fecha_entreno = \Carbon\Carbon::parse($request->fecha)->startOfDay();
        
        if ($usuario->ultima_actividad_fecha) {
            $ultima = \Carbon\Carbon::parse($usuario->ultima_actividad_fecha)->startOfDay();
            $diferencia_dias = $ultima->diffInDays($fecha_entreno, false); 
            
            if ($diferencia_dias == 1) {
                // Entrenó justo al día siguiente
                $usuario->racha_actual += 1;
                $usuario->ultima_actividad_fecha = $fecha_entreno->toDateString();
            } elseif ($diferencia_dias > 1) {
                // Rompió la racha
                $usuario->racha_actual = 1;
                $usuario->ultima_actividad_fecha = $fecha_entreno->toDateString();
            }
            // Si diff == 0, entrena 2 veces el mismo día, no sube la racha ni cambia la fecha (ya es hoy)
        } else {
            // Su primer registro absoluto
            $usuario->racha_actual = 1;
            $usuario->ultima_actividad_fecha = $fecha_entreno->toDateString();
        }
        
        // Récord histórico
        if ($usuario->racha_actual > $usuario->mejor_racha) {
            $usuario->mejor_racha = $usuario->racha_actual;
        }
        $usuario->save();

        // 4. GAMIFICACIÓN: Otorgar logros pasivamente
        
        // Track newly unlocked logros for confetti celebration
        $logros_nuevos = [];

        // 4.1 Primer Entrenamiento
        if (\App\Models\Entrenamiento::where('usuario_id', $usuario->id)->count() === 1) {
            $logro = \App\Models\Logro::where('criterio', \App\Models\Logro::CRITERIO_PRIMER_ENTRENO)->first();
            if ($logro && !$usuario->logros->contains($logro->id)) {
                $usuario->logros()->attach($logro->id);
                $logros_nuevos[] = $logro->nombre;
            }
        }

        // 4.2 Levantador NATO (5 Sesiones de Fuerza)
        if ($tipo_db === 'Fuerza') {
            $fuerzaCount = \App\Models\Entrenamiento::where('usuario_id', $usuario->id)->where('tipo', 'Fuerza')->count();
            if ($fuerzaCount >= 5) {
                $logro = \App\Models\Logro::where('criterio', \App\Models\Logro::CRITERIO_5_SESIONES_FUERZA)->first();
                if ($logro && !$usuario->logros->contains($logro->id)) {
                    $usuario->logros()->attach($logro->id);
                    $logros_nuevos[] = $logro->nombre;
                }
            }
        }
        // 4.3 Maratonista (Carrera >= 10km)
        if ($tipo_db === 'Carrera' && $distancia >= 10) {
            $logro = \App\Models\Logro::where('criterio', \App\Models\Logro::CRITERIO_CARRERA_10KM)->first();
            if ($logro && !$usuario->logros->contains($logro->id)) {
                $usuario->logros()->attach($logro->id);
                $logros_nuevos[] = $logro->nombre;
            }
        }

        // 4.4 Leyenda del Sudor (Más de 1000 minutos totales)
        $minutosTotales = \App\Models\Entrenamiento::where('usuario_id', $usuario->id)->sum('duracion_minutos');
        
        if ($minutosTotales >= 1000) {
            $logro = \App\Models\Logro::where('criterio', \App\Models\Logro::CRITERIO_1000_MINUTOS)->first();
            if ($logro && !$usuario->logros->contains($logro->id)) {
                $usuario->logros()->attach($logro->id);
                $logros_nuevos[] = $logro->nombre;
            }
        }

        // 4.5 Constancia Pura (Racha de 3 días)
        $racha = $usuario->calcularRacha();
        if ($racha >= 3) {
            $logro = \App\Models\Logro::where('criterio', \App\Models\Logro::CRITERIO_RACHA_3_DIAS)->first();
            if ($logro && !$usuario->logros->contains($logro->id)) {
                $usuario->logros()->attach($logro->id);
                $logros_nuevos[] = $logro->nombre;
            }
        }

        // 5. REDIRECCIÓN ELEGANTE
        if (!empty($logros_nuevos)) {
            return redirect('/')->with('msg', '¡Actividad registrada con éxito!')->with('logros_nuevos', $logros_nuevos);
        }
        return redirect('/')->with('msg', '¡Actividad registrada con éxito!');
    }
    // 1. Mostrar el formulario de edición
    public function edit($id)
    {
        $usuario_id = Auth::id();
        $entreno = \App\Models\Entrenamiento::with(['detalles.ejercicio'])->where('id', $id)->where('usuario_id', $usuario_id)->first();

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

        $notas = "Sensación: " . $sensacion . "/10";
        if ($distancia > 0) $notas .= " | Distancia: {$distancia}km";
        if ($calorias > 0) $notas .= " | Aprox: " . round($calorias) . " kcal";

        \App\Models\Entrenamiento::where('id', $id)
            ->where('usuario_id', Auth::id()) // Seguridad: solo el dueño puede editar
            ->update([
                'fecha' => $request->fecha,
                'tipo' => $tipo_db,
                'duracion_minutos' => $duracion,
                'calorias_estimadas' => $calorias > 0 ? round($calorias) : null,
                'notas' => $notas
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
                    $ej_model = \App\Models\Ejercicio::firstOrCreate(
                        ['nombre' => $ejercicios[$index]],
                        ['grupo_muscular' => $grupo]
                    );

                    $detalles[] = [
                        'entrenamiento_id' => $id,
                        'grupo_muscular' => $grupo,
                        'ejercicio_id' => $ej_model->id,
                        'series' => empty($series[$index]) ? null : intval($series[$index]),
                        'repeticiones' => empty($reps[$index]) ? null : intval($reps[$index]),
                        'carga_kg' => empty($cargas[$index]) ? null : floatval($cargas[$index]),
                        'creado_en' => now(),
                        'actualizado_en' => now(),
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