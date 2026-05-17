<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ejecutar las migraciones.
     */
    public function up(): void
    {
        // 1. Usuarios table using raw CHANGE to avoid RENAME COLUMN syntax errors on older MariaDB
        if (Schema::hasColumn('usuarios', 'name')) {
            DB::statement("ALTER TABLE usuarios CHANGE name nombre VARCHAR(255) NOT NULL");
        }
        if (Schema::hasColumn('usuarios', 'email')) {
            DB::statement("ALTER TABLE usuarios CHANGE email correo VARCHAR(255) NOT NULL");
        }
        if (Schema::hasColumn('usuarios', 'password')) {
            DB::statement("ALTER TABLE usuarios CHANGE password contrasena VARCHAR(255) NOT NULL");
        }
        if (Schema::hasColumn('usuarios', 'email_verified_at')) {
            DB::statement("ALTER TABLE usuarios CHANGE email_verified_at correo_verificado_en TIMESTAMP NULL DEFAULT NULL");
        }
        if (Schema::hasColumn('usuarios', 'remember_token')) {
            DB::statement("ALTER TABLE usuarios CHANGE remember_token token_recuerdo VARCHAR(100) NULL DEFAULT NULL");
        }
        if (Schema::hasColumn('usuarios', 'created_at')) {
            DB::statement("ALTER TABLE usuarios CHANGE created_at creado_en TIMESTAMP NULL DEFAULT NULL");
        }
        if (Schema::hasColumn('usuarios', 'updated_at')) {
            DB::statement("ALTER TABLE usuarios CHANGE updated_at actualizado_en TIMESTAMP NULL DEFAULT NULL");
        }

        // 2. Renaminig foreign keys and columns in other tables
        if (Schema::hasColumn('entrenamientos', 'user_id')) {
            try {
                Schema::table('entrenamientos', function (Blueprint $table) {
                    $table->dropForeign(['user_id']);
                });
            } catch (\Exception $e) {}
            DB::statement("ALTER TABLE entrenamientos CHANGE user_id usuario_id BIGINT UNSIGNED NOT NULL");
            Schema::table('entrenamientos', function (Blueprint $table) {
                $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            });
        }
        if (Schema::hasColumn('entrenamientos', 'created_at')) {
            DB::statement("ALTER TABLE entrenamientos CHANGE created_at creado_en TIMESTAMP NULL DEFAULT NULL");
        }
        if (Schema::hasColumn('entrenamientos', 'updated_at')) {
            DB::statement("ALTER TABLE entrenamientos CHANGE updated_at actualizado_en TIMESTAMP NULL DEFAULT NULL");
        }

        if (Schema::hasColumn('objetivos', 'user_id')) {
            try {
                Schema::table('objetivos', function (Blueprint $table) {
                    $table->dropForeign(['user_id']);
                });
            } catch (\Exception $e) {}
            DB::statement("ALTER TABLE objetivos CHANGE user_id usuario_id BIGINT UNSIGNED NOT NULL");
            Schema::table('objetivos', function (Blueprint $table) {
                $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            });
        }
        if (Schema::hasColumn('objetivos', 'created_at')) {
            DB::statement("ALTER TABLE objetivos CHANGE created_at creado_en TIMESTAMP NULL DEFAULT NULL");
        }
        if (Schema::hasColumn('objetivos', 'updated_at')) {
            DB::statement("ALTER TABLE objetivos CHANGE updated_at actualizado_en TIMESTAMP NULL DEFAULT NULL");
        }

        if (Schema::hasColumn('metricas', 'user_id')) {
            try {
                Schema::table('metricas', function (Blueprint $table) {
                    $table->dropForeign(['user_id']);
                });
            } catch (\Exception $e) {}
            DB::statement("ALTER TABLE metricas CHANGE user_id usuario_id BIGINT UNSIGNED NOT NULL");
            Schema::table('metricas', function (Blueprint $table) {
                $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            });
        }
        if (Schema::hasColumn('metricas', 'created_at')) {
            DB::statement("ALTER TABLE metricas CHANGE created_at creado_en TIMESTAMP NULL DEFAULT NULL");
        }
        if (Schema::hasColumn('metricas', 'updated_at')) {
            DB::statement("ALTER TABLE metricas CHANGE updated_at actualizado_en TIMESTAMP NULL DEFAULT NULL");
        }

        // 3. Rename log_user table and its columns
        if (Schema::hasTable('logro_user')) {
            Schema::rename('logro_user', 'logro_usuario');
        }
        
        if (Schema::hasTable('logro_usuario') && Schema::hasColumn('logro_usuario', 'user_id')) {
            Schema::table('logro_usuario', function (Blueprint $table) {
                try {
                    $table->dropForeign('logro_user_user_id_foreign');
                } catch (\Exception $e) {}
                try {
                    $table->dropForeign('logro_user_logro_id_foreign');
                } catch (\Exception $e) {}
            });
            DB::statement("ALTER TABLE logro_usuario CHANGE user_id usuario_id BIGINT UNSIGNED NOT NULL");
            
            Schema::table('logro_usuario', function (Blueprint $table) {
                try {
                    $table->dropUnique('logro_user_user_id_logro_id_unique');
                } catch (\Exception $e) {}
                
                $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
                $table->foreign('logro_id')->references('id')->on('logros')->onDelete('cascade');
                
                // Re-create unique index
                $table->unique(['usuario_id', 'logro_id']);
            });
        }
        if (Schema::hasTable('logro_usuario')) {
            if (Schema::hasColumn('logro_usuario', 'created_at')) {
                DB::statement("ALTER TABLE logro_usuario CHANGE created_at creado_en TIMESTAMP NULL DEFAULT NULL");
            }
            if (Schema::hasColumn('logro_usuario', 'updated_at')) {
                DB::statement("ALTER TABLE logro_usuario CHANGE updated_at actualizado_en TIMESTAMP NULL DEFAULT NULL");
            }
        }

        // 4. Other tables timestamps using raw CHANGE
        if (Schema::hasColumn('ejercicios', 'created_at')) {
            DB::statement("ALTER TABLE ejercicios CHANGE created_at creado_en TIMESTAMP NULL DEFAULT NULL");
        }
        if (Schema::hasColumn('ejercicios', 'updated_at')) {
            DB::statement("ALTER TABLE ejercicios CHANGE updated_at actualizado_en TIMESTAMP NULL DEFAULT NULL");
        }
        
        if (Schema::hasColumn('entrenamiento_detalles', 'created_at')) {
            DB::statement("ALTER TABLE entrenamiento_detalles CHANGE created_at creado_en TIMESTAMP NULL DEFAULT NULL");
        }
        if (Schema::hasColumn('entrenamiento_detalles', 'updated_at')) {
            DB::statement("ALTER TABLE entrenamiento_detalles CHANGE updated_at actualizado_en TIMESTAMP NULL DEFAULT NULL");
        }

        if (Schema::hasColumn('logros', 'created_at')) {
            DB::statement("ALTER TABLE logros CHANGE created_at creado_en TIMESTAMP NULL DEFAULT NULL");
        }
        if (Schema::hasColumn('logros', 'updated_at')) {
            DB::statement("ALTER TABLE logros CHANGE updated_at actualizado_en TIMESTAMP NULL DEFAULT NULL");
        }
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        // No down needed as we're fixing an active migration
    }
};
