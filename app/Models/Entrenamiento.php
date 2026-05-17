<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrenamiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'fecha',
        'tipo',
        'duracion_minutos',
        'calorias_estimadas',
        'notas',
    ];
    public $timestamps = false; // Actually, wait, it has created_at/updated_at renamed, but timestamps are false here anyway? If it is false, I don't need CREATED_AT.

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
                    ->withTimestamps();
    }
}
