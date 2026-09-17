<?php

namespace App\Filament\Residente\Resources;

use App\Filament\Residente\Resources\EgresoResource\Pages;
use App\Models\Egreso;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EgresoResource extends Resource
{
    protected static ?string $model = Egreso::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Gastos del Edificio';
    protected static ?string $pluralModelLabel = 'Gastos Transparentes';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fecha_egreso')
                    ->label('Fecha')
                    ->date('F Y') // Muestra el Mes y Año
                    ->sortable(),
                Tables\Columns\TextColumn::make('descripcion') // Asumiendo que tienes un campo de descripción o concepto
                    ->label('Concepto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('monto')
                    ->label('Monto')
                    ->money('BOB', true)
                    ->sortable(),
            ])
            ->defaultSort('fecha_egreso', 'desc')
            ->actions([]) // Sin acciones de edición
            ->bulkActions([]); // Sin borrado
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEgresos::route('/'),
        ];
    }
}