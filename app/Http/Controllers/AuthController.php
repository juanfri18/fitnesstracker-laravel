<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class AuthController extends Controller
{
    // Mostrar formulario de Login
    public function showLoginForm()
    {
        return view('login');
    }

    // Procesar Login
    public function login(Request $request)
    {
        $request->validate([
            'correo' => ['required', 'email'],
            'contrasena' => ['required'],
        ]);

        $credenciales = [
            'correo' => $request->correo,
            'password' => $request->contrasena,
        ];

        if (Auth::attempt($credenciales)) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('msg', '¡Bienvenido de nuevo!');
        }

        return back()->withErrors([
            'correo' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('correo');
    }

    // Mostrar formulario de Registro
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Procesar Registro
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'correo' => ['required', 'string', 'email', 'max:255', 'unique:usuarios'],
            'contrasena' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'correo.unique' => 'Este correo electrónico ya está registrado.',
            'contrasena.confirmed' => 'Las contraseñas no coinciden.',
            'contrasena.min' => 'La contraseña debe tener al menos 8 caracteres.'
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'contrasena' => Hash::make($request->contrasena),
        ]);

        // Auto-login después de registrarse
        Auth::login($usuario);

        return redirect('/')->with('msg', '¡Cuenta creada con éxito! Bienvenido.');
    }

    // Procesar Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('msg', 'Has cerrado sesión correctamente.');
    }
}
