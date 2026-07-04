<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEntrenamiento extends Model
{
    use HasFactory;

    protected $table = 'tipos_entrenamiento';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = ['nombre', 'icono'];
}
