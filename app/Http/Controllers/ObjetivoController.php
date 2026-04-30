<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ObjetivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuario_id = Auth::id();

        $objetivos = \App\Models\Objetivo::where('user_id', $usuario_id)->get();

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
            'user_id' => Auth::id(),
            'tipo_objetivo' => $request->tipo_objetivo,
            'valor_objetivo' => $request->valor_objetivo,
            'estado' => 'en_progreso',
            'fecha_inicio' => $request->fecha_inicio ? \Carbon\Carbon::parse($request->fecha_inicio) : now(),
            'fecha_limite' => $request->fecha_limite ? \Carbon\Carbon::parse($request->fecha_limite) : now()->addDays(30)
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
            ->where('user_id', Auth::id())
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
            ->where('user_id', Auth::id())
            ->delete();

        return redirect('/objetivos')->with('msg', 'Objetivo eliminado.');
    }
}
