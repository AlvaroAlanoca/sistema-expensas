<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Expensa;
use Carbon\Carbon;
use Filament\Notifications\Notification;

class ReporteMorosidad extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static string $view = 'filament.pages.reporte-morosidad';
    protected static ?string $title = 'Reporte de Morosidad';
    protected static ?string $navigationGroup = 'Módulo de Morosidad';

    public function getDeudoresProperty()
    {
        // AHORA SÍ CARGA A LOS USUARIOS DESDE LA TABLA INTERMEDIA
        return Expensa::with('propiedad.usuarios')
            ->where('monto_pendiente', '>', 0)
            ->whereDate('fecha_vencimiento', '<', Carbon::today())
            ->orderBy('fecha_vencimiento', 'asc')
            ->get();
    }
    
    public function enviarWhatsApp($expensaId)
    {
        $expensa = Expensa::with('propiedad.usuarios')->find($expensaId);
        
        $usuario = $expensa->propiedad->usuarios->first();

        if (!$usuario || empty($usuario->telefono)) {
            Notification::make()
                ->title('Error de Contacto')
                ->body('Esta propiedad no tiene un usuario asignado o falta su teléfono.')
                ->danger()
                ->send();
            return;
        }

        $nombreCompleto = trim($usuario->name . ' ' . $usuario->apellido_paterno);
        $fechaVencimiento = Carbon::parse($expensa->fecha_vencimiento)->format('d/m/Y');

        // 1. Llamamos al servicio para obtener el enlace generado
        $enlace = \App\Services\WhatsAppService::generarEnlace(
            $usuario->telefono,
            $nombreCompleto,
            $expensa->mes_gestion,
            $expensa->monto_pendiente,
            $fechaVencimiento
        );

        // 2. Usamos Javascript a través de Livewire para abrir el enlace en una nueva pestaña
        $this->js("window.open('{$enlace}', '_blank');");

        // 3. Mostramos una notificación de que la acción se ejecutó
        Notification::make()
            ->title('Abriendo WhatsApp...')
            ->body("Redirigiendo al chat con {$nombreCompleto}.")
            ->success()
            ->send();
    }
}