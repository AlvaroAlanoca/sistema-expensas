<?php

namespace App\Filament\Resources\PropiedadUserResource\Pages;

use App\Filament\Resources\PropiedadUserResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePropiedadUser extends CreateRecord
{
    protected static string $resource = PropiedadUserResource::class;

    // El "hook" dispara una fracción de segundo después de guardar
    protected function afterCreate(): void
    {
        // crear exitosamente
        $asignacion = $this->record;
        
        // tabla de propiedades y cambiamos su estado a Ocupado
        if ($asignacion->propiedad) {
            $asignacion->propiedad->update([
                'estado_ocupacion' => 'Ocupado'
            ]);
        }
    }
}