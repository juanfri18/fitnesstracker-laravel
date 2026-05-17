<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'usuarios';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'correo',
        'contrasena',
    ];

    /**
     * Los atributos que deben ocultarse para la serialización.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'contrasena',
        'token_recuerdo',
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'correo_verificado_en' => 'datetime',
        'contrasena' => 'hashed',
    ];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function getRememberTokenName()
    {
        return 'token_recuerdo';
    }

    /**
     * Obtener los entrenamientos del usuario.
     */
    public function entrenamientos()
    {
        return $this->hasMany(Entrenamiento::class, 'usuario_id');
    }

    /**
     * Obtener los objetivos del usuario.
     */
    public function objetivos()
    {
        return $this->hasMany(Objetivo::class, 'usuario_id');
    }

    /**
     * Obtener las métricas del usuario.
     */
    public function metricas()
    {
        return $this->hasMany(Metrica::class, 'usuario_id');
    }

    /**
     * Obtener los logros del usuario.
     */
    public function logros()
    {
        return $this->belongsToMany(Logro::class, 'logro_usuario', 'usuario_id', 'logro_id')
            ->withPivot('creado_en', 'actualizado_en') // Use custom timestamps if Laravel doesn't pick up the model's constants automatically for pivots
            ->withTimestamps();
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
