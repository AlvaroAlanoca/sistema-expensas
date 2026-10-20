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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;

class EgresoResource extends Resource
{
    protected static ?string $model = Egreso::class;

protected static ?string $navigationGroup = 'Finanzas y Pagos';
    protected static ?string $navigationLabel = 'Egresos Generales';
    protected static ?string $pluralModelLabel = 'Egresos';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-down';

 public static function form(Form $form): Form
{
    return $form
        ->schema([
            Section::make('Detalles del Egreso')
                ->description('Registre la información del dinero que saldrá de la caja.')
                ->icon('heroicon-o-banknotes') // Un ícono elegante para la sección
                ->schema([
                    // Primera fila: Caja de origen y Fecha
                    Select::make('caja_chica_id')
                        // Asegúrate de que 'nombre' sea el campo que quieres mostrar de la caja (ej. "Caja Principal")
                        ->relationship('cajaChica', 'id') 
                        ->label('Caja Chica (Origen)')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->columnSpan(1),

                    DatePicker::make('fecha_egreso')
                        ->label('Fecha del Egreso')
                        ->default(now()) // Se llena automáticamente con la fecha de hoy
                        ->required()
                        ->columnSpan(1),

                    // Segunda fila: El concepto ocupa todo el ancho disponible
                    TextInput::make('concepto')
                        ->label('Concepto o Descripción')
                        ->placeholder('Ej: Pago a la empresa de limpieza mensual')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(2), // Ocupa las 2 columnas para que el texto largo se vea bien

                    // Tercera fila: Monto y Estado
                    TextInput::make('monto')
                        ->label('Monto Total')
                        ->numeric()
                        ->prefix('$') // O 'Bs.' dependiendo de tu moneda
                        ->required()
                        ->columnSpan(1),

                    Select::make('estado')
                        ->label('Estado del Egreso')
                        ->options([
                            1 => 'Aprobado',
                            0 => 'Anulado',
                            2 => 'Pendiente' // Ajusta estos números según tu lógica
                        ])
                        ->default(1)
                        ->required()
                        ->columnSpan(1),
                        
                ])->columns(2), // Divide la tarjeta en 2 columnas perfectas
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
