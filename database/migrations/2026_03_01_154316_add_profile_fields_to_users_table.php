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
            $table->string('apellidos')->nullable()->after('name');
            $table->string('biografia')->nullable()->after('apellidos');
            $table->decimal('peso', 5, 2)->nullable()->after('biografia');
            $table->decimal('grasa', 5, 2)->nullable()->after('peso');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['apellidos', 'biografia', 'peso', 'grasa']);
        });
    }
};
