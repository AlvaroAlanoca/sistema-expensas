<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    // Le decimos a Laravel el nombre exacto de la tabla
    protected $table = 'metodos_pagos';

    protected $fillable = [
        'nombre',
        'estado',
    ];
}