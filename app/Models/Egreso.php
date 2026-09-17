<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- Muy importante
use App\Models\CajaChica;
class Egreso extends Model
{
    use HasFactory;

protected $guarded = [];
   /* protected $fillable = [
        'caja_chica_id',
        'monto',
        'estado',
    ];*/

    protected static function booted()
    {
        // Resta el dinero del total general al guardar un egreso
        static::created(function ($egreso) {
            $caja = CajaChica::find($egreso->caja_chica_id);
            if ($caja) {
                $caja->decrement('total', $egreso->monto);
            }
        });
    }
    public function cajaChica(): BelongsTo
    {
        return $this->belongsTo(CajaChica::class, 'caja_chica_id');
    }
}