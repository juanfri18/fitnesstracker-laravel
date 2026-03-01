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

        // 1. TOTALES GLOBALES
        $totales = \App\Models\Entrenamiento::selectRaw('IFNULL(SUM(distancia_km), 0) as total_km, IFNULL(SUM(duracion_minutos), 0) as total_min, IFNULL(SUM(calorias), 0) as total_cal')
            ->where('usuario_id', $usuario_id)
            ->first();

        // 2. TOTALES SEMANA ACTUAL
        $semana = \App\Models\Entrenamiento::selectRaw('IFNULL(SUM(distancia_km), 0) as sem_km, IFNULL(SUM(calorias), 0) as sem_cal')
            ->where('usuario_id', $usuario_id)
            ->whereRaw('YEARWEEK(fecha, 1) = YEARWEEK(CURDATE(), 1)')
            ->first();

        // 3. MEJOR MARCA
        $mejor_marca = \App\Models\Entrenamiento::where('usuario_id', $usuario_id)
            ->max('distancia_km') ?? 0;

        // 4. OBJETIVOS
        $objetivos = \App\Models\Objetivo::where('usuario_id', $usuario_id)
            ->where('estado', 'Pendiente')
            ->get();

        $lista_objetivos = [];
        foreach($objetivos as $obj) {
            $actual = 0;
            if ($obj->tipo == 'Distancia Mensual') {
                $actual = \App\Models\Entrenamiento::where('usuario_id', $usuario_id)
                    ->whereRaw('MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE())')
                    ->sum('distancia_km');
            } elseif ($obj->tipo == 'Frecuencia Semanal') {
                $actual = \App\Models\Entrenamiento::where('usuario_id', $usuario_id)
                    ->whereRaw('YEARWEEK(fecha, 1) = YEARWEEK(CURDATE(), 1)')
                    ->count();
            }

            $porcentaje = ($obj->valor_objetivo > 0) ? ($actual / $obj->valor_objetivo) * 100 : 0;
            
            $lista_objetivos[] = [
                'titulo' => $obj->tipo,
                'meta' => $obj->valor_objetivo,
                'actual' => $actual,
                'porcentaje' => min($porcentaje, 100),
                'color' => ($porcentaje >= 100) ? 'success' : 'primary'
            ];
        }

        // 5. GRÁFICA (Últimos 7)
        $chartData = \App\Models\Entrenamiento::select('fecha', 'calorias')
            ->where('usuario_id', $usuario_id)
            ->orderBy('fecha', 'asc')
            ->limit(7)
            ->get();

        $labels = [];
        $dataPoints = [];
        foreach($chartData as $row) {
            $labels[] = date('d/m', strtotime($row->fecha));
            $dataPoints[] = $row->calorias;
        }

        // Pasamos todo a la vista (convertimos los objetos a arrays para que Blade los lea igual que antes)
        return view('estadisticas', [
            'totales' => $totales ? $totales->toArray() : ['total_km' => 0, 'total_min' => 0, 'total_cal' => 0],
            'semana' => $semana ? $semana->toArray() : ['sem_km' => 0, 'sem_cal' => 0],
            'mejor_marca' => $mejor_marca,
            'lista_objetivos' => $lista_objetivos,
            'labels' => $labels,
            'dataPoints' => $dataPoints
        ]);
    }

    /**
     * Devuelve los datos JSON para las gráficas asíncronas (AJAX)
     */
    public function dashboardAPI()
    {
        $usuario_id = Auth::id();

        $chartData = \App\Models\Entrenamiento::selectRaw('fecha, SUM(calorias) as total_calorias')
            ->where('usuario_id', $usuario_id)
            ->where('fecha', '>=', now()->subDays(7))
            ->groupBy('fecha')
            ->orderBy('fecha', 'asc')
            ->get();

        $labels = [];
        $dataPoints = [];
        foreach($chartData as $row) {
            $labels[] = date('d/m', strtotime($row->fecha));
            $dataPoints[] = $row->total_calorias;
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