<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Observers\DetallePagoObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy; 

#[ObservedBy([DetallePagoObserver::class])] 
class DetallePago extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'pago_id',
        'lectura_agua_id',
        'expensa_id',
        'servicio_id',
        'concepto',
        'monto'
    ];

    public function expensa(): BelongsTo
    {
        return $this->belongsTo(Expensa::class);
    }
}