<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar las migraciones.
     */
    public function up(): void
    {
        Schema::table('entrenamientos', function (Blueprint $table) {
            $table->index(['usuario_id', 'fecha'], 'idx_entrenamientos_usuario_fecha');
        });

        Schema::table('metricas', function (Blueprint $table) {
            $table->index(['usuario_id', 'fecha_registro'], 'idx_metricas_usuario_fecha');
        });

        Schema::table('objetivos', function (Blueprint $table) {
            $table->index(['usuario_id', 'estado'], 'idx_objetivos_usuario_estado');
            $table->index('fecha_limite', 'idx_objetivos_fecha_limite');
        });

        Schema::table('ejercicios', function (Blueprint $table) {
            $table->index('grupo_muscular', 'idx_ejercicios_grupo_muscular');
        });

        Schema::table('entrenamiento_detalles', function (Blueprint $table) {
            $table->index(['entrenamiento_id', 'ejercicio_id'], 'idx_detalles_entrenamiento_ejercicio');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::table('entrenamientos', function (Blueprint $table) {
            $table->dropIndex('idx_entrenamientos_usuario_fecha');
        });

        Schema::table('metricas', function (Blueprint $table) {
            $table->dropIndex('idx_metricas_usuario_fecha');
        });

        Schema::table('objetivos', function (Blueprint $table) {
            $table->dropIndex('idx_objetivos_usuario_estado');
            $table->dropIndex('idx_objetivos_fecha_limite');
        });

        Schema::table('ejercicios', function (Blueprint $table) {
            $table->dropIndex('idx_ejercicios_grupo_muscular');
        });

        Schema::table('entrenamiento_detalles', function (Blueprint $table) {
            $table->dropIndex('idx_detalles_entrenamiento_ejercicio');
        });
    }
};
