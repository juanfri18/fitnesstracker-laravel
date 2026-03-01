<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntrenamientoDetalle extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the training session that owns this detail.
     */
    public function entrenamiento()
    {
        return $this->belongsTo(Entrenamiento::class);
    }
}
