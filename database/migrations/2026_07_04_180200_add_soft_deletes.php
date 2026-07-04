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
        Schema::table('usuarios', function (Blueprint $table) {
            $table->softDeletes('eliminado_en');
        });

        Schema::table('entrenamientos', function (Blueprint $table) {
            $table->softDeletes('eliminado_en');
        });

        Schema::table('entrenamiento_detalles', function (Blueprint $table) {
            $table->softDeletes('eliminado_en');
        });

        Schema::table('objetivos', function (Blueprint $table) {
            $table->softDeletes('eliminado_en');
        });

        Schema::table('metricas', function (Blueprint $table) {
            $table->softDeletes('eliminado_en');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropSoftDeletes('eliminado_en');
        });

        Schema::table('entrenamientos', function (Blueprint $table) {
            $table->dropSoftDeletes('eliminado_en');
        });

        Schema::table('entrenamiento_detalles', function (Blueprint $table) {
            $table->dropSoftDeletes('eliminado_en');
        });

        Schema::table('objetivos', function (Blueprint $table) {
            $table->dropSoftDeletes('eliminado_en');
        });

        Schema::table('metricas', function (Blueprint $table) {
            $table->dropSoftDeletes('eliminado_en');
        });
    }
};
