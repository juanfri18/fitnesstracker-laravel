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
        Schema::table('entrenamiento_detalles', function (Blueprint $table) {
            $table->dropColumn('grupo_muscular');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::table('entrenamiento_detalles', function (Blueprint $table) {
            $table->string('grupo_muscular')->nullable();
        });
    }
};
