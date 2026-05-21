<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usuario;
use Carbon\Carbon;

class VerificarInactividad extends Command
{
    protected $signature = 'fitness:check-inactividad';
    protected $description = 'Alerta a los usuarios inactivos 3+ días y resetea rachas rotas.';

    public function handle()
    {
        $threeDaysAgo = now()->subDays(3);

        Usuario::with(['entrenamientos' => function ($q) {
            $q->orderBy('fecha', 'desc')->limit(1);
        }])->chunk(100, function ($usuarios) use ($threeDaysAgo) {
            foreach ($usuarios as $usuario) {
                // Resetear racha si el último entreno fue antes de ayer
                if ($usuario->racha_actual > 0) {
                    $sinActividadReciente = !$usuario->ultima_actividad_fecha
                        || Carbon::parse($usuario->ultima_actividad_fecha)->startOfDay()
                            ->lt(now()->subDays(1)->startOfDay());

                    if ($sinActividadReciente) {
                        $usuario->racha_actual = 0;
                        $usuario->save();
                    }
                }

                $lastEntrenamiento = $usuario->entrenamientos->first();

                if (!$lastEntrenamiento || $lastEntrenamiento->fecha < $threeDaysAgo) {
                    $usuario->notify(new \App\Notifications\RecordatorioEntrenamiento());
                }
            }
        });

        $this->info('Notificaciones enviadas a usuarios inactivos.');
    }
}
