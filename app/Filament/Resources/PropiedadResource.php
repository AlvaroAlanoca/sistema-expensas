<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropiedadResource\Pages;
use App\Filament\Resources\PropiedadResource\RelationManagers;
use App\Models\Propiedad;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Section;

class PropiedadResource extends Resource
{
    protected static ?string $model = Propiedad::class;
protected static ?string $navigationGroup = 'Administración';
    protected static ?string $navigationLabel = 'Propiedades';
    protected static ?string $pluralModelLabel = 'Propiedades';
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
  
public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Ubicación Física')
                    ->description('Coordenadas exactas de la unidad dentro del condominio.')
                    ->schema([
                        Forms\Components\TextInput::make('torre')
                            ->label('Torre / Bloque')
                            ->placeholder('Ej: Torre A')
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('piso')
                            ->label('Nivel / Piso')
                            ->placeholder('Ej: 3')
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('numero')
                            ->label('Número de Unidad')
                            ->placeholder('Ej: 301')
                            ->required()
                            ->maxLength(255),
                    ])->columns(3),

                Forms\Components\Section::make('Clasificación y Estado')
                    ->schema([
                        Forms\Components\Select::make('tipo_propiedad')
                            ->label('Tipo de Propiedad')
                            ->options([
                                'departamento' => 'Departamento',
                                'parqueo' => 'Parqueo / Estacionamiento',
                                'baulera' => 'Baulera / Depósito',
                                'comercial' => 'Local Comercial',
                            ])
                            ->required(),
                            
                        Forms\Components\Select::make('estado_ocupacion')
                            ->label('Estado de Ocupación')
                            ->options([
                                'Ocupado' => 'Ocupado',
                                'Desocupado' => 'Desocupado',
                                'Mantenimiento' => 'En Mantenimiento',
                            ])
                            ->default('Desocupado')
                            ->required(),

                        Forms\Components\Select::make('estado')
                            ->label('Estado del Registro')
                            ->options([
                                1 => 'Activo',
                                0 => 'Inactivo / Suspendido',
                            ])
                            ->default(1)
                            ->required(),
                    ])->columns(3),

                Forms\Components\Section::make('Dimensiones y Prorrateo')
                    ->description('Datos técnicos para el cálculo de expensas.')
                    ->schema([
                        Forms\Components\TextInput::make('metros_cuadrados')
                            ->label('Área Privada')
                            ->numeric()
                            ->suffix('m²')
                            ->maxValue(99999),
                            
                        Forms\Components\TextInput::make('participacion')
                            ->label('Participación en Áreas Comunes')
                            ->numeric()
                            ->suffix('%')
                            ->maxValue(100)
                            ->helperText('Utilizado para el cálculo de cuotas extraordinarias.'),
                    ])->columns(2),

                Forms\Components\Section::make('Información Adicional')
                    ->schema([
                        Forms\Components\Textarea::make('observaciones')
                            ->label('Notas u Observaciones')
                            ->placeholder('Detalles físicos, reportes de humedad, características especiales...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero')->label('N°')->sortable()->searchable(),
                TextColumn::make('torre')->label('Torre')->sortable()->searchable(),
                TextColumn::make('piso')->label('Piso')->sortable(),
                TextColumn::make('metros_cuadrados')->label('Área')->suffix(' m²')->sortable()->toggleable(),
                TextColumn::make('tipo_propiedad')->label('Tipo')->badge()->searchable(),
                
                TextColumn::make('estado_ocupacion')
                    ->label('Ocupación')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ocupado' => 'success',
                        'alquilado' => 'warning',
                        'desocupado' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state == 1 ? 'Activo' : 'Inactivo')
                    ->color(fn ($state) => $state == 1 ? 'success' : 'danger'),
            ])
            ->filters([
                // Aquí puedes agregar filtros posteriores
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Action::make('evaluar_riesgo')
                    ->label('Evaluar Riesgo')
                    ->icon('heroicon-o-sparkles')
                    ->color('info')
                    ->form([
                        TextInput::make('monto_estimado')
                            ->label('Monto de la próxima expensa')
                            ->numeric()
                            ->required()
                            ->prefix('Bs.'),
                    ])
                    ->action(function ($record, array $data) {
                        try {
                            $response = Http::post('http://127.0.0.1:8000/predecir', [
                                'monto' => (float) $data['monto_estimado'],
                                'piso' => (int) $record->piso,
                                'tipo_propiedad' => $record->tipo_propiedad,
                            ]);

                            if ($response->successful()) {
                                $resultado = $response->json();
                                if ($resultado['riesgo_alto'] ?? false) {
                                    Notification::make()
                                        ->title('Alto Riesgo de Mora')
                                        ->body("Probabilidad de atraso: {$resultado['probabilidad_atraso_porcentaje']}%. " . ($resultado['mensaje'] ?? ''))
                                        ->danger()
                                        ->send();
                                } else {
                                    Notification::make()
                                        ->title('Bajo Riesgo de Mora')
                                        ->body("Probabilidad de atraso: {$resultado['probabilidad_atraso_porcentaje']}%. " . ($resultado['mensaje'] ?? ''))
                                        ->success()
                                        ->send();
                                }
                            } else {
                                Notification::make()->title('Error en la API Predictiva')->danger()->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('No se pudo conectar con la IA')
                                ->body('Asegúrate de que el servidor Uvicorn esté encendido.')
                                ->danger()
                                ->send();
                        }
                    }),
                    // --- BOTÓN ESTADO DE CUENTA ---
                Tables\Actions\Action::make('estado_cuenta')
                    ->label('Estado de Cuenta')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->url(fn (Propiedad $record): string => route('imprimir.estadoCuenta', $record->id))
                    ->openUrlInNewTab(),
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
            RelationManagers\UsuariosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPropiedads::route('/'),
            'create' => Pages\CreatePropiedad::route('/create'),
            'edit' => Pages\EditPropiedad::route('/{record}/edit'),
        ];
    }
}