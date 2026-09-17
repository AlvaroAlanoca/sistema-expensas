<?php

namespace App\Filament\Resources\ExpensaResource\Pages;

use App\Filament\Resources\ExpensaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExpensa extends EditRecord
{
    protected static string $resource = ExpensaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
