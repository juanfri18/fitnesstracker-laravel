<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MetricaController extends Controller
{
    public function index()
    {
        $usuario_id = 1; // Fijo temporalmente hasta que hagamos el Login en el Sprint 5

        // 1. TOTALES GLOBALES
        $totales = DB::table('entrenamientos')
            ->selectRaw('IFNULL(SUM(distancia_km), 0) as total_km, IFNULL(SUM(duracion_minutos), 0) as total_min, IFNULL(SUM(calorias), 0) as total_cal')
            ->where('usuario_id', $usuario_id)
            ->first();

        // 2. TOTALES SEMANA ACTUAL
        $semana = DB::table('entrenamientos')
            ->selectRaw('IFNULL(SUM(distancia_km), 0) as sem_km, IFNULL(SUM(calorias), 0) as sem_cal')
            ->where('usuario_id', $usuario_id)
            ->whereRaw('YEARWEEK(fecha, 1) = YEARWEEK(CURDATE(), 1)')
            ->first();

        // 3. MEJOR MARCA
        $mejor_marca = DB::table('entrenamientos')
            ->where('usuario_id', $usuario_id)
            ->max('distancia_km') ?? 0;

        // 4. OBJETIVOS
        $objetivos = DB::table('objetivos')
            ->where('usuario_id', $usuario_id)
            ->where('estado', 'Pendiente')
            ->get();

        $lista_objetivos = [];
        foreach($objetivos as $obj) {
            $actual = 0;
            if ($obj->tipo == 'Distancia Mensual') {
                $actual = DB::table('entrenamientos')
                    ->where('usuario_id', $usuario_id)
                    ->whereRaw('MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE())')
                    ->sum('distancia_km');
            } elseif ($obj->tipo == 'Frecuencia Semanal') {
                $actual = DB::table('entrenamientos')
                    ->where('usuario_id', $usuario_id)
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
        $chartData = DB::table('entrenamientos')
            ->select('fecha', 'calorias')
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
            'totales' => (array) $totales,
            'semana' => (array) $semana,
            'mejor_marca' => $mejor_marca,
            'lista_objetivos' => $lista_objetivos,
            'labels' => $labels,
            'dataPoints' => $dataPoints
        ]);
    }
}