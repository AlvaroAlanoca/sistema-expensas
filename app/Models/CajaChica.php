<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CajaChica extends Model
{
    // Le decimos a Laravel el nombre exacto de la tabla
    protected $table = 'cajas_chicas';

    protected $fillable = [
        'total',
    ];
}