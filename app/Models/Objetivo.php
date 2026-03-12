<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objetivo extends Model
{
    use HasFactory;

    protected $guarded = [];

    // The new database uses standard created_at and updated_at
    // public $timestamps = true; (default)

    /**
     * Get the user that owns the objective.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
