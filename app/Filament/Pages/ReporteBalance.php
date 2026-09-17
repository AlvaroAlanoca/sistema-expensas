<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Expensa;
use App\Models\Egreso;
use App\Models\LecturaAgua;
use App\Models\LecturaGas;

class ReporteBalance extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-scale';
    protected static string $view = 'filament.pages.reporte-balance';
    protected static ?string $title = 'Balance General';
    protected static ?string $navigationGroup = 'Reportes Financieros';
    protected static ?int $navigationSort = 3;

    public function getBalanceDataProperty()
    {
        // 1. Sumamos todos los ingresos registrados
        $ingresosExpensas = Expensa::sum('monto_pagado');
        $ingresosAgua = LecturaAgua::sum('monto_pagado');
        $ingresosGas = LecturaGas::sum('monto_pagado');
        $totalIngresos = $ingresosExpensas + $ingresosAgua + $ingresosGas;
        
        // 2. Sumamos todos los gastos
        $totalEgresos = Egreso::where('estado', 1)->sum('monto');
        
        // 3. Calculamos el dinero real en caja
        return [
            'expensas' => $ingresosExpensas,
            'agua' => $ingresosAgua,
            'gas' => $ingresosGas,
            'total_ingresos' => $totalIngresos,
            'total_egresos' => $totalEgresos,
            'neto' => $totalIngresos - $totalEgresos,
        ];
    }
}