<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entrenamiento extends Model
{
    use HasFactory, SoftDeletes;

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';
    const DELETED_AT = 'eliminado_en';

    protected $fillable = [
        'usuario_id',
        'fecha',
        'tipo',
        'duracion_minutos',
        'calorias_estimadas',
        'notas',
    ];

    /**
     * Obtener el usuario al que pertenece el entrenamiento.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Obtener los detalles del entrenamiento (ejercicios de fuerza, etc.).
     */
    public function detalles()
    {
        return $this->hasMany(EntrenamientoDetalle::class);
    }

    /**
     * Obtener los ejercicios de este entrenamiento.
     */
    public function ejercicios()
    {
        return $this->belongsToMany(Ejercicio::class, 'entrenamiento_detalles')
                    ->withPivot(['series', 'repeticiones', 'carga_kg'])
                    ->withTimestamps('creado_en', 'actualizado_en');
    }
}
