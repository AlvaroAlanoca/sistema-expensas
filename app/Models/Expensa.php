<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expensa extends Model
{
protected $fillable = [
    'propiedad_id',
    'monto',
    'monto_pagado', 
    'monto_pendiente', 
    'monto_ahorro',
    'mes_gestion', 
    'fecha_vencimiento',
    'estado',
    'status',
    'cuota_ordinaria',
    'cuota_extraordinaria',
    'fondo_mantenimiento',
    'fondo_ascensor',
    'multas'
];

    public function propiedad()
    {
        return $this->belongsTo(Propiedad::class);
    }
}