<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('objetivos')->where('tipo_objetivo', 'Frecuencia Semanal')->update(['tipo_objetivo' => 'Días Entrenados']);
        DB::table('objetivos')->where('tipo_objetivo', 'Volumen Mensual')->update(['tipo_objetivo' => 'Volumen (kg levantados)']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('objetivos')->where('tipo_objetivo', 'Días Entrenados')->update(['tipo_objetivo' => 'Frecuencia Semanal']);
        DB::table('objetivos')->where('tipo_objetivo', 'Volumen (kg levantados)')->update(['tipo_objetivo' => 'Volumen Mensual']);
    }
};
