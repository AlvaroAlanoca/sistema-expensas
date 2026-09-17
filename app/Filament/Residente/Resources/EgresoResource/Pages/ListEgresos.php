<?php

namespace App\Filament\Residente\Resources\EgresoResource\Pages;

use App\Filament\Residente\Resources\EgresoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEgresos extends ListRecords
{
    protected static string $resource = EgresoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
