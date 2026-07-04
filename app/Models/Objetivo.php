<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Objetivo extends Model
{
    use HasFactory, SoftDeletes;

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';
    const DELETED_AT = 'eliminado_en';

    protected $fillable = [
        'usuario_id',
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
     * Obtener el usuario al que pertenece el objetivo.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}

