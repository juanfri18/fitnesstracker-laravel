<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ObjetivoController extends Controller
{
    /**
     * Display a listing of the resource.
     * Calcula el progreso real de cada objetivo dentro de su rango de fechas.
     */
    public function index()
    {
        $usuario_id = Auth::id();
        $objetivos = \App\Models\Objetivo::where('usuario_id', $usuario_id)
            ->orderByRaw("FIELD(estado, 'en_progreso', 'completado') ASC")
            ->get();

        foreach ($objetivos as $obj) {
            // Solo calculamos progreso de objetivos que no están ya completados manualmente
            if ($obj->estado === 'completado') {
                continue;
            }

            $fechaInicio = $obj->fecha_inicio ? Carbon::parse($obj->fecha_inicio)->toDateString() : null;
            $fechaLimite = $obj->fecha_limite ? Carbon::parse($obj->fecha_limite)->toDateString() : null;

            $actual = 0;

            if ($obj->tipo_objetivo === 'Volumen Mensual' || $obj->tipo_objetivo === 'Volumen (kg levantados)') {
                // Suma de (carga * series * repeticiones) dentro del rango de fechas del objetivo
                $query = DB::table('entrenamiento_detalles')
                    ->join('entrenamientos', 'entrenamiento_detalles.entrenamiento_id', '=', 'entrenamientos.id')
                    ->where('entrenamientos.usuario_id', $usuario_id);

                if ($fechaInicio && $fechaLimite) {
                    $query->whereBetween('entrenamientos.fecha', [$fechaInicio, $fechaLimite]);
                } elseif ($fechaInicio) {
                    $query->where('entrenamientos.fecha', '>=', $fechaInicio);
                }

                $actual = $query->sum(DB::raw('carga_kg * series * repeticiones'));

            } elseif ($obj->tipo_objetivo === 'Frecuencia Semanal' || $obj->tipo_objetivo === 'Días Entrenados') {
                // Cuenta de entrenamientos distintos (por fecha) dentro del rango
                $query = \App\Models\Entrenamiento::where('usuario_id', $usuario_id);

                if ($fechaInicio && $fechaLimite) {
                    $query->whereBetween('fecha', [$fechaInicio, $fechaLimite]);
                } elseif ($fechaInicio) {
                    $query->where('fecha', '>=', $fechaInicio);
                }

                $actual = $query->distinct('fecha')->count('fecha');

            } elseif ($obj->tipo_objetivo === 'Peso Corporal') {
                $actual = \App\Models\Metrica::where('usuario_id', $usuario_id)
                    ->orderBy('fecha_registro', 'desc')
                    ->value('peso') ?? 0;
            }

            // Guardar progreso calculado
            $obj->progreso = $actual;

            // Auto-completar si alcanzó la meta
            $porcentaje = ($obj->valor_objetivo > 0) ? ($actual / $obj->valor_objetivo) * 100 : 0;

            if ($obj->tipo_objetivo === 'Peso Corporal') {
                // Para peso corporal, completado si llegó al valor objetivo (±0.5 kg de tolerancia)
                if ($actual > 0 && abs($actual - $obj->valor_objetivo) <= 0.5) {
                    $porcentaje = 100;
                } elseif ($actual > 0 && $obj->valor_objetivo > 0) {
                    $porcentaje = ($obj->valor_objetivo > $actual)
                        ? ($actual / $obj->valor_objetivo) * 100
                        : ($obj->valor_objetivo / $actual) * 100;
                }
            }

            if ($porcentaje >= 100 && $obj->estado !== 'completado') {
                $obj->estado = 'completado';
                $obj->save();
            }
        }

        return view('objetivos.index', ['objetivos' => $objetivos]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipo_objetivo' => 'required|string',
            'valor_objetivo' => 'required|numeric|min:0.1',
            'fecha_inicio' => 'nullable|date',
            'fecha_limite' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        \App\Models\Objetivo::create([
            'usuario_id' => Auth::id(),
            'tipo_objetivo' => $request->tipo_objetivo,
            'valor_objetivo' => $request->valor_objetivo,
            'estado' => 'en_progreso',
            'fecha_inicio' => $request->fecha_inicio ? Carbon::parse($request->fecha_inicio) : now(),
            'fecha_limite' => $request->fecha_limite ? Carbon::parse($request->fecha_limite) : now()->addDays(30)
        ]);

        return redirect('/objetivos')->with('msg', '¡Objetivo guardado con éxito!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'estado' => 'required|string|in:en_progreso,completado',
        ]);

        \App\Models\Objetivo::where('id', $id)
            ->where('usuario_id', Auth::id())
            ->update([
                'estado' => $request->estado
            ]);

        return redirect('/objetivos')->with('msg', '¡Estado del objetivo actualizado!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        \App\Models\Objetivo::where('id', $id)
            ->where('usuario_id', Auth::id())
            ->delete();

        return redirect('/objetivos')->with('msg', 'Objetivo eliminado.');
    }
}

