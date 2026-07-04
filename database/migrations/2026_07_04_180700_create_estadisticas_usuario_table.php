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
        Schema::create('estadisticas_usuario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->unique()->constrained('usuarios')->cascadeOnDelete();
            $table->unsignedInteger('total_entrenamientos')->default(0);
            $table->unsignedInteger('total_calorias')->default(0);
            $table->unsignedInteger('total_minutos')->default(0);
            $table->unsignedInteger('total_logros')->default(0);
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('estadisticas_usuario');
    }
};
