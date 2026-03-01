<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntrenamientoController;
use App\Http\Controllers\MetricaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ObjetivoController;
use App\Http\Controllers\AuthController;

// Rutas Públicas de Autenticación (Solo para invitados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/registro-usuario', [AuthController::class, 'showRegisterForm'])->name('registro.form');
    Route::post('/registro-usuario', [AuthController::class, 'register'])->name('registro.post');
});

// Ruta de Logout (Solo para autenticados)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rutas Protegidas de la Aplicación
Route::middleware(['auth'])->group(function () {
    
    // Inicio / Dashboard
    Route::get('/', [EntrenamientoController::class, 'index'])->name('home');

    // Muro de Registro de Vista individual
    Route::get('/registro', function () {
        return view('registro');
    });

    // Entrenamientos
    Route::resource('entrenamientos', EntrenamientoController::class)->except(['create', 'show']);

    // Estadísticas
    Route::get('/estadisticas', [MetricaController::class, 'index']);

    // Objetivos
    Route::resource('objetivos', ObjetivoController::class)->only(['index', 'store', 'update', 'destroy']);

    // Perfil
    Route::get('/perfil', [PerfilController::class, 'index']);
    Route::post('/perfil', [PerfilController::class, 'update']);

    // Calendario
    Route::get('/calendario', [EntrenamientoController::class, 'calendario']);
    Route::get('/api/calendario/eventos', [EntrenamientoController::class, 'eventosAPI']);

    // API de Gráficas AJAX
    Route::get('/api/metricas/dashboard', [MetricaController::class, 'dashboardAPI']);
    Route::get('/api/metricas/tipos', [MetricaController::class, 'tiposAPI']);
});