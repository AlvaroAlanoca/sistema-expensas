<?php

namespace App\Observers;

use App\Models\DetallePago;
use App\Models\Expensa;

class DetallePagoObserver
{
    /**
     * Este evento se dispara justo DESPUÉS de que el Repeater guarda un ítem.
     */
    public function created(DetallePago $detallePago): void
{
    if ($detallePago->concepto === 'Expensa' && $detallePago->expensa_id) {
        
        $expensa = Expensa::find($detallePago->expensa_id);
        
        if ($expensa) {
            // Cambiamos a formato snake_case: monto_pagado y monto_pendiente
            $expensa->monto_pagado += $detallePago->monto;
            $expensa->monto_pendiente = $expensa->monto - $expensa->monto_pagado;
            
            if ($expensa->monto_pendiente <= 0) {
                $expensa->estado = 1; 
                $expensa->monto_pendiente = 0; 
            }
            
            $expensa->save();
        }
    }
}
}