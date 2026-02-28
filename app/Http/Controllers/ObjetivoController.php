<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ObjetivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuario_id = 1; // Fijo temporalmente

        $objetivos = DB::table('objetivos')
            ->where('usuario_id', $usuario_id)
            ->get();

        return view('objetivos.index', ['objetivos' => $objetivos]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|string',
            'valor_objetivo' => 'required|numeric',
        ]);

        DB::table('objetivos')->insert([
            'usuario_id' => 1,
            'tipo' => $request->tipo,
            'valor_objetivo' => $request->valor_objetivo,
            'estado' => 'Pendiente',
            'fecha_creacion' => now(),
        ]);

        return redirect('/estadisticas')->with('msg', '¡Objetivo guardado con éxito!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'estado' => 'required|string|in:Pendiente,Completado',
        ]);

        DB::table('objetivos')
            ->where('id', $id)
            ->where('usuario_id', 1)
            ->update([
                'estado' => $request->estado
            ]);

        return redirect('/estadisticas')->with('msg', '¡Estado del objetivo actualizado!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::table('objetivos')
            ->where('id', $id)
            ->where('usuario_id', 1)
            ->delete();

        return redirect('/estadisticas')->with('msg', 'Objetivo eliminado.');
    }
}
