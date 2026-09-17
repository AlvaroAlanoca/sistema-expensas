<?php

namespace App\Filament\Resources\LecturaGasResource\Pages;

use App\Filament\Resources\LecturaGasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLecturaGas extends EditRecord
{
    protected static string $resource = LecturaGasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
