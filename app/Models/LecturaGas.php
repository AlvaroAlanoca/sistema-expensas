<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LecturaGas extends Model
{
    protected $table = 'lecturas_gas';
protected $guarded = [];

public function propiedad() { 
    return $this->belongsTo(Propiedad::class); 
}
}
