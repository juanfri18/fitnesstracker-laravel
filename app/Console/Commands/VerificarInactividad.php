<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VerificarInactividad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fitness:check-inactividad';
    protected $description = 'Alerta a los usuarios desconectados 3 días o más.';

    public function handle()
    {
        $users = \App\Models\User::all();
        $threeDaysAgo = now()->subDays(3);

        foreach ($users as $user) {
            // --- GESTIÓN DE RACHA (STREAK) ---
            // Si tiene racha, pero su última actividad fue antes de ayer, la pierde.
            if ($user->racha_actual > 0) {
                if (!$user->ultima_actividad_fecha || \Carbon\Carbon::parse($user->ultima_actividad_fecha)->startOfDay() < now()->subDays(1)->startOfDay()) {
                    $user->racha_actual = 0;
                    $user->save();
                }
            }

            $lastEntrenamiento = $user->entrenamientos()->orderBy('fecha', 'desc')->first();

            // Si nunca entrenó, o si su último entreno fue hace más de 3 días
            if (!$lastEntrenamiento || $lastEntrenamiento->fecha < $threeDaysAgo) {
                $user->notify(new \App\Notifications\RecordatorioEntrenamiento());
            }
        }

        $this->info('Notificaciones enviadas a usuarios inactivos.');
    }
}
