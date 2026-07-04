<?php

namespace App\Observers;

use App\Models\Entrenamiento;
use App\Models\EstadisticaUsuario;

class EntrenamientoObserver
{
    /**
     * Handle the Entrenamiento "created" event.
     */
    public function created(Entrenamiento $entrenamiento): void
    {
        $stats = EstadisticaUsuario::firstOrCreate(
            ['usuario_id' => $entrenamiento->usuario_id]
        );

        $stats->increment('total_entrenamientos');
        $stats->increment('total_calorias', $entrenamiento->calorias_estimadas ?? 0);
        $stats->increment('total_minutos', $entrenamiento->duracion_minutos ?? 0);
    }

    /**
     * Handle the Entrenamiento "deleted" event.
     */
    public function deleted(Entrenamiento $entrenamiento): void
    {
        $stats = EstadisticaUsuario::where('usuario_id', $entrenamiento->usuario_id)->first();
        
        if ($stats) {
            $stats->decrement('total_entrenamientos');
            $stats->decrement('total_calorias', $entrenamiento->calorias_estimadas ?? 0);
            $stats->decrement('total_minutos', $entrenamiento->duracion_minutos ?? 0);
        }
    }
}
