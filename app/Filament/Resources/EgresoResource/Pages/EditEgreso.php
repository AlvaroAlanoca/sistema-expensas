<?php

namespace App\Filament\Resources\EgresoResource\Pages;

use App\Filament\Resources\EgresoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEgreso extends EditRecord
{
    protected static string $resource = EgresoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
