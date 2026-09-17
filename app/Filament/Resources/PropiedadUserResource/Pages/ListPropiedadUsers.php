<?php

namespace App\Filament\Resources\PropiedadUserResource\Pages;

use App\Filament\Resources\PropiedadUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPropiedadUsers extends ListRecords
{
    protected static string $resource = PropiedadUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
