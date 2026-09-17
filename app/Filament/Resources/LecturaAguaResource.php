<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LecturaAguaResource\Pages;
use App\Filament\Resources\LecturaAguaResource\RelationManagers;
use App\Models\LecturaAgua;
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
use Filament\Forms\Get;
use Filament\Forms\Set;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Notifications\Notification;
use App\Models\DetallePago;
use App\Models\MetodoPago;
use App\Models\CajaChica;
use Illuminate\Support\Facades\DB;
use App\Models\Pago;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;

class LecturaAguaResource extends Resource
{
    protected static ?string $model = LecturaAgua::class;

protected static ?string $navigationGroup = 'Consumo de Servicios';
    protected static ?string $navigationLabel = 'Lecturas de Agua';
    protected static ?string $pluralModelLabel = 'Lecturas de Agua';
    protected static ?string $navigationIcon = 'heroicon-o-beaker';
public static function form(Form $form): Form
{
    return $form
        ->schema([
            Section::make('Registro de Medidor')
                ->schema([
                    Select::make('propiedad_id')
                        ->relationship('propiedad', 'id') // Cambia 'id' por 'numero' o 'torre' si tienes un campo descriptivo
                        ->label('Propiedad')
                        ->searchable()
                        ->required(),

                    TextInput::make('mes_gestion')
                        ->label('Mes de Gestión')
                        ->default(ucfirst(Carbon::now()->translatedFormat('F Y')))
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
                        ->readOnly() // El usuario no debe editar esto manualmente
                        ->extraInputAttributes(['style' => 'font-weight: bold; background-color: #f3f4f6;']),

                    TextInput::make('monto_total')
                        ->label('Total a Cobrar (Bs/$)')
                        ->numeric()
                        ->readOnly()
                        ->extraInputAttributes(['style' => 'font-weight: bold; color: green; background-color: #f3f4f6;']),
                        
                    // Campos ocultos para mantener la integridad de la deuda inicial
                    \Filament\Forms\Components\Hidden::make('monto_pendiente'),
                    \Filament\Forms\Components\Hidden::make('estado')->default(2),
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
            TextColumn::make('monto_total')->label('Deuda')->money('USD'), // Cambia USD por tu moneda local
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
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
Action::make('registrar_pago')
    ->label('Cobrar')
    ->icon('heroicon-o-currency-dollar')
    ->color('success')
    ->visible(fn ($record) => $record->estado == 2)
    ->form([
        Select::make('metodo_pago_id')
            ->label('Método de Pago')
            ->options(MetodoPago::pluck('nombre', 'id'))
            ->required(),
            //Selector de Caja Chica adaptado a tu estructura
        Select::make('caja_chica_id')
            ->label('Caja / Cuenta Destino')
            ->options(function () {
                // Combina el ID y la fecha para que el menú sea legible
                return \App\Models\CajaChica::all()->mapWithKeys(function ($caja) {
                    $fecha = $caja->created_at ? $caja->created_at->format('d/m/Y') : 'Sin fecha';
                    return [$caja->id => "Caja #{$caja->id} (Apertura: {$fecha})"];
                });
            })
            ->required(),
    ])
    ->action(function ($record, array $data) {
        DB::transaction(function () use ($record, $data) {
            
            // 1. Crear el recibo usando los datos del formulario
            $pago = Pago::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'metodo_pago_id' => $data['metodo_pago_id'],
                'caja_chica_id' => $data['caja_chica_id'], // <-- Usa la caja elegida por el admin
                'monto' => $record->monto_pendiente,
                'fecha_pago' => now(),
                'estado' => 1,
            ]);

            // 2. Conectar el pago con el agua
            DetallePago::create([
                'pago_id' => $pago->id,
                'lectura_agua_id' => $record->id,
                'concepto' => 'Consumo de Agua - ' . $record->mes_gestion,
                'monto' => $record->monto_pendiente,
            ]);

            // 3. Liquidar la deuda
            $record->update([
                'monto_pagado' => $record->monto_pendiente,
                'monto_pendiente' => 0,
                'estado' => 1,
            ]);
        });

        Notification::make()
            ->title('Pago registrado con éxito')
            ->success()
            ->send();
    })
    ->requiresConfirmation()
    ->modalHeading('Registrar Pago de Agua'),
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
            'index' => Pages\ListLecturaAguas::route('/'),
            'create' => Pages\CreateLecturaAgua::route('/create'),
            'edit' => Pages\EditLecturaAgua::route('/{record}/edit'),
        ];
    }

    public static function calcularCobro(Set $set, Get $get): void
{
    $anterior = (float) $get('lectura_anterior') ?: 0;
    $actual = (float) $get('lectura_actual') ?: 0;
    $tarifaPorM3 = 5.00; // PRECIO FIJO: Cámbialo según la tarifa real de tu edificio

    $consumo = $actual - $anterior;
    $consumo = $consumo > 0 ? $consumo : 0; // Evita consumos negativos si el usuario se equivoca
    $total = $consumo * $tarifaPorM3;

    $set('consumo_m3', $consumo);
    $set('monto_total', $total);
    $set('monto_pendiente', $total); // Inicia debiendo el 100% de la factura
}
}
