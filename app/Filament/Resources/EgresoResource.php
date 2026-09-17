<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EgresoResource\Pages;
use App\Filament\Resources\EgresoResource\RelationManagers;
use App\Models\Egreso;
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

class EgresoResource extends Resource
{
    protected static ?string $model = Egreso::class;

protected static ?string $navigationGroup = 'Finanzas y Pagos';
    protected static ?string $navigationLabel = 'Egresos Generales';
    protected static ?string $pluralModelLabel = 'Egresos';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-down';

    public static function form(Form $form): Form
    {
return $form->schema([
    Select::make('caja_chica_id')
        ->relationship('cajaChica', 'id')
        ->label('Caja Chica (Origen)')
        ->required(),
    TextInput::make('monto')
        ->numeric()
        ->required()
        ->prefix('$'),
    Select::make('estado')
        ->options([1 => 'Aprobado', 0 => 'Anulado'])
        ->default(1)
        ->required(),
]);
    }

    public static function table(Table $table): Table
    {
return $table->columns([
    TextColumn::make('cajaChica.id')->label('Caja Origen'),
    TextColumn::make('monto')->money('USD')->sortable(),
    TextColumn::make('created_at')->date('d/m/Y')->label('Fecha'),
    TextColumn::make('estado')
        ->badge()
        ->color(fn (string $state): string => $state == 1 ? 'success' : 'danger')
        ->formatStateUsing(fn (string $state): string => $state == 1 ? 'Aprobado' : 'Anulado'),
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
            'index' => Pages\ListEgresos::route('/'),
            'create' => Pages\CreateEgreso::route('/create'),
            'edit' => Pages\EditEgreso::route('/{record}/edit'),
        ];
    }
}
