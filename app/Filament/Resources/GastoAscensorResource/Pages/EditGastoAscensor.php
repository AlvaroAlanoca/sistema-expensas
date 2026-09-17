<?php

namespace App\Filament\Resources\GastoAscensorResource\Pages;

use App\Filament\Resources\GastoAscensorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGastoAscensor extends EditRecord
{
    protected static string $resource = GastoAscensorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
