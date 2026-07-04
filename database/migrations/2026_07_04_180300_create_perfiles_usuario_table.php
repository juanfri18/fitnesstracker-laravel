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
        Schema::create('perfiles_usuario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->unique()->constrained('usuarios')->cascadeOnDelete();
            $table->string('apellidos')->nullable();
            $table->string('biografia')->nullable();
            $table->string('genero')->nullable();
            $table->integer('edad')->nullable();
            $table->integer('altura')->nullable()->comment('Altura en centímetros');
            $table->string('foto')->nullable();
            $table->string('nivel_actividad')->nullable();
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfiles_usuario');
    }
};
