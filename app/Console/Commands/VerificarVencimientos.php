<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Expensa;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Notification;

class VerificarVencimientos extends Command
{
    // El nombre del comando que ejecutaremos en la terminal
    protected $signature = 'expensas:verificar-vencimientos';

    // La descripción de lo que hace
    protected $description = 'Verifica expensas por vencer o vencidas y notifica a los copropietarios';

    public function handle()
    {
        $hoy = Carbon::now()->startOfDay();
        $manana = Carbon::now()->addDay()->startOfDay();

        $this->info('Iniciando verificación de expensas...');

        // 1. MARCAR COMO VENCIDAS Y NOTIFICAR
        // Buscamos expensas pendientes (0) cuya fecha ya pasó
        $expensasVencidas = Expensa::where('estado', 0)
            ->whereDate('fecha_vencimiento', '<', $hoy)
            ->with(['propiedad.users']) // Traemos la propiedad y sus dueños
            ->get();

        foreach ($expensasVencidas as $expensa) {
            // Cambiamos el estado a Vencido (2)
            $expensa->update(['estado' => 2]);

            // Notificamos a todos los usuarios asignados a esa propiedad
            foreach ($expensa->propiedad->users as $user) {
                Notification::make()
                    ->title('¡Expensa Vencida!')
                    ->body("La expensa de la propiedad {$expensa->propiedad->nombre} ha vencido el " . Carbon::parse($expensa->fecha_vencimiento)->format('d/m/Y') . ".")
                    ->danger() // Color rojo
                    ->sendToDatabase($user);
            }
            $this->warn("Expensa #{$expensa->id} marcada como vencida.");
        }

        // 2. AVISO DE PRÓXIMO VENCIMIENTO (Vence mañana)
        $expensasPorVencer = Expensa::where('estado', 0)
            ->whereDate('fecha_vencimiento', $manana)
            ->with(['propiedad.users'])
            ->get();

        foreach ($expensasPorVencer as $expensa) {
            foreach ($expensa->propiedad->users as $user) {
                Notification::make()
                    ->title('Vencimiento Próximo')
                    ->body("Recuerde que mañana vence la expensa de su propiedad {$expensa->propiedad->nombre} por un monto de \${$expensa->monto}.")
                    ->warning() // Color amarillo
                    ->sendToDatabase($user);
            }
            $this->info("Recordatorio enviado para expensa #{$expensa->id}.");
        }

        $this->info('Verificación completada.');
    }
}