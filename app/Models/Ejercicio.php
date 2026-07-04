<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ejercicio extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'grupo_muscular'];

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    public function entrenamientos()
    {
        return $this->belongsToMany(Entrenamiento::class, 'entrenamiento_detalles')
                    ->withPivot(['series', 'repeticiones', 'carga_kg'])
                    ->withTimestamps('creado_en', 'actualizado_en');
    }
}
