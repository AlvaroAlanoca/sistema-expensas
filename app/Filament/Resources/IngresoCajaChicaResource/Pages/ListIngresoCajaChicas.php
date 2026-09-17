<?php

namespace App\Filament\Resources\IngresoCajaChicaResource\Pages;

use App\Filament\Resources\IngresoCajaChicaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIngresoCajaChicas extends ListRecords
{
    protected static string $resource = IngresoCajaChicaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
