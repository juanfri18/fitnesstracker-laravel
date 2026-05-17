<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function index()
    {
        // Obtenemos el usuario autenticado directamente usando el Facade Auth
        $usuario = Auth::user();

        // Lo pasamos a la vista
        return view('perfil', ['usuario' => $usuario]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'biografia' => 'nullable|string|max:500',
            'peso' => 'nullable|numeric|min:20|max:300',
            'altura' => 'nullable|numeric|min:50|max:250',
            'edad' => 'nullable|numeric|min:10|max:100',
            'genero' => 'nullable|string|in:Hombre,Mujer',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'nivel_actividad' => 'nullable|string|max:255',
        ]);

        $usuario = Auth::user();
        
        $grasa_calculada = null;

        // Cálculo automático de Grasa Corporal (Aproximación por IMC)
        $peso_calc = $request->peso ?? $usuario->peso;
        $altura_calc = $request->altura ?? $usuario->altura;
        $edad_calc = $request->edad ?? $usuario->edad;
        $genero_calc = $request->genero ?? $usuario->genero;

        if ($peso_calc && $altura_calc && $edad_calc && $genero_calc) {
            $altura_metros = $altura_calc / 100;
            $imc = $peso_calc / ($altura_metros * $altura_metros);
            $factor_genero = ($genero_calc === 'Hombre') ? 1 : 0;
            
            $grasa_calculada = (1.20 * $imc) + (0.23 * $edad_calc) - (10.8 * $factor_genero) - 5.4;
            // Evitar resultados absurdos o negativos
            $grasa_calculada = max(1, min(60, round($grasa_calculada, 1)));
        }

        // Variable para la ruta de la foto, por defecto la que ya tiene el usuario
        $foto_path = $usuario->foto;

        // Manejar subida de foto
        if ($request->hasFile('foto')) {
            // Guardar en storage/app/public/avatars
            $foto_path = $request->file('foto')->store('avatars', 'public');
        }

        \App\Models\Usuario::where('id', $usuario->id)->update([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'biografia' => $request->biografia,
            'peso' => $request->peso,
            'altura' => $request->altura,
            'edad' => $request->edad,
            'genero' => $request->genero,
            'grasa' => $grasa_calculada ?? $usuario->grasa,
            'foto' => $foto_path,
            'nivel_actividad' => $request->nivel_actividad,
            'actualizado_en' => now(),
        ]);

        return redirect('/perfil')->with('msg', 'Perfil actualizado correctamente.');
    }
}