<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ejercicio extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function entrenamientos()
    {
        return $this->belongsToMany(Entrenamiento::class, 'entrenamiento_detalles')
                    ->withPivot(['series', 'repeticiones', 'carga_kg'])
                    ->withTimestamps();
    }
}
