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
        // 1. Usuarios table
        Schema::table('usuarios', function (Blueprint $table) {
            $table->renameColumn('name', 'nombre');
            $table->renameColumn('email', 'correo');
            $table->renameColumn('password', 'contrasena');
            $table->renameColumn('email_verified_at', 'correo_verificado_en');
            $table->renameColumn('remember_token', 'token_recuerdo');
            $table->renameColumn('created_at', 'creado_en');
            $table->renameColumn('updated_at', 'actualizado_en');
        });

        // 2. Renaming foreign keys (dropping constraint first, then renaming, then re-adding)
        Schema::table('entrenamientos', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->renameColumn('user_id', 'usuario_id');
            $table->renameColumn('created_at', 'creado_en');
            $table->renameColumn('updated_at', 'actualizado_en');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });

        Schema::table('objetivos', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->renameColumn('user_id', 'usuario_id');
            $table->renameColumn('created_at', 'creado_en');
            $table->renameColumn('updated_at', 'actualizado_en');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });

        Schema::table('metricas', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->renameColumn('user_id', 'usuario_id');
            $table->renameColumn('created_at', 'creado_en');
            $table->renameColumn('updated_at', 'actualizado_en');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });

        // 3. Rename log_user table and its columns
        Schema::rename('logro_user', 'logro_usuario');
        Schema::table('logro_usuario', function (Blueprint $table) {
            $table->dropForeign('logro_user_user_id_foreign');
            $table->renameColumn('user_id', 'usuario_id');
            $table->renameColumn('created_at', 'creado_en');
            $table->renameColumn('updated_at', 'actualizado_en');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('logro_id')->references('id')->on('logros')->onDelete('cascade');
            
            // Re-create unique index
            $table->dropUnique('logro_user_user_id_logro_id_unique');
            $table->unique(['usuario_id', 'logro_id']);
        });

        // 4. Other tables timestamps
        Schema::table('ejercicios', function (Blueprint $table) {
            $table->renameColumn('created_at', 'creado_en');
            $table->renameColumn('updated_at', 'actualizado_en');
        });
        
        Schema::table('entrenamiento_detalles', function (Blueprint $table) {
            $table->renameColumn('created_at', 'creado_en');
            $table->renameColumn('updated_at', 'actualizado_en');
        });

        Schema::table('logros', function (Blueprint $table) {
            $table->renameColumn('created_at', 'creado_en');
            $table->renameColumn('updated_at', 'actualizado_en');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::table('spanish', function (Blueprint $table) {
            //
        });
    }
};
