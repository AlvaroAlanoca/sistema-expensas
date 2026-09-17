<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LecturaAgua extends Model
{
    protected $table = 'lectura_aguas';
    
    protected $guarded = []; // Evita el error MassAssignmentException

    public function propiedad(): BelongsTo
    {
        return $this->belongsTo(Propiedad::class);
    }
}