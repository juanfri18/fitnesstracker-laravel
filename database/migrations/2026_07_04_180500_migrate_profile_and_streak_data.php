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
        // 1. Copy data from usuarios to perfiles_usuario and rachas
        DB::table('usuarios')->orderBy('id')->chunk(100, function ($usuarios) {
            foreach ($usuarios as $usuario) {
                DB::table('perfiles_usuario')->insert([
                    'usuario_id' => $usuario->id,
                    'apellidos' => $usuario->apellidos ?? null,
                    'biografia' => $usuario->biografia ?? null,
                    'genero' => $usuario->genero ?? null,
                    'edad' => $usuario->edad ?? null,
                    'altura' => $usuario->altura ?? null,
                    'foto' => $usuario->foto ?? null,
                    'nivel_actividad' => $usuario->nivel_actividad ?? null,
                    'creado_en' => now(),
                    'actualizado_en' => now(),
                ]);

                DB::table('rachas')->insert([
                    'usuario_id' => $usuario->id,
                    'racha_actual' => $usuario->racha_actual ?? 0,
                    'mejor_racha' => $usuario->mejor_racha ?? 0,
                    'ultima_actividad_fecha' => $usuario->ultima_actividad_fecha ?? null,
                    'creado_en' => now(),
                    'actualizado_en' => now(),
                ]);
            }
        });

        // 2. Drop old columns from usuarios
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->dropColumn([
                    'apellidos',
                    'biografia',
                    'genero',
                    'edad',
                    'altura',
                    'peso',
                    'grasa',
                    'foto',
                    'nivel_actividad',
                    'racha_actual',
                    'mejor_racha',
                    'ultima_actividad_fecha',
                ]);
            });
        }
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('apellidos')->nullable();
            $table->string('biografia')->nullable();
            $table->string('genero')->nullable();
            $table->integer('edad')->nullable();
            $table->integer('altura')->nullable()->comment('Altura en centímetros');
            $table->decimal('peso', 5, 2)->nullable();
            $table->decimal('grasa', 5, 2)->nullable();
            $table->string('foto')->nullable();
            $table->string('nivel_actividad')->nullable();
            $table->integer('racha_actual')->default(0);
            $table->integer('mejor_racha')->default(0);
            $table->date('ultima_actividad_fecha')->nullable();
        });
    }
};
