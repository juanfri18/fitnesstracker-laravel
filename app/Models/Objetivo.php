<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objetivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tipo_objetivo',
        'valor_objetivo',
        'progreso',
        'fecha_inicio',
        'fecha_limite',
        'estado',
    ];

    // The new database uses standard created_at and updated_at
    // public $timestamps = true; (default)

    /**
     * Devuelve el estado real del objetivo, teniendo en cuenta si ha caducado.
     * Si la fecha límite ha pasado y no está completado → "caducado".
     */
    public function getEstadoRealAttribute()
    {
        if ($this->estado === 'completado') {
            return 'completado';
        }

        if ($this->fecha_limite && \Carbon\Carbon::parse($this->fecha_limite)->lt(now()->startOfDay())) {
            return 'caducado';
        }

        return 'en_progreso';
    }

    /**
     * Get the user that owns the objective.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

