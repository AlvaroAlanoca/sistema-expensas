<?php

namespace App\Filament\Residente\Resources;

use App\Filament\Residente\Resources\PagoResource\Pages;
use App\Models\Pago;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PagoResource extends Resource
{
    protected static ?string $model = Pago::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Mi Historial de Pagos';
    protected static ?string $pluralModelLabel = 'Mis Pagos';

    // Filtra los pagos para que solo vea los suyos.
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::id());
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]); // Vacío porque no van a crear pagos por aquí
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fecha_pago')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('monto')
                    ->label('Monto Pagado')
                    ->money('BOB', true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state == 1 ? 'Aprobado' : 'Pendiente')
                    ->color(fn ($state) => $state == 1 ? 'success' : 'warning'),
            ])
            ->defaultSort('fecha_pago', 'desc')
            ->actions([
                // Botón para descargar el recibo
                Tables\Actions\Action::make('imprimir_recibo')
                    ->label('Descargar Recibo')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                   ->url(fn (Pago $record) => route('imprimir.estadoCuenta', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([]); 
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPagos::route('/'),
        ];
    }
}