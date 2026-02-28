<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Herramienta para hablar con la Base de Datos

class EntrenamientoController extends Controller
{
    public function index()
    {
        // 1. Como aún no tenemos sistema de Login en Laravel, fingimos que somos el usuario 1
        $usuario_id = 1; 

        // 2. Consulta a la base de datos real (equivalente a tu antiguo PDO)
        $actividades = DB::table('entrenamientos')
                        ->where('usuario_id', $usuario_id)
                        ->orderBy('fecha', 'desc')
                        ->limit(5)
                        ->get();

        // Convertimos el resultado a un array normal para que Blade lo entienda fácil
        $actividades_array = json_decode(json_encode($actividades), true);

        // 3. Devolvemos la vista y le pasamos los datos reales
        return view('inicio', ['actividades' => $actividades_array]);
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

        // 3. GUARDAR EN BASE DE DATOS
        DB::table('entrenamientos')->insert([
            'usuario_id' => 1, // Fijo temporalmente
            'fecha' => $request->fecha,
            'tipo' => $tipo_db,
            'duracion_minutos' => $duracion,
            'sensacion' => $sensacion,
            'distancia_km' => $distancia,
            'calorias' => round($calorias_calculadas)
        ]);

        // 4. REDIRECCIÓN ELEGANTE
        // Esto sustituye a tu antiguo: header("Location: index.php?msg=guardado");
        return redirect('/')->with('msg', '¡Actividad registrada con éxito!');
    }
    // 1. Mostrar el formulario de edición
    public function edit($id)
    {
        $usuario_id = 1; // Fijo temporalmente
        $entreno = DB::table('entrenamientos')->where('id', $id)->where('usuario_id', $usuario_id)->first();

        if (!$entreno) {
            return redirect('/');
        }

        return view('editar', ['entreno' => (array) $entreno]);
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

        DB::table('entrenamientos')
            ->where('id', $id)
            ->where('usuario_id', 1) // Seguridad: solo el dueño puede editar
            ->update([
                'fecha' => $request->fecha,
                'tipo' => $tipo_db,
                'duracion_minutos' => $duracion,
                'sensacion' => $sensacion,
                'distancia_km' => $distancia,
                'calorias' => round($calorias)
            ]);

        return redirect('/')->with('msg', '¡Actividad actualizada correctamente!');
    }

    // 3. Eliminar de la BD
    public function destroy($id)
    {
        DB::table('entrenamientos')
            ->where('id', $id)
            ->where('usuario_id', 1) // Seguridad: solo el dueño puede borrar
            ->delete();

        return redirect('/')->with('msg', 'Entrenamiento eliminado.');
    }
}