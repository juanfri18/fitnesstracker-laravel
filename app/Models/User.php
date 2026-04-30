<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'usuarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the trainings for the user.
     */
    public function entrenamientos()
    {
        return $this->hasMany(Entrenamiento::class);
    }

    /**
     * Get the objectives for the user.
     */
    public function objetivos()
    {
        return $this->hasMany(Objetivo::class);
    }

    /**
     * Get the metrics for the user.
     */
    public function metricas()
    {
        return $this->hasMany(Metrica::class, 'user_id');
    }

    /**
     * Get the achievements for the user.
     */
    public function logros()
    {
        return $this->belongsToMany(Logro::class, 'logro_user')->withTimestamps();
    }

    /**
     * Calcula la racha actual de días consecutivos entrenando.
     */
    public function calcularRacha()
    {
        $fechas = $this->entrenamientos()
                       ->select('fecha')
                       ->orderBy('fecha', 'desc')
                       ->distinct()
                       ->pluck('fecha')
                       ->map(function ($f) {
                           return \Carbon\Carbon::parse($f)->startOfDay();
                       });

        if ($fechas->isEmpty()) {
            return 0;
        }

        $racha = 0;
        $hoy = \Carbon\Carbon::today();
        $ayer = \Carbon\Carbon::yesterday();

        $primeraFecha = clone $fechas->first();

        // Si el último entrenamiento es anterior a ayer, la racha está rota (0)
        // Se utiliza lt (less than) para tolerar fechas futuras o de otras zonas horarias.
        if ($primeraFecha->lt($ayer)) {
            return 0;
        }

        $fechaEsperada = $primeraFecha;

        foreach ($fechas as $fecha) {
            if ($fecha->eq($fechaEsperada)) {
                $racha++;
                $fechaEsperada->subDay();
            } else {
                break; // Se rompió la racha hacia atrás
            }
        }

        return $racha;
    }
}
