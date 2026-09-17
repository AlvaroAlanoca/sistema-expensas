<?php

namespace App\Filament\Resources\CajaChicaResource\Pages;

use App\Filament\Resources\CajaChicaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCajaChica extends EditRecord
{
    protected static string $resource = CajaChicaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
