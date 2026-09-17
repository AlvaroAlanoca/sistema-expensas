<?php

namespace App\Filament\Resources\LecturaGasResource\Pages;

use App\Filament\Resources\LecturaGasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLecturaGases extends ListRecords
{
    protected static string $resource = LecturaGasResource::class;

protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('imprimir')
                ->label('Imprimir Reporte')
                ->icon('heroicon-o-document-text')
                ->color('info')
                ->url(route('imprimir.gas'))
                ->openUrlInNewTab(),
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
