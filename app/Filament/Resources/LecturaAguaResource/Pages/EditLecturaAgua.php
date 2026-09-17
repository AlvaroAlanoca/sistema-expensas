<?php

namespace App\Filament\Resources\LecturaAguaResource\Pages;

use App\Filament\Resources\LecturaAguaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLecturaAgua extends EditRecord
{
    protected static string $resource = LecturaAguaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
