<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objetivo extends Model
{
    use HasFactory;

    protected $guarded = [];

    // La base de datos original usa fecha_creacion, no created_at
    public $timestamps = false;

    /**
     * Get the user that owns the objective.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
