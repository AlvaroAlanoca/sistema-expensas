<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\CajaChica;
class IngresoCajaChica extends Model
{
protected $table = 'ingresos_caja_chica';
protected $guarded = [];
    /*protected $fillable = [
        'caja_chica_id',
        'pago_id',
        'monto_ingreso',
        'fecha_ingreso',
        'estado',
    ];*/

    public function cajaChica(): BelongsTo
    {
        return $this->belongsTo(CajaChica::class);
    }

    public function pago(): BelongsTo
    {
        return $this->belongsTo(Pago::class);
    }
    protected static function booted()
    {
        // Suma el dinero al total general de la caja
        static::created(function ($ingreso) {
            $caja = CajaChica::find($ingreso->caja_chica_id);
            if ($caja) {
                $caja->increment('total', $ingreso->monto_ingreso);
            }
        });
    }
}