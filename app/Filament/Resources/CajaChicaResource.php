<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CajaChicaResource\Pages;
use App\Filament\Resources\CajaChicaResource\RelationManagers;
use App\Models\CajaChica;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class CajaChicaResource extends Resource
{
    protected static ?string $model = CajaChica::class;
    
    protected static ?string $navigationGroup = 'Módulo de Caja';
    protected static ?string $navigationLabel = 'Apertura de Cajas';
    protected static ?string $pluralModelLabel = 'Cajas Chicas';
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('total')
                ->required()
                ->numeric()
                ->default(0.00)
                ->prefix('$')
                ->label('Saldo Actual'),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('id')->label('Nº de Caja'),
            TextColumn::make('total')
                ->money('USD')
                ->sortable(),
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
            'index' => Pages\ListCajaChicas::route('/'),
            'create' => Pages\CreateCajaChica::route('/create'),
            'edit' => Pages\EditCajaChica::route('/{record}/edit'),
        ];
    }
}
