<?php

namespace App\Models;
use App\Models\DetallePago;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Observers\PagoObserver; // 1. Importar el observador
use Illuminate\Database\Eloquent\Attributes\ObservedBy; // 2. Importar el atributo
class Pago extends Model
{
    protected $guarded = [];
/*    protected $fillable = [
        'user_id', 
        'expensa_id', 
        'metodo_pago_id', 
        'caja_chica_id', 
        'monto', 
        'fecha_pago', 
        'estado'
    ];*/
public function detalles(): HasMany
    {
        return $this->hasMany(DetallePago::class); // Laravel buscará 'pago_id' en la tabla detalle_pagos
    }
    public function user() { return $this->belongsTo(User::class); }
    public function expensa() { return $this->belongsTo(Expensa::class); }
    public function metodoPago() { return $this->belongsTo(MetodoPago::class); }
    public function cajaChica() { return $this->belongsTo(CajaChica::class); }
}