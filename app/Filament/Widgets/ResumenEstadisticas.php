<?php

namespace App\Filament\Widgets;

use App\Models\Propiedad;
use App\Models\Expensa;
// use App\Models\Gasto; // Lo descomentaremos cuando creemos la tabla de gastos
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ResumenEstadisticas extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';
    
    // Hace que el widget ocupe todo el ancho de la pantalla para acomodar las 5 tarjetas
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        // 1. Consultas a la base de datos
        $totalPropiedades = Propiedad::count();
        
        // Sumamos las columnas numéricas exactas
        $totalRecaudado = Expensa::sum('monto_pagado');
        $totalDeuda = Expensa::sum('monto_pendiente');

        // TODO: Cuando creemos la tabla de Gastos, cambiaremos este 0 por: Gasto::sum('monto')
        $totalGastos = 0; 
        
        // Fórmula del Balance General (Dinero real en caja)
        $balance = $totalRecaudado - $totalGastos;

        // 2. Retorno de las tarjetas visuales
        return [
            Stat::make('Total Propiedades', $totalPropiedades)
                ->description('Unidades en el edificio')
                ->descriptionIcon('heroicon-m-home-modern')
                ->color('info'),

            Stat::make('Recaudación Total', 'Bs. ' . number_format($totalRecaudado, 2))
                ->description('Ingresos confirmados')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 10, 13, 15, 20, 32])
                ->color('success'),

            Stat::make('Gastos del Edificio', 'Bs. ' . number_format($totalGastos, 2))
                ->description('Egresos por mantenimiento')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('warning'),

            Stat::make('Balance General', 'Bs. ' . number_format($balance, 2))
                ->description('Recaudación vs Gastos')
                ->descriptionIcon('heroicon-m-scale')
                ->color($balance >= 0 ? 'success' : 'danger'),

            Stat::make('Deuda por Cobrar', 'Bs. ' . number_format($totalDeuda, 2))
                ->description('Capital en mora')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->chart([15, 12, 10, 8, 12, 18])
                ->color('danger'),
        ];
    }
}