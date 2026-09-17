<?php

namespace App\Filament\Resources\IngresoCajaChicaResource\Pages;

use App\Filament\Resources\IngresoCajaChicaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIngresoCajaChica extends EditRecord
{
    protected static string $resource = IngresoCajaChicaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
