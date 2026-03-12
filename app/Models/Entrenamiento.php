<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrenamiento extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    /**
     * Get the user that owns the training.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the details for the training (strength exercises etc).
     */
    public function detalles()
    {
        return $this->hasMany(EntrenamientoDetalle::class);
    }

    /**
     * Get the exercises for this training.
     */
    public function ejercicios()
    {
        return $this->belongsToMany(Ejercicio::class, 'entrenamiento_detalles')
                    ->withPivot(['series', 'repeticiones', 'carga_kg'])
                    ->withTimestamps();
    }
}
