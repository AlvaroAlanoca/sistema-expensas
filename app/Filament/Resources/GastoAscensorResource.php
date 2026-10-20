<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GastoAscensorResource\Pages;
use App\Filament\Resources\GastoAscensorResource\RelationManagers;
use App\Models\GastoAscensor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Carbon\Carbon;

class GastoAscensorResource extends Resource
{
    protected static ?string $model = GastoAscensor::class;

protected static ?string $navigationGroup = 'Finanzas y Pagos';
    protected static ?string $navigationLabel = 'Gastos de Ascensor';
    protected static ?string $pluralModelLabel = 'Gastos de Ascensor';
    protected static ?string $navigationIcon = 'heroicon-o-bars-arrow-up';

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Section::make('Registro de Factura / Recibo de Ascensor')
                ->schema([
                    TextInput::make('mes_gestion')
                        ->label('Mes de Gestión')
                        ->default(ucfirst(Carbon::now()->translatedFormat('F Y')))
                        ->required(),
                    
                    Select::make('tipo')
                        ->label('Tipo de Gasto')
                        ->options([
                            'Mantenimiento' => 'Mantenimiento de Rutina',
                            'Reparación' => 'Reparación / Repuestos',
                            'Reserva' => 'Fondo de Reserva',
                        ])
                        ->required(),

                    TextInput::make('monto_total')
                        ->label('Monto Total Facturado')
                        ->numeric()
                        ->prefix('Bs') 
                        ->required(),

                    TextInput::make('concepto')
                        ->label('Detalle del trabajo realizado')
                        ->placeholder('Ej: Cambio de placa electrónica piso 4')
                        ->required()
                        ->columnSpanFull(),
                ])->columns(3),
        ]);
}
    public static function table(Table $table): Table
    {
        return $table
->columns([
            TextColumn::make('mes_gestion')->label('Mes')->searchable(),
            TextColumn::make('tipo')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Mantenimiento' => 'info',
                    'Reparación' => 'danger',
                    'Reserva' => 'warning',
                    default => 'gray',
                }),
            TextColumn::make('concepto')->label('Detalle'),
            TextColumn::make('monto_total')->label('Costo Total')->money('USD'), // Ajusta USD por tu moneda local
            TextColumn::make('created_at')->label('Fecha Registro')->date('d/m/Y'),
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
            'index' => Pages\ListGastoAscensors::route('/'),
            'create' => Pages\CreateGastoAscensor::route('/create'),
            'edit' => Pages\EditGastoAscensor::route('/{record}/edit'),
        ];
    }
}
