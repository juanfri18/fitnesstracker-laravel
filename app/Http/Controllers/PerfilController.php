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
        $usuario->load('perfil', 'racha', 'metricaActual');

        // Lo pasamos a la vista
        return view('perfil', ['usuario' => $usuario]);
    }

    public function update(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|max:255|unique:usuarios,correo,' . $usuario->id,
            'contrasena' => 'nullable|string|min:8|confirmed',
            'apellidos' => 'nullable|string|max:255',
            'biografia' => 'nullable|string|max:500',
            'peso' => 'nullable|numeric|min:20|max:300',
            'altura' => 'nullable|numeric|min:50|max:250',
            'edad' => 'nullable|numeric|min:10|max:100',
            'genero' => 'nullable|string|in:Hombre,Mujer',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'nivel_actividad' => 'nullable|string|max:255',
        ]);

        $perfil = $usuario->perfil ?? \App\Models\PerfilUsuario::create(['usuario_id' => $usuario->id]);
        
        // Cálculo automático de Grasa Corporal (Aproximación por IMC)
        $grasa_calculada = null;
        $peso_calc = $request->peso ?? ($usuario->metricaActual->peso ?? null);
        $altura_calc = $request->altura ?? $perfil->altura;
        $edad_calc = $request->edad ?? $perfil->edad;
        $genero_calc = $request->genero ?? $perfil->genero;

        if ($peso_calc && $altura_calc && $edad_calc && $genero_calc) {
            $altura_metros = $altura_calc / 100;
            $imc = $peso_calc / ($altura_metros * $altura_metros);
            $factor_genero = ($genero_calc === 'Hombre') ? 1 : 0;
            
            $grasa_calculada = (1.20 * $imc) + (0.23 * $edad_calc) - (10.8 * $factor_genero) - 5.4;
            $grasa_calculada = max(1, min(60, round($grasa_calculada, 1)));
        }

        // Actualizar datos de usuario
        $usuario->nombre = $request->nombre;
        $usuario->correo = $request->correo;
        if ($request->filled('contrasena')) {
            $usuario->contrasena = $request->contrasena;
        }
        $usuario->save();

        // Manejar subida de foto
        $foto_path = $perfil->foto;
        if ($request->hasFile('foto')) {
            $foto_path = $request->file('foto')->store('avatars', 'public');
        }

        // Actualizar perfil
        $perfil->update([
            'apellidos' => $request->apellidos,
            'biografia' => $request->biografia,
            'edad' => $request->edad,
            'altura' => $request->altura,
            'genero' => $request->genero,
            'foto' => $foto_path,
            'nivel_actividad' => $request->nivel_actividad,
        ]);

        // Si hay peso nuevo, crear métrica
        if ($request->filled('peso')) {
            \App\Models\Metrica::create([
                'usuario_id' => $usuario->id,
                'peso' => $request->peso,
                'altura' => $request->altura ?? $perfil->altura,
                'fecha_registro' => now()->toDateString(),
            ]);
        }

        return redirect('/perfil')->with('msg', 'Perfil actualizado correctamente.');
    }
}