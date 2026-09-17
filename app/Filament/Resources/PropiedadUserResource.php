<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropiedadUserResource\Pages;
use App\Filament\Resources\PropiedadUserResource\RelationManagers;
use App\Models\PropiedadUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PropiedadUserResource extends Resource
{
    protected static ?string $model = PropiedadUser::class;

protected static ?string $navigationGroup = 'Administración';
    protected static ?string $navigationLabel = 'Asignación de Inquilinos';
    protected static ?string $pluralModelLabel = 'Asignaciones de Propiedades';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('propiedad_id')
                ->relationship('propiedad', 'numero') // Mostrará el número en lugar del ID
                ->label('Propiedad (Departamento)')
                ->required(),

            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name') // Mostrará el nombre en lugar del ID
                ->label('Usuario')
                ->required(),

            Forms\Components\Select::make('rol_en_propiedad')
                ->options([
                    'propietario' => 'Propietario',
                    'inquilino' => 'Inquilino',
                ])
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('propiedad.numero')->label('Nº Propiedad'),
            Tables\Columns\TextColumn::make('user.name')->label('Usuario'),
            Tables\Columns\TextColumn::make('rol_en_propiedad')->label('Rol'),
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
            'index' => Pages\ListPropiedadUsers::route('/'),
            'create' => Pages\CreatePropiedadUser::route('/create'),
            'edit' => Pages\EditPropiedadUser::route('/{record}/edit'),
        ];
    }
}
