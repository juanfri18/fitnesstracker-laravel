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
            $table->integer('racha_actual')->default(0)->after('grasa');
            $table->integer('mejor_racha')->default(0)->after('racha_actual');
            $table->date('ultima_actividad_fecha')->nullable()->after('mejor_racha');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['racha_actual', 'mejor_racha', 'ultima_actividad_fecha']);
        });
    }
};
