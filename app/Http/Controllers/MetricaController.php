<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MetricaController extends Controller
{
    public function index(Request $request)
    {
        $usuario_id = Auth::id();
        $periodo = $request->query('periodo', 'semana');

        // 1. TOTALES GLOBALES (Entrenos, Tiempo, Volumen)
        $totales = \App\Models\Entrenamiento::selectRaw('COUNT(id) as total_entrenos, IFNULL(SUM(duracion_minutos), 0) as total_min')
            ->where('usuario_id', $usuario_id)
            ->first();

        // Filtros dinámicos de tiempo (Semana, Mes, Año)
        $inicio = now()->startOfWeek();
        $fin = now()->endOfWeek();
        $inicio_pasado = now()->subWeek()->startOfWeek();
        $fin_pasado = now()->subWeek()->endOfWeek();
        
        if ($periodo === 'mes') {
            $inicio = now()->startOfMonth();
            $fin = now()->endOfMonth();
            $inicio_pasado = now()->subMonth()->startOfMonth();
            $fin_pasado = now()->subMonth()->endOfMonth();
        } elseif ($periodo === 'anio') {
            $inicio = now()->startOfYear();
            $fin = now()->endOfYear();
            $inicio_pasado = now()->subYear()->startOfYear();
            $fin_pasado = now()->subYear()->endOfYear();
        }

        // 2. TOTALES PERIODO ACTUAL
        $semana = \App\Models\Entrenamiento::selectRaw('COUNT(id) as sem_entrenos, IFNULL(SUM(duracion_minutos), 0) as sem_min')
            ->where('usuario_id', $usuario_id)
            ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
            ->first();

        // 2.5 TENDENCIA (vs Periodo Pasado)
        $semana_pasada = \App\Models\Entrenamiento::selectRaw('COUNT(id) as sem_entrenos, IFNULL(SUM(duracion_minutos), 0) as sem_min')
            ->where('usuario_id', $usuario_id)
            ->whereBetween('fecha', [$inicio_pasado->toDateString(), $fin_pasado->toDateString()])
            ->first();
        
        $tendencia_porcentaje = 0;
        if ($semana_pasada && $semana_pasada->sem_min > 0) {
            $tendencia_porcentaje = (($semana->sem_min - $semana_pasada->sem_min) / $semana_pasada->sem_min) * 100;
        } elseif ($semana && $semana->sem_min > 0) {
            $tendencia_porcentaje = 100;
        }

        // 3. MEJOR MARCA (Carga Máxima)
        $mejor_marca = DB::table('entrenamiento_detalles')
            ->join('entrenamientos', 'entrenamiento_detalles.entrenamiento_id', '=', 'entrenamientos.id')
            ->where('entrenamientos.usuario_id', $usuario_id)
            ->max('carga_kg') ?? 0;

        // 4. OBJETIVOS (con progreso calculado dentro del rango de fechas de cada objetivo)
        $objetivos = \App\Models\Objetivo::where('usuario_id', $usuario_id)
            ->where('estado', 'en_progreso')
            ->get();

        $lista_objetivos = [];
        foreach($objetivos as $obj) {
            $actual = 0;
            $fechaInicio = $obj->fecha_inicio ? \Carbon\Carbon::parse($obj->fecha_inicio)->toDateString() : null;
            $fechaLimite = $obj->fecha_limite ? \Carbon\Carbon::parse($obj->fecha_limite)->toDateString() : null;

            if (in_array($obj->tipo_objetivo, ['Volumen Mensual', 'Volumen (kg levantados)'])) {
                $query = DB::table('entrenamiento_detalles')
                    ->join('entrenamientos', 'entrenamiento_detalles.entrenamiento_id', '=', 'entrenamientos.id')
                    ->where('entrenamientos.usuario_id', $usuario_id);

                if ($fechaInicio && $fechaLimite) {
                    $query->whereBetween('entrenamientos.fecha', [$fechaInicio, $fechaLimite]);
                } elseif ($fechaInicio) {
                    $query->where('entrenamientos.fecha', '>=', $fechaInicio);
                }

                $actual = $query->sum(DB::raw('carga_kg * series * repeticiones'));

            } elseif (in_array($obj->tipo_objetivo, ['Frecuencia Semanal', 'Días Entrenados'])) {
                $query = \App\Models\Entrenamiento::where('usuario_id', $usuario_id);

                if ($fechaInicio && $fechaLimite) {
                    $query->whereBetween('fecha', [$fechaInicio, $fechaLimite]);
                } elseif ($fechaInicio) {
                    $query->where('fecha', '>=', $fechaInicio);
                }

                $actual = $query->distinct('fecha')->count('fecha');

            } elseif ($obj->tipo_objetivo == 'Peso Corporal') {
                $actual = \App\Models\Metrica::where('usuario_id', $usuario_id)
                    ->orderBy('fecha_registro', 'desc')
                    ->value('peso') ?? 0;
            }

            // Porcentaje
            if ($obj->tipo_objetivo == 'Peso Corporal') {
                if ($actual > 0 && abs($actual - $obj->valor_objetivo) <= 0.5) {
                    $porcentaje = 100;
                } elseif ($actual > 0 && $obj->valor_objetivo > 0) {
                    $porcentaje = ($obj->valor_objetivo > $actual)
                        ? ($actual / $obj->valor_objetivo) * 100
                        : ($obj->valor_objetivo / $actual) * 100;
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

        // Nombre legible del periodo para la vista
        $periodo_labels = [
            'semana' => 'la semana pasada',
            'mes' => 'el mes pasado',
            'anio' => 'el año pasado',
        ];

        // Pasamos todo a la vista
        return view('estadisticas', [
            'totales' => $totales ? $totales->toArray() : ['total_entrenos' => 0, 'total_min' => 0],
            'semana' => $semana ? $semana->toArray() : ['sem_entrenos' => 0, 'sem_min' => 0],
            'tendencia_porcentaje' => round($tendencia_porcentaje),
            'periodo_anterior_entrenos' => $semana_pasada ? $semana_pasada->sem_entrenos : 0,
            'periodo_label' => $periodo_labels[$periodo] ?? 'el periodo anterior',
            'periodo_actual' => $periodo,
            'mejor_marca' => $mejor_marca,
            'lista_objetivos' => $lista_objetivos
        ]);
    }

    /**
     * Devuelve los datos JSON para las gráficas asíncronas (AJAX)
     * Genera TODOS los puntos del periodo (sin huecos) para una gráfica coherente.
     */
    public function dashboardAPI(Request $request)
    {
        $usuario_id = Auth::id();
        $periodo = $request->query('periodo', 'semana');

        $labels = [];
        $dataPoints = [];

        if ($periodo === 'anio') {
            // ANUAL: 12 puntos, uno por cada mes del año actual (Ene → Dic)
            $anioActual = now()->year;
            $mesActual = now()->month;

            // Consulta agrupada por mes
            $datosRaw = \App\Models\Entrenamiento::selectRaw('MONTH(fecha) as mes, SUM(duracion_minutos) as total')
                ->where('usuario_id', $usuario_id)
                ->whereYear('fecha', $anioActual)
                ->groupBy('mes')
                ->pluck('total', 'mes');

            $mesesNombres = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
            for ($m = 1; $m <= 12; $m++) {
                $labels[] = $mesesNombres[$m - 1];
                $dataPoints[] = (int)($datosRaw[$m] ?? 0);
            }
        } elseif ($periodo === 'mes') {
            // MENSUAL: todos los días del mes actual
            $inicioMes = now()->startOfMonth();
            $finMes = now()->endOfMonth();
            $diasEnMes = $inicioMes->daysInMonth;

            $datosRaw = \App\Models\Entrenamiento::selectRaw('DAY(fecha) as dia, SUM(duracion_minutos) as total')
                ->where('usuario_id', $usuario_id)
                ->whereMonth('fecha', now()->month)
                ->whereYear('fecha', now()->year)
                ->groupBy('dia')
                ->pluck('total', 'dia');

            for ($d = 1; $d <= $diasEnMes; $d++) {
                $labels[] = str_pad($d, 2, '0', STR_PAD_LEFT) . '/' . str_pad(now()->month, 2, '0', STR_PAD_LEFT);
                $dataPoints[] = (int)($datosRaw[$d] ?? 0);
            }
        } else {
            // SEMANAL (default): últimos 7 días, terminando en hoy
            $datosRaw = \App\Models\Entrenamiento::selectRaw('DATE(fecha) as dia, SUM(duracion_minutos) as total')
                ->where('usuario_id', $usuario_id)
                ->where('fecha', '>=', now()->subDays(6)->toDateString())
                ->where('fecha', '<=', now()->toDateString())
                ->groupBy('dia')
                ->pluck('total', 'dia');

            for ($i = 6; $i >= 0; $i--) {
                $fecha = now()->subDays($i);
                $key = $fecha->toDateString();
                $labels[] = $fecha->format('d/m');
                $dataPoints[] = (int)($datosRaw[$key] ?? 0);
            }
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
            ->where('usuario_id', $usuario_id)
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