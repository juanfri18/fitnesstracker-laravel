<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntrenamientoController;
use App\Http\Controllers\MetricaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ObjetivoController;

// 1. Rutas Públicas (Autenticación mock/dummy)
Route::view('/login', 'login')->name('login');
Route::post('/logout', function () {
    return redirect('/login');
})->name('logout');

// 2. Rutas Protegidas (Simuladas en este caso con middleware web, en un futuro 'auth')
Route::middleware(['web'])->group(function () {
    
    // Inicio / Dashboard
    Route::get('/', [EntrenamientoController::class, 'index'])->name('home');

    // Registro de Vista individual
    Route::get('/registro', function () {
        return view('registro');
    });

    // Entrenamientos (Rutas RESTful mediante resource, exceptuando create/show si no se usan)
    Route::resource('entrenamientos', EntrenamientoController::class)->except(['create', 'show']);

    // Estadísticas
    Route::get('/estadisticas', [MetricaController::class, 'index']);

    // Objetivos
    Route::resource('objetivos', ObjetivoController::class)->only(['index', 'store', 'update', 'destroy']);

    // Perfil
    Route::get('/perfil', [PerfilController::class, 'index']);
});