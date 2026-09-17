<?php

namespace App\Observers;

use App\Models\Pago;
use App\Models\CajaChica;
use App\Models\IngresoCajaChica; // Asegúrate de tener este modelo creado

class PagoObserver
{
    /**
     * Se ejecuta automáticamente al guardar el recibo principal (Pago)
     */
    public function created(Pago $pago): void
    {
        // 1. Registrar la trazabilidad del dinero en el historial
        IngresoCajaChica::create([
            'caja_chica_id' => $pago->caja_chica_id,
            'pago_id' => $pago->id, // Conecta el ingreso con este recibo
            'monto_ingreso' => $pago->monto,
            'fecha_ingreso' => $pago->fecha_pago ?? now(),
            'estado' => 1,
        ]);

        // 2. Actualizar matemáticamente los fondos disponibles
        $caja = CajaChica::find($pago->caja_chica_id);
        
        if ($caja) {
            // Nota: Ajusta 'total' por 'saldo_actual' si así se llama en tu BD
            $caja->total += $pago->monto; 
            $caja->save();
        }
    }
}