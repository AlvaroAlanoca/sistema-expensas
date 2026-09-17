<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpensaResource\Pages;
use App\Filament\Resources\ExpensaResource\RelationManagers;
use App\Models\Expensa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;

class ExpensaResource extends Resource
{
    protected static ?string $model = Expensa::class;

protected static ?string $navigationGroup = 'Finanzas y Pagos';
    protected static ?string $navigationLabel = 'Expensas';
    protected static ?string $pluralModelLabel = 'Expensas';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

public static function form(Form $form): Form
{
    return $form
        ->schema([
            // 1. Datos Generales
            Section::make('Datos Principales')
                ->schema([
                    Select::make('propiedad_id')
                        ->relationship('propiedad', 'id') // Cambia 'id' por 'nombre' o 'numero' si tienes esa columna en Propiedades
                        ->label('Propiedad')
                        ->searchable()
                        ->required(),
                        
                    TextInput::make('mes_gestion')
                        ->label('Mes de Gestión')
                        ->placeholder('Ej: Agosto 2026')
                        ->maxLength(255),
                        
TextInput::make('monto')
    ->label('Monto Total a Cobrar')
    ->numeric()
    ->required()
    ->prefix('$')
    ->live(onBlur: true) // Detecta cuando terminas de escribir
    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
        // Solo copia el valor a la deuda si estamos creando una expensa nueva
        if ($operation === 'create') {
            $set('monto_pendiente', $state);
        }
    }),
                    DatePicker::make('fecha_vencimiento')
                        ->required(),
                ])->columns(2),

            // 2. Control Financiero
Section::make('Saldos (Actualizados por el sistema de Pagos)')
    ->schema([
        TextInput::make('monto_pagado')
            ->label('Capital Pagado')
            ->numeric()
            ->default(0)
            ->required()
            ->readOnly() // <-- Bloquea el campo para que no se edite a mano
            ->prefix('$'),
            
        TextInput::make('monto_pendiente')
            ->label('Deuda Pendiente')
            ->numeric()
            ->default(0)
            ->required()
            ->readOnly() // <-- Bloqueado, se llena solo con el "afterStateUpdated" de arriba
            ->prefix('$'),
            
        TextInput::make('monto_ahorro')
            ->label('Fondo de Ahorro')
            ->numeric()
            ->default(0)
            ->required()
            ->readOnly() 
            ->prefix('$'),
    ])->columns(3),

            // 3. Estados
            Section::make('Control de Estados')
                ->schema([
                    Select::make('estado')
                        ->options([
                            1 => 'Pagado',
                            2 => 'Pendiente / Deuda',
                        ])
                        ->default(2) // Asume pendiente al crear
                        ->required(),
                        
                    Select::make('status')
                        ->label('Status del Registro')
                        ->options([
                            1 => 'Activo',
                            0 => 'Anulado',
                        ])
                        ->default(1)
                        ->required(),
                ])->columns(2),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            // Accedemos al nombre de la propiedad a través de la relación
            TextColumn::make('propiedad.nombre')
                ->label('Propiedad')
                ->searchable()
                ->sortable(),
                TextColumn::make('monto')
    ->label('Total a Cobrar')
    ->money('USD'),
    
TextColumn::make('monto_pagado')
    ->label('Capital Pagado')
    ->money('USD')
    ->color('success'),
    
TextColumn::make('monto_pendiente')
    ->label('Deuda Actual')
    ->money('USD')
    ->color('danger'),
            TextColumn::make('monto')
                ->money('USD') // Formatea automáticamente como dinero
                ->sortable(),
                
            TextColumn::make('fecha_vencimiento')
                ->date('d/m/Y') // Formato de fecha latino/europeo
                ->sortable(),
                
            TextColumn::make('estado')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    '0' => 'Pendiente',
                    '1' => 'Pagado',
                    '2' => 'Vencido',
                    default => 'Desconocido',
                    
                })
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    '0' => 'warning', // Amarillo
                    '1' => 'success', // Verde
                    '2' => 'danger',  // Rojo
                    default => 'gray',
                }),
        ])
->filters([
            // 1. Filtro rápido por Estado
            SelectFilter::make('estado')
                ->options([
                    '0' => 'Pendiente',
                    '1' => 'Pagado',
                    '2' => 'Vencido',
                ])
                ->label('Estado de la Expensa'),

            // 2. Filtro avanzado por Rango de Fechas
            Filter::make('fecha_vencimiento')
                ->form([
                    DatePicker::make('desde')
                        ->label('Vence desde'),
                    DatePicker::make('hasta')
                        ->label('Vence hasta'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['desde'],
                            fn (Builder $query, $date): Builder => $query->whereDate('fecha_vencimiento', '>=', $date),
                        )
                        ->when(
                            $data['hasta'],
                            fn (Builder $query, $date): Builder => $query->whereDate('fecha_vencimiento', '<=', $date),
                        );
                })
                ->indicateUsing(function (array $data): array {
                    $indicators = [];
                    if ($data['desde'] ?? null) {
                        $indicators[] = 'Desde: ' . \Carbon\Carbon::parse($data['desde'])->format('d/m/Y');
                    }
                    if ($data['hasta'] ?? null) {
                        $indicators[] = 'Hasta: ' . \Carbon\Carbon::parse($data['hasta'])->format('d/m/Y');
                    }
                    return $indicators;
                }),
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
            'index' => Pages\ListExpensas::route('/'),
            'create' => Pages\CreateExpensa::route('/create'),
            'edit' => Pages\EditExpensa::route('/{record}/edit'),
        ];
    }
}
