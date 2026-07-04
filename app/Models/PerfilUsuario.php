<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilUsuario extends Model
{
    use HasFactory;

    protected $table = 'perfiles_usuario';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'usuario_id',
        'apellidos',
        'biografia',
        'genero',
        'edad',
        'altura',
        'foto',
        'nivel_actividad',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
