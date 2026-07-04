<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Racha extends Model
{
    use HasFactory;

    protected $table = 'rachas';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'usuario_id',
        'racha_actual',
        'mejor_racha',
        'ultima_actividad_fecha',
    ];

    protected $casts = [
        'ultima_actividad_fecha' => 'date',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
