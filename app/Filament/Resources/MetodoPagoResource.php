<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MetodoPagoResource\Pages;
use App\Filament\Resources\MetodoPagoResource\RelationManagers;
use App\Models\MetodoPago;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
class MetodoPagoResource extends Resource
{
    protected static ?string $model = MetodoPago::class;

protected static ?string $navigationGroup = 'Finanzas y Pagos';
    protected static ?string $navigationLabel = 'Métodos de Pago';
    protected static ?string $pluralModelLabel = 'Métodos de Pago';
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('nombre')
                ->required()
                ->maxLength(45)
                ->label('Nombre del Método (Ej. Transferencia)'),
                
            Select::make('estado')
                ->options([
                    1 => 'Activo',
                    0 => 'Inactivo',
                ])
                ->default(1)
                ->required(),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('nombre')->searchable(),
            TextColumn::make('estado')
                ->formatStateUsing(fn (string $state): string => $state == 1 ? 'Activo' : 'Inactivo')
                ->badge()
                ->color(fn (string $state): string => $state == 1 ? 'success' : 'danger'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListMetodoPagos::route('/'),
            'create' => Pages\CreateMetodoPago::route('/create'),
            'edit' => Pages\EditMetodoPago::route('/{record}/edit'),
        ];
    }
}
