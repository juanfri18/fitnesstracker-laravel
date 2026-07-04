<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar las migraciones.
     */
    public function up(): void
    {
        Schema::create('tipos_entrenamiento', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('icono')->nullable();
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });

        Schema::create('tipos_objetivo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });

        // Seed initial data for tipos_entrenamiento
        DB::table('tipos_entrenamiento')->insert([
            ['nombre' => 'Fuerza', 'icono' => 'fas fa-dumbbell', 'creado_en' => now(), 'actualizado_en' => now()],
            ['nombre' => 'Carrera', 'icono' => 'fas fa-running', 'creado_en' => now(), 'actualizado_en' => now()],
            ['nombre' => 'Caminata', 'icono' => 'fas fa-walking', 'creado_en' => now(), 'actualizado_en' => now()],
        ]);

        // Seed initial data for tipos_objetivo
        DB::table('tipos_objetivo')->insert([
            ['nombre' => 'Días Entrenados', 'creado_en' => now(), 'actualizado_en' => now()],
            ['nombre' => 'Peso Corporal', 'creado_en' => now(), 'actualizado_en' => now()],
            ['nombre' => 'Volumen (kg levantados)', 'creado_en' => now(), 'actualizado_en' => now()],
            ['nombre' => 'Volumen Mensual', 'creado_en' => now(), 'actualizado_en' => now()],
            ['nombre' => 'Frecuencia Semanal', 'creado_en' => now(), 'actualizado_en' => now()],
        ]);
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_objetivo');
        Schema::dropIfExists('tipos_entrenamiento');
    }
};
