<?php

namespace App\Filament\Resources\PagoResource\Pages;

use App\Filament\Resources\PagoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Expensa; // <-- Asegúrate de importar el modelo

class CreatePago extends CreateRecord
{
    protected static string $resource = PagoResource::class;

    // Esta función se ejecuta automáticamente después de guardar el pago
    protected function afterCreate(): void
    {
        $pago = $this->record; // Obtenemos el pago recién creado

        if ($pago->estado == 1) { // Si el pago está Aprobado
            // Buscamos la expensa correspondiente
            $expensa = Expensa::find($pago->expensa_id);
            
            if ($expensa) {
                // Le cambiamos el estado a 1 (Pagado)
                $expensa->update(['estado' => 1]);
            }
        }
    }
}