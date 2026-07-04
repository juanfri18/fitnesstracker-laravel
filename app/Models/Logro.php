<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logro extends Model
{
    use HasFactory;

    // Constantes de criterios de logros (#14)
    public const CRITERIO_PRIMER_ENTRENO = 'primer_entreno';
    public const CRITERIO_5_SESIONES_FUERZA = '5_sesiones_fuerza';
    public const CRITERIO_CARRERA_10KM = 'carrera_10km';
    public const CRITERIO_1000_MINUTOS = '1000_minutos';
    public const CRITERIO_RACHA_3_DIAS = 'racha_3_dias';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'criterio',
        'puntos'
    ];

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'logro_usuario', 'logro_id', 'usuario_id')->withTimestamps('creado_en', 'actualizado_en');
    }
}
