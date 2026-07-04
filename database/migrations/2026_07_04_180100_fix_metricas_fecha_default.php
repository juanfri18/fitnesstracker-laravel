<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar las migraciones.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE metricas MODIFY COLUMN fecha_registro DATE DEFAULT (CURRENT_DATE)");
        }
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE metricas MODIFY COLUMN fecha_registro DATE DEFAULT NULL");
        }
    }
};
