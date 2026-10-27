<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropiedadUserResource\Pages;
use App\Filament\Resources\PropiedadUserResource\RelationManagers;
use App\Models\PropiedadUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
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
            // 1. PRIMERO PEDIMOS EL ROL
            Forms\Components\Select::make('rol_en_propiedad')
                ->label('¿Qué rol tendrá este usuario?')
                ->options([
                    'propietario' => 'Propietario',
                    'inquilino' => 'Inquilino',
                ])
                ->live() 
                ->required(),

            // 2 LISTA DE PROPIEDADES SE ADAPTA AL ROL
            Forms\Components\Select::make('propiedad_id')
                ->relationship('propiedad', 'numero', function (Builder $query, Get $get) {
                    $rol = $get('rol_en_propiedad');
                    
                    // Departamentos Desocupados Y Ocupados
                    if ($rol === 'inquilino') {
                        return $query->whereIn('estado_ocupacion', ['Desocupado', 'Ocupado']);
                    }
                    
                    // Si es Propietario (o aún no se seleccionó el rol), SOLO  Desocupados
                    return $query->where('estado_ocupacion', 'Desocupado');
                }) 
                ->getOptionLabelFromRecordUsing(fn ($record) => trim("{$record->tipo_propiedad} - {$record->numero}"))
                ->searchable(['tipo_propiedad', 'numero'])
                ->preload()
                ->label('Propiedad (Disponibles)')
                ->required(),

            // 3. EL USUARIO
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name') 
                ->getOptionLabelFromRecordUsing(fn ($record) => trim("{$record->name} {$record->apellido_paterno} {$record->apellido_materno}"))
                ->searchable(['name', 'apellido_paterno', 'apellido_materno'])
                ->preload()
                ->label('Usuario')
                ->required(),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            // 1. Columna de Propiedad destacada
            Tables\Columns\TextColumn::make('propiedad.numero')
                ->label('Propiedad')
                ->searchable()
                ->sortable()
                ->weight('bold'), 

            // 2. Columna de Usuario (Nombre + Apellidos combinados)
            Tables\Columns\TextColumn::make('user.name')
                ->label('Usuario')
                ->formatStateUsing(fn ($record) => trim("{$record->user->name} {$record->user->apellido_paterno} {$record->user->apellido_materno}"))
                // Búsqueda inteligente: permite que el buscador superior encuentre por cualquier parte del nombre
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query->whereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('apellido_paterno', 'like', "%{$search}%")
                          ->orWhere('apellido_materno', 'like', "%{$search}%");
                    });
                })
                ->sortable(),

            // 3. Columna de Rol con diseño de "Etiquetas" (Badges)
            Tables\Columns\TextColumn::make('rol_en_propiedad')
                ->label('Rol')
                ->badge() // Convierte el texto simple en una etiqueta redondeada
                ->color(fn (string $state): string => match ($state) {
                    'propietario' => 'success', 
                    'inquilino' => 'warning',   
                    default => 'gray',
                }),
        ])
        ->filters([
            // Filtro desplegable para buscar solo Propietarios o solo Inquilinos
            Tables\Filters\SelectFilter::make('rol_en_propiedad')
                ->label('Filtrar por Rol')
                ->options([
                    'propietario' => 'Propietario',
                    'inquilino' => 'Inquilino',
                ]),
                
            // Filtro desplegable para ver quiénes están asignados a un departamento específico
            Tables\Filters\SelectFilter::make('propiedad_id')
                ->relationship('propiedad', 'numero')
                ->label('Filtrar por Propiedad')
                ->searchable()
                ->preload(),
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
