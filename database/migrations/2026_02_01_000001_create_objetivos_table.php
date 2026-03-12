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
        Schema::create('objetivos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('tipo_objetivo'); // e.g., 'Perder Peso', 'Ganar Masa Muscular'
            $table->decimal('valor_objetivo', 8, 2);
            $table->decimal('progreso', 8, 2)->default(0);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_limite')->nullable();
            $table->string('estado')->default('en_progreso'); // e.g., 'en_progreso', 'completado'
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('objetivos');
        Schema::enableForeignKeyConstraints();
    }
};
