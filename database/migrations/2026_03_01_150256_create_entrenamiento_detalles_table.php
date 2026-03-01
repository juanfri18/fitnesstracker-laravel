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
        Schema::create('entrenamiento_detalles', function (Blueprint $table) {
            $table->id();
            $table->integer('entrenamiento_id');
            $table->string('grupo_muscular');
            $table->string('ejercicio');
            $table->integer('series')->nullable();
            $table->integer('repeticiones')->nullable();
            $table->decimal('carga_kg', 8, 2)->nullable();
            $table->timestamps();

            // Foreign key (Asumiendo que la tabla se llama entrenamientos)
            $table->foreign('entrenamiento_id')->references('id')->on('entrenamientos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrenamiento_detalles');
    }
};
