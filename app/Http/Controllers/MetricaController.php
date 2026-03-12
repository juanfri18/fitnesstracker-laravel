<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MetricaController extends Controller
{
    public function index()
    {
        $usuario_id = Auth::id();

        // 1. TOTALES GLOBALES (Entrenos, Tiempo, Volumen)
        $totales = \App\Models\Entrenamiento::selectRaw('COUNT(id) as total_entrenos, IFNULL(SUM(duracion_minutos), 0) as total_min')
            ->where('user_id', $usuario_id)
            ->first();

        // 2. TOTALES SEMANA ACTUAL
        $semana = \App\Models\Entrenamiento::selectRaw('COUNT(id) as sem_entrenos, IFNULL(SUM(duracion_minutos), 0) as sem_min')
            ->where('user_id', $usuario_id)
            ->whereRaw('YEARWEEK(fecha, 1) = YEARWEEK(CURDATE(), 1)')
            ->first();

        // 3. MEJOR MARCA (Carga Máxima)
        $mejor_marca = DB::table('entrenamiento_detalles')
            ->join('entrenamientos', 'entrenamiento_detalles.entrenamiento_id', '=', 'entrenamientos.id')
            ->where('entrenamientos.user_id', $usuario_id)
            ->max('carga_kg') ?? 0;

        // 4. OBJETIVOS
        $objetivos = \App\Models\Objetivo::where('user_id', $usuario_id)
            ->where('estado', 'en_progreso')
            ->get();

        $lista_objetivos = [];
        foreach($objetivos as $obj) {
            $actual = 0;
            if ($obj->tipo_objetivo == 'Volumen Mensual') {
                $actual = DB::table('entrenamiento_detalles')
                    ->join('entrenamientos', 'entrenamiento_detalles.entrenamiento_id', '=', 'entrenamientos.id')
                    ->where('entrenamientos.user_id', $usuario_id)
                    ->whereRaw('MONTH(entrenamientos.fecha) = MONTH(CURDATE()) AND YEAR(entrenamientos.fecha) = YEAR(CURDATE())')
                    ->sum(DB::raw('carga_kg * series * repeticiones'));
            } elseif ($obj->tipo_objetivo == 'Frecuencia Semanal') {
                $actual = \App\Models\Entrenamiento::where('user_id', $usuario_id)
                    ->whereRaw('YEARWEEK(fecha, 1) = YEARWEEK(CURDATE(), 1)')
                    ->count();
            } elseif ($obj->tipo_objetivo == 'Peso Corporal') {
                $ultimo_peso = \App\Models\Metrica::where('user_id', $usuario_id)
                    ->orderBy('fecha_registro', 'desc')
                    ->value('peso');
                // Progreso es qué tan cerca estamos (asumiendo pérdida de peso como meta o ganancia)
                // Usaremos el valor actual simplemente para mostrarlo. El porcentaje será si cruzó la meta.
                $actual = $ultimo_peso ?? 0;
            }

            // Cálculo dinámico de porcentaje (si es peso corporal, la meta puede ser menor que el actual)
            if ($obj->tipo_objetivo == 'Peso Corporal') {
                // Simplificación: si actual <= meta (para pérdida) o actual >= meta (para ganancia)
                // Necesitaríamos saber el peso inicial, pero lo aproximamos a completado si llegó a la meta.
                if ($actual > 0 && clone $obj->valor_objetivo > 0) {
                     // Si la meta es mayor (ganar masa), progreso es actual/meta. Si es menor (perder peso), es meta/actual.
                     $porcentaje = ($obj->valor_objetivo > $actual) ? ($actual / $obj->valor_objetivo) * 100 : (($obj->valor_objetivo / $actual) * 100);
                     if ($actual <= $obj->valor_objetivo && $obj->valor_objetivo < 65) $porcentaje = 100; // hack básico para pérdida
                } else {
                     $porcentaje = 0;
                }
            } else {
                $porcentaje = ($obj->valor_objetivo > 0) ? ($actual / $obj->valor_objetivo) * 100 : 0;
            }
            
            $lista_objetivos[] = [
                'titulo' => $obj->tipo_objetivo,
                'meta' => $obj->valor_objetivo,
                'actual' => $actual,
                'porcentaje' => min($porcentaje, 100),
                'color' => ($porcentaje >= 100) ? 'success' : 'primary'
            ];
        }

        // Pasamos todo a la vista
        return view('estadisticas', [
            'totales' => $totales ? $totales->toArray() : ['total_entrenos' => 0, 'total_min' => 0],
            'semana' => $semana ? $semana->toArray() : ['sem_entrenos' => 0, 'sem_min' => 0],
            'mejor_marca' => $mejor_marca,
            'lista_objetivos' => $lista_objetivos
        ]);
    }

    /**
     * Devuelve los datos JSON para las gráficas asíncronas (AJAX)
     */
    public function dashboardAPI()
    {
        $usuario_id = Auth::id();

        $chartData = \App\Models\Entrenamiento::selectRaw('fecha, SUM(duracion_minutos) as total_valores')
            ->where('user_id', $usuario_id)
            ->where('fecha', '>=', now()->subDays(7))
            ->groupBy('fecha')
            ->orderBy('fecha', 'asc')
            ->get();

        $labels = [];
        $dataPoints = [];
        foreach($chartData as $row) {
            $labels[] = date('d/m', strtotime($row->fecha));
            $dataPoints[] = $row->total_valores;
        }

        if (empty($labels)) {
            $labels = [date('d/m')];
            $dataPoints = [0];
        }

        return response()->json([
            'labels' => $labels,
            'dataPoints' => $dataPoints
        ]);
    }

    /**
     * Devuelve los datos JSON para la comparativa de tipos de entrenamientos
     */
    public function tiposAPI()
    {
        $usuario_id = Auth::id();

        $tiposData = \App\Models\Entrenamiento::selectRaw('tipo, COUNT(*) as cantidad')
            ->where('user_id', $usuario_id)
            ->groupBy('tipo')
            ->get();

        $labels = [];
        $dataPoints = [];
        foreach($tiposData as $row) {
            $labels[] = $row->tipo;
            $dataPoints[] = $row->cantidad;
        }

        if (empty($labels)) {
            $labels = ['Sin datos'];
            $dataPoints = [1];
        }

        return response()->json([
            'labels' => $labels,
            'dataPoints' => $dataPoints
        ]);
    }
}