<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LecturaGasResource\Pages;
use App\Filament\Resources\LecturaGasResource\RelationManagers;
use App\Models\LecturaGas;
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
use Filament\Forms\Components\Hidden;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\DB;
use App\Models\Pago;
use App\Models\DetallePago;
use App\Models\MetodoPago;
use App\Models\CajaChica;
use Filament\Notifications\Notification;
use Carbon\Carbon;
class LecturaGasResource extends Resource
{
    protected static ?string $model = LecturaGas::class;
protected static ?string $navigationGroup = 'Consumo de Servicios';
    protected static ?string $navigationLabel = 'Lecturas de Gas';
    protected static ?string $pluralModelLabel = 'Lecturas de Gas';
    protected static ?string $navigationIcon = 'heroicon-o-fire';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Registro de Medidor de Gas')
                ->schema([
                    Select::make('propiedad_id')
                        ->relationship('propiedad', 'id') // Ajusta 'id' si usas 'numero'
                        ->label('Propiedad')
                        ->searchable()
                        ->required(),

                    TextInput::make('mes_gestion')
                        ->label('Mes de Gestión')
                        ->default(ucfirst(\Carbon\Carbon::now()->locale('es')->translatedFormat('F Y')))
                        ->required(),

                    TextInput::make('lectura_anterior')
                        ->numeric()
                        ->default(0)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, Get $get) => self::calcularCobro($set, $get)),

                    TextInput::make('lectura_actual')
                        ->numeric()
                        ->default(0)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, Get $get) => self::calcularCobro($set, $get)),

                    TextInput::make('consumo_m3')
                        ->label('Consumo (m³)')
                        ->numeric()
                        ->readOnly()
                        ->extraInputAttributes(['style' => 'font-weight: bold; background-color: #f3f4f6;']),

                    TextInput::make('monto_total')
                        ->label('Total a Cobrar')
                        ->numeric()
                        ->readOnly()
                        ->extraInputAttributes(['style' => 'font-weight: bold; color: #d97706; background-color: #fef3c7;']), // Color naranja para diferenciar del agua
                        
                    Hidden::make('monto_pendiente'),
                    Hidden::make('estado')->default(2),
                ])->columns(2),
            ]);
    }

public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('propiedad.id')->label('Propiedad')->sortable(),
            TextColumn::make('mes_gestion')->label('Mes'),
            TextColumn::make('consumo_m3')->label('Consumo')->suffix(' m³'),
            TextColumn::make('monto_total')->label('Deuda')->money('USD'),
            TextColumn::make('estado')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    '1' => 'success',
                    '2' => 'danger',
                    default => 'gray',
                })
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    '1' => 'Pagado',
                    '2' => 'Pendiente',
                    default => 'Desconocido',
                }),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            
            // BOTÓN DE COBRO RÁPIDO (Adaptado para Gas)
            Action::make('registrar_pago')
                ->label('Cobrar Gas')
                ->icon('heroicon-o-fire') // Icono distinto para el gas
                ->color('warning')
                ->visible(fn ($record) => $record->estado == 2)
                ->form([
                    Select::make('metodo_pago_id')
                        ->label('Método de Pago')
                        ->options(MetodoPago::pluck('nombre', 'id'))
                        ->required(),
                        
                    Select::make('caja_chica_id')
                        ->label('Caja / Cuenta Destino')
                        ->options(function () {
                            return CajaChica::all()->mapWithKeys(function ($caja) {
                                $fecha = $caja->created_at ? $caja->created_at->format('d/m/Y') : 'Sin fecha';
                                return [$caja->id => "Caja #{$caja->id} (Apertura: {$fecha})"];
                            });
                        })
                        ->required(),
                ])
                ->action(function ($record, array $data) {
                    DB::transaction(function () use ($record, $data) {
                        $pago = Pago::create([
                            'user_id' => \Illuminate\Support\Facades\Auth::id(),
                            'metodo_pago_id' => $data['metodo_pago_id'],
                            'caja_chica_id' => $data['caja_chica_id'],
                            'monto' => $record->monto_pendiente,
                            'fecha_pago' => now(),
                            'estado' => 1,
                        ]);

                        DetallePago::create([
                            'pago_id' => $pago->id,
                            'lectura_gas_id' => $record->id, // <-- ¡Clave para conectar con la tabla correcta!
                            'concepto' => 'Consumo de Gas - ' . $record->mes_gestion,
                            'monto' => $record->monto_pendiente,
                        ]);

                        $record->update([
                            'monto_pagado' => $record->monto_pendiente,
                            'monto_pendiente' => 0,
                            'estado' => 1,
                        ]);
                    });

                    Notification::make()
                        ->title('Pago de gas registrado con éxito')
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('Registrar Pago de Gas'),
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
            'index' => Pages\ListLecturaGases::route('/'),
            'create' => Pages\CreateLecturaGas::route('/create'),
            'edit' => Pages\EditLecturaGas::route('/{record}/edit'),
        ];
    }
    public static function calcularCobro(Set $set, Get $get): void
{
    $anterior = (float) $get('lectura_anterior') ?: 0;
    $actual = (float) $get('lectura_actual') ?: 0;
    
    // TARIFA FIJA DE GAS: Cambia este 3.50 por el costo real por metro cúbico en tu edificio
    $tarifaPorM3 = 3.50; 

    $consumo = $actual - $anterior;
    $consumo = $consumo > 0 ? $consumo : 0;
    $total = $consumo * $tarifaPorM3;

    $set('consumo_m3', $consumo);
    $set('monto_total', $total);
    $set('monto_pendiente', $total);
}
}
