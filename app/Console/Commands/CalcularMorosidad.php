<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Expensa;
use Carbon\Carbon;

class CalcularMorosidad extends Command
{
    protected $signature = 'expensas:calcular-morosidad';
    protected $description = 'Calcula días de retraso y aplica multas a expensas vencidas';

    public function handle()
    {
        // 1. Buscar todas las expensas vencidas que aún deben dinero
        $expensasVencidas = Expensa::where('monto_pendiente', '>', 0)
            ->whereDate('fecha_vencimiento', '<', Carbon::today())
            ->get();

        foreach ($expensasVencidas as $expensa) {
            $fechaVencimiento = Carbon::parse($expensa->fecha_vencimiento);
            $diasRetraso = $fechaVencimiento->diffInDays(Carbon::today());

            // 2. REGLA DE NEGOCIO (Modificable en el futuro)
            // Ejemplo: Multa de 5 por cada día de retraso
            $tarifaPorDia = 5.00; 
            $multaTotal = $diasRetraso * $tarifaPorDia;

            // 3. Recalcular el total sumando todos los conceptos de tu tabla
            $nuevoMonto = $expensa->cuota_ordinaria 
                        + $expensa->cuota_extraordinaria 
                        + $expensa->fondo_mantenimiento 
                        + $expensa->fondo_ascensor 
                        + $multaTotal; // Se suma la nueva multa

            // 4. Actualizar la base de datos
            $expensa->update([
                'multas' => $multaTotal,
                'monto' => $nuevoMonto,
                'monto_pendiente' => $nuevoMonto - $expensa->monto_pagado,
            ]);
        }

        $this->info('Morosidad calculada y multas aplicadas con éxito.');
    }
}