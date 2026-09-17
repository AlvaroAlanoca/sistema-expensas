<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IngresoCajaChicaResource\Pages;
use App\Filament\Resources\IngresoCajaChicaResource\RelationManagers;
use App\Models\IngresoCajaChica;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use App\Models\CajaChica;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;

class IngresoCajaChicaResource extends Resource
{
    protected static ?string $model = IngresoCajaChica::class;

protected static ?string $navigationGroup = 'Módulo de Caja';
    protected static ?string $navigationLabel = 'Ingresos a Caja';
    protected static ?string $pluralModelLabel = 'Ingresos a Caja';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-on-rectangle';

    public static function form(Form $form): Form
    {
        return $form
->schema([
            Select::make('caja_chica_id')
                ->label('Caja Destino')
                ->options(CajaChica::pluck('id', 'id'))
                ->required(),
            TextInput::make('concepto')
                ->label('Motivo del Ingreso')
                ->placeholder('Ej: Apertura de Caja / Fondo Inicial')
                ->required(),
            TextInput::make('monto_ingreso')
                ->label('Monto')
                ->numeric()
                ->required(),
            DatePicker::make('fecha_ingreso')
                ->label('Fecha del Ingreso')
                ->default(now())
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Numero')->sortable(),
                TextColumn::make('monto_ingreso')->label('Monto')->sortable(),
                TextColumn::make('fecha_ingreso')->label('Fecha Ingreso')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIngresoCajaChicas::route('/'),
            'create' => Pages\CreateIngresoCajaChica::route('/create'),
            'edit' => Pages\EditIngresoCajaChica::route('/{record}/edit'),
        ];
    }
}
