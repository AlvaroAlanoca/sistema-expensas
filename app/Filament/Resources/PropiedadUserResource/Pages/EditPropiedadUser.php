<?php

namespace App\Filament\Resources\PropiedadUserResource\Pages;

use App\Filament\Resources\PropiedadUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPropiedadUser extends EditRecord
{
    protected static string $resource = PropiedadUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
