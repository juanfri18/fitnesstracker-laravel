<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntrenamientoDetalle extends Model
{
    use HasFactory, SoftDeletes;

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';
    const DELETED_AT = 'eliminado_en';

    protected $fillable = ['entrenamiento_id', 'ejercicio_id', 'series', 'repeticiones', 'carga_kg'];

    /**
     * Obtener la sesión de entrenamiento a la que pertenece este detalle.
     */
    public function entrenamiento()
    {
        return $this->belongsTo(Entrenamiento::class);
    }

    /**
     * Obtener el ejercicio asociado a este detalle.
     */
    public function ejercicio()
    {
        return $this->belongsTo(Ejercicio::class);
    }
}
