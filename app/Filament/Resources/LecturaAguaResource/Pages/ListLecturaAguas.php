<?php

namespace App\Filament\Resources\LecturaAguaResource\Pages;

use App\Filament\Resources\LecturaAguaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLecturaAguas extends ListRecords
{
    protected static string $resource = LecturaAguaResource::class;

protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('imprimir')
                ->label('Imprimir Reporte')
                ->icon('heroicon-o-document-text')
                ->color('info')
                ->url(route('imprimir.agua'))
                ->openUrlInNewTab(),
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
