<?php

namespace App\Filament\Resources\PropiedadResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class UsuariosRelationManager extends RelationManager
{
    protected static string $relationship = 'usuarios';
    protected static ?string $title = 'Habitantes y Propietarios';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('apellido_paterno')
                    ->label('Apellido Paterno')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rol_en_propiedad')
                    ->label('Rol Asignado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'propietario' => 'success',
                        'inquilino_principal' => 'warning',
                        'co_inquilino' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state))),
            ])
            ->headerActions([
                // Esta acción abre el modal para vincular un usuario y guardar en la tabla pivot
                Tables\Actions\AttachAction::make()
                    ->label('Vincular Usuario')
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->label('Seleccionar Usuario de la base de datos'),
                        Forms\Components\Select::make('rol_en_propiedad')
                            ->label('Rol en esta propiedad')
                            ->options([
                                'propietario' => 'Propietario',
                                'inquilino_principal' => 'Inquilino Principal (Responsable de pago)',
                                'co_inquilino' => 'Co-inquilino (No responsable)',
                            ])
                            ->required(),
                    ]),
            ])
            ->actions([
                // Permite editar el rol si te equivocas
                Tables\Actions\EditAction::make(),
                // Permite quitar al usuario de la propiedad (ej. cuando se mudan) sin borrar su cuenta
                Tables\Actions\DetachAction::make()
                    ->label('Desvincular'),
            ]);
    }
}