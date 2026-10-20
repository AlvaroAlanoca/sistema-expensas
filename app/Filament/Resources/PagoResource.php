<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PagoResource\Pages;
use App\Filament\Resources\PagoResource\RelationManagers;
use App\Models\Pago;
use App\Models\Expensa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;

class PagoResource extends Resource
{
    protected static ?string $model = Pago::class;

protected static ?string $navigationGroup = 'Finanzas y Pagos';
    protected static ?string $navigationLabel = 'Pagos Recibidos';
    protected static ?string $pluralModelLabel = 'Pagos';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // 1. SECCIÓN PRINCIPAL: Datos del recibo
                Section::make('Datos Generales del Recibo')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Copropietario (Usuario)')
                            ->searchable()
                            ->required(),
                            
                        Select::make('metodo_pago_id')
                            ->relationship('metodoPago', 'nombre')
                            ->label('Método de Pago')
                            ->required(),
                            
                        Select::make('caja_chica_id')
                            ->relationship('cajaChica', 'total') 
                            ->label('Destino (Caja Chica)')
                            ->required(),
                            
                        TextInput::make('monto')
                            ->label('Monto Total del Recibo')
                            ->required()
                            ->numeric()
                            ->prefix('$'),
                            
                        DatePicker::make('fecha_pago')
                            ->default(now())
                            ->required(),
                            
                        Select::make('estado')
                            ->options([
                                1 => 'Aprobado',
                                0 => 'Rechazado/Anulado',
                            ])
                            ->default(1)
                            ->required(),
                    ])->columns(2),

                // 2. SECCIÓN DETALLE: El "carrito" de cobros
                Section::make('Detalle de los Cobros')
                    ->schema([
                        Repeater::make('detalles')
                            ->relationship() // Requiere la función detalles() en Pago.php
                            ->schema([
                                Select::make('concepto')
                                    ->options([
                                        'Expensa' => 'Pago de Expensa',
                                        'Agua' => 'Servicio de Agua',
                                        'Otro' => 'Otros',
                                    ])
                                    ->required()
                                    ->live(),

                                Select::make('expensa_id')
                                    ->relationship('expensa', 'id')
                                    ->getOptionLabelFromRecordUsing(fn (Expensa $record) => "Expensa #{$record->id} - Vence: " . \Carbon\Carbon::parse($record->fecha_vencimiento)->format('d/m/Y'))
                                    ->label('Expensa a Pagar')
                                    ->searchable()
                                    ->visible(fn (callable $get) => $get('concepto') === 'Expensa'),
                                    
                                TextInput::make('monto')
                                    ->label('Monto del ítem')
                                    ->required()
                                    ->numeric()
                                    ->prefix('$'),
                            ])
                            ->columns(3)
                            ->addActionLabel('Agregar ítem al pago')
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Copropietario')
                    ->searchable()
                    ->sortable(),
              
                TextColumn::make('monto')
                    ->label('Total Pagado')
                    ->money('USD')
                    ->sortable(),
                    
                TextColumn::make('fecha_pago')
                    ->date('d/m/Y')
                    ->sortable(),
                    
                TextColumn::make('metodoPago.nombre')
                    ->label('Método'),
                    
                TextColumn::make('estado')
                    ->formatStateUsing(fn (string $state): string => $state == 1 ? 'Aprobado' : 'Anulado')
                    ->badge()
                    ->color(fn (string $state): string => $state == 1 ? 'success' : 'danger'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListPagos::route('/'),
            'create' => Pages\CreatePago::route('/create'),
            'edit' => Pages\EditPago::route('/{record}/edit'),
        ];
    }
}