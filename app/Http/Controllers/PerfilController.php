<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerfilController extends Controller
{
    public function index()
    {
        $usuario_id = 1; // Fijo temporalmente
        
        // Buscamos al usuario en la BD
        $user = DB::table('usuarios')->where('id', $usuario_id)->first();

        // Lo pasamos a la vista
        return view('perfil', ['user' => (array) $user]);
    }
}