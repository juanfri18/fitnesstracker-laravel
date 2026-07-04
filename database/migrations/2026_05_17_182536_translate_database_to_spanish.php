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
        $isSqlite = Schema::getConnection()->getDriverName() === 'sqlite';

        // 1. Usuarios table
        if ($isSqlite) {
            if (Schema::hasColumn('usuarios', 'name')) {
                Schema::table('usuarios', function (Blueprint $table) { $table->renameColumn('name', 'nombre'); });
            }
            if (Schema::hasColumn('usuarios', 'email')) {
                Schema::table('usuarios', function (Blueprint $table) { $table->renameColumn('email', 'correo'); });
            }
            if (Schema::hasColumn('usuarios', 'password')) {
                Schema::table('usuarios', function (Blueprint $table) { $table->renameColumn('password', 'contrasena'); });
            }
            if (Schema::hasColumn('usuarios', 'email_verified_at')) {
                Schema::table('usuarios', function (Blueprint $table) { $table->renameColumn('email_verified_at', 'correo_verificado_en'); });
            }
            if (Schema::hasColumn('usuarios', 'remember_token')) {
                Schema::table('usuarios', function (Blueprint $table) { $table->renameColumn('remember_token', 'token_recuerdo'); });
            }
            if (Schema::hasColumn('usuarios', 'created_at')) {
                Schema::table('usuarios', function (Blueprint $table) { $table->renameColumn('created_at', 'creado_en'); });
            }
            if (Schema::hasColumn('usuarios', 'updated_at')) {
                Schema::table('usuarios', function (Blueprint $table) { $table->renameColumn('updated_at', 'actualizado_en'); });
            }
        } else {
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
        }

        // 2. Entrenamientos
        if (Schema::hasColumn('entrenamientos', 'user_id')) {
            if (!$isSqlite) {
                try {
                    Schema::table('entrenamientos', function (Blueprint $table) {
                        $table->dropForeign(['user_id']);
                    });
                } catch (\Exception $e) {}
            }
            if ($isSqlite) {
                Schema::table('entrenamientos', function (Blueprint $table) { $table->renameColumn('user_id', 'usuario_id'); });
            } else {
                DB::statement("ALTER TABLE entrenamientos CHANGE user_id usuario_id BIGINT UNSIGNED NOT NULL");
            }
            Schema::table('entrenamientos', function (Blueprint $table) {
                $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            });
        }
        if ($isSqlite) {
            if (Schema::hasColumn('entrenamientos', 'created_at')) {
                Schema::table('entrenamientos', function (Blueprint $table) { $table->renameColumn('created_at', 'creado_en'); });
            }
            if (Schema::hasColumn('entrenamientos', 'updated_at')) {
                Schema::table('entrenamientos', function (Blueprint $table) { $table->renameColumn('updated_at', 'actualizado_en'); });
            }
        } else {
            if (Schema::hasColumn('entrenamientos', 'created_at')) {
                DB::statement("ALTER TABLE entrenamientos CHANGE created_at creado_en TIMESTAMP NULL DEFAULT NULL");
            }
            if (Schema::hasColumn('entrenamientos', 'updated_at')) {
                DB::statement("ALTER TABLE entrenamientos CHANGE updated_at actualizado_en TIMESTAMP NULL DEFAULT NULL");
            }
        }

        // 3. Objetivos
        if (Schema::hasColumn('objetivos', 'user_id')) {
            if (!$isSqlite) {
                try {
                    Schema::table('objetivos', function (Blueprint $table) {
                        $table->dropForeign(['user_id']);
                    });
                } catch (\Exception $e) {}
            }
            if ($isSqlite) {
                Schema::table('objetivos', function (Blueprint $table) { $table->renameColumn('user_id', 'usuario_id'); });
            } else {
                DB::statement("ALTER TABLE objetivos CHANGE user_id usuario_id BIGINT UNSIGNED NOT NULL");
            }
            Schema::table('objetivos', function (Blueprint $table) {
                $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            });
        }
        if ($isSqlite) {
            if (Schema::hasColumn('objetivos', 'created_at')) {
                Schema::table('objetivos', function (Blueprint $table) { $table->renameColumn('created_at', 'creado_en'); });
            }
            if (Schema::hasColumn('objetivos', 'updated_at')) {
                Schema::table('objetivos', function (Blueprint $table) { $table->renameColumn('updated_at', 'actualizado_en'); });
            }
        } else {
            if (Schema::hasColumn('objetivos', 'created_at')) {
                DB::statement("ALTER TABLE objetivos CHANGE created_at creado_en TIMESTAMP NULL DEFAULT NULL");
            }
            if (Schema::hasColumn('objetivos', 'updated_at')) {
                DB::statement("ALTER TABLE objetivos CHANGE updated_at actualizado_en TIMESTAMP NULL DEFAULT NULL");
            }
        }

        // 4. Metricas
        if (Schema::hasColumn('metricas', 'user_id')) {
            if (!$isSqlite) {
                try {
                    Schema::table('metricas', function (Blueprint $table) {
                        $table->dropForeign(['user_id']);
                    });
                } catch (\Exception $e) {}
            }
            if ($isSqlite) {
                Schema::table('metricas', function (Blueprint $table) { $table->renameColumn('user_id', 'usuario_id'); });
            } else {
                DB::statement("ALTER TABLE metricas CHANGE user_id usuario_id BIGINT UNSIGNED NOT NULL");
            }
            Schema::table('metricas', function (Blueprint $table) {
                $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            });
        }
        if ($isSqlite) {
            if (Schema::hasColumn('metricas', 'created_at')) {
                Schema::table('metricas', function (Blueprint $table) { $table->renameColumn('created_at', 'creado_en'); });
            }
            if (Schema::hasColumn('metricas', 'updated_at')) {
                Schema::table('metricas', function (Blueprint $table) { $table->renameColumn('updated_at', 'actualizado_en'); });
            }
        } else {
            if (Schema::hasColumn('metricas', 'created_at')) {
                DB::statement("ALTER TABLE metricas CHANGE created_at creado_en TIMESTAMP NULL DEFAULT NULL");
            }
            if (Schema::hasColumn('metricas', 'updated_at')) {
                DB::statement("ALTER TABLE metricas CHANGE updated_at actualizado_en TIMESTAMP NULL DEFAULT NULL");
            }
        }

        // 5. Rename logro_user table and its columns
        if (Schema::hasTable('logro_user')) {
            Schema::rename('logro_user', 'logro_usuario');
        }
        
        if (Schema::hasTable('logro_usuario') && Schema::hasColumn('logro_usuario', 'user_id')) {
            if (!$isSqlite) {
                Schema::table('logro_usuario', function (Blueprint $table) {
                    try {
                        $table->dropForeign('logro_user_user_id_foreign');
                    } catch (\Exception $e) {}
                    try {
                        $table->dropForeign('logro_user_logro_id_foreign');
                    } catch (\Exception $e) {}
                });
            }
            if ($isSqlite) {
                Schema::table('logro_usuario', function (Blueprint $table) { $table->renameColumn('user_id', 'usuario_id'); });
            } else {
                DB::statement("ALTER TABLE logro_usuario CHANGE user_id usuario_id BIGINT UNSIGNED NOT NULL");
            }
            
            Schema::table('logro_usuario', function (Blueprint $table) use ($isSqlite) {
                if (!$isSqlite) {
                    try {
                        $table->dropUnique('logro_user_user_id_logro_id_unique');
                    } catch (\Exception $e) {}
                }
                
                $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
                $table->foreign('logro_id')->references('id')->on('logros')->onDelete('cascade');
                
                // Re-create unique index
                $table->unique(['usuario_id', 'logro_id']);
            });
        }
        if (Schema::hasTable('logro_usuario')) {
            if ($isSqlite) {
                if (Schema::hasColumn('logro_usuario', 'created_at')) {
                    Schema::table('logro_usuario', function (Blueprint $table) { $table->renameColumn('created_at', 'creado_en'); });
                }
                if (Schema::hasColumn('logro_usuario', 'updated_at')) {
                    Schema::table('logro_usuario', function (Blueprint $table) { $table->renameColumn('updated_at', 'actualizado_en'); });
                }
            } else {
                if (Schema::hasColumn('logro_usuario', 'created_at')) {
                    DB::statement("ALTER TABLE logro_usuario CHANGE created_at creado_en TIMESTAMP NULL DEFAULT NULL");
                }
                if (Schema::hasColumn('logro_usuario', 'updated_at')) {
                    DB::statement("ALTER TABLE logro_usuario CHANGE updated_at actualizado_en TIMESTAMP NULL DEFAULT NULL");
                }
            }
        }

        // 6. Ejercicios
        if ($isSqlite) {
            if (Schema::hasColumn('ejercicios', 'created_at')) {
                Schema::table('ejercicios', function (Blueprint $table) { $table->renameColumn('created_at', 'creado_en'); });
            }
            if (Schema::hasColumn('ejercicios', 'updated_at')) {
                Schema::table('ejercicios', function (Blueprint $table) { $table->renameColumn('updated_at', 'actualizado_en'); });
            }
        } else {
            if (Schema::hasColumn('ejercicios', 'created_at')) {
                DB::statement("ALTER TABLE ejercicios CHANGE created_at creado_en TIMESTAMP NULL DEFAULT NULL");
            }
            if (Schema::hasColumn('ejercicios', 'updated_at')) {
                DB::statement("ALTER TABLE ejercicios CHANGE updated_at actualizado_en TIMESTAMP NULL DEFAULT NULL");
            }
        }
        
        // 7. Entrenamiento Detalles
        if ($isSqlite) {
            if (Schema::hasColumn('entrenamiento_detalles', 'created_at')) {
                Schema::table('entrenamiento_detalles', function (Blueprint $table) { $table->renameColumn('created_at', 'creado_en'); });
            }
            if (Schema::hasColumn('entrenamiento_detalles', 'updated_at')) {
                Schema::table('entrenamiento_detalles', function (Blueprint $table) { $table->renameColumn('updated_at', 'actualizado_en'); });
            }
        } else {
            if (Schema::hasColumn('entrenamiento_detalles', 'created_at')) {
                DB::statement("ALTER TABLE entrenamiento_detalles CHANGE created_at creado_en TIMESTAMP NULL DEFAULT NULL");
            }
            if (Schema::hasColumn('entrenamiento_detalles', 'updated_at')) {
                DB::statement("ALTER TABLE entrenamiento_detalles CHANGE updated_at actualizado_en TIMESTAMP NULL DEFAULT NULL");
            }
        }

        // 8. Logros
        if ($isSqlite) {
            if (Schema::hasColumn('logros', 'created_at')) {
                Schema::table('logros', function (Blueprint $table) { $table->renameColumn('created_at', 'creado_en'); });
            }
            if (Schema::hasColumn('logros', 'updated_at')) {
                Schema::table('logros', function (Blueprint $table) { $table->renameColumn('updated_at', 'actualizado_en'); });
            }
        } else {
            if (Schema::hasColumn('logros', 'created_at')) {
                DB::statement("ALTER TABLE logros CHANGE created_at creado_en TIMESTAMP NULL DEFAULT NULL");
            }
            if (Schema::hasColumn('logros', 'updated_at')) {
                DB::statement("ALTER TABLE logros CHANGE updated_at actualizado_en TIMESTAMP NULL DEFAULT NULL");
            }
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
