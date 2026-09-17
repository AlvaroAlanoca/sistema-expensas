<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Propiedad;
use App\Models\Expensa;
use Carbon\Carbon;

class GenerarExpensasMensuales extends Command
{
    protected $signature = 'expensas:generar';
    protected $description = 'Genera automáticamente las expensas del mes para todas las propiedades';

    public function handle()
    {
        // Traemos todas las propiedades (las 19 unidades)
        $propiedades = Propiedad::all(); 
        
        // Formateamos el mes actual (Ej: "Septiembre 2026")
        $mesActual = ucfirst(Carbon::now()->translatedFormat('F Y'));
        $fechaVencimiento = Carbon::now()->endOfMonth();

        foreach ($propiedades as $propiedad) {
            // Validación de seguridad para no duplicar deudas si el comando corre dos veces
            $existeExpensa = Expensa::where('propiedad_id', $propiedad->id)
                ->where('mes_gestion', $mesActual)
                ->exists();

            if (!$existeExpensa) {
                // Aquí defines los valores por defecto que se cobran cada mes
                $cuotaOrdinaria = 500.00; // Puedes hacerlo dinámico después
                $fondoMantenimiento = 50.00;
                $fondoAscensor = 20.00;
                
                $montoTotal = $cuotaOrdinaria + $fondoMantenimiento + $fondoAscensor;

                Expensa::create([
                    'propiedad_id' => $propiedad->id,
                    'mes_gestion' => $mesActual,
                    'cuota_ordinaria' => $cuotaOrdinaria,
                    'fondo_mantenimiento' => $fondoMantenimiento,
                    'fondo_ascensor' => $fondoAscensor,
                    'cuota_extraordinaria' => 0,
                    'multas' => 0,
                    'monto' => $montoTotal,
                    'monto_pendiente' => $montoTotal, // Arranca debiendo todo
                    'monto_pagado' => 0,
                    'monto_ahorro' => 0,
                    'fecha_vencimiento' => $fechaVencimiento,
                    'estado' => 2, // 2 = Pendiente
                    'status' => 1,
                ]);
            }
        }

        $this->info('Las expensas de ' . $mesActual . ' fueron generadas con éxito.');
    }
}
