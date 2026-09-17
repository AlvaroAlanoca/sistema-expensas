<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Expensa;
use Illuminate\Support\Facades\DB;

class ReporteRecaudacion extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static string $view = 'filament.pages.reporte-recaudacion';
    protected static ?string $title = 'Recaudación Mensual';
    protected static ?string $navigationGroup = 'Reportes Financieros';

    // Esta función agrupa y suma los pagos por mes de gestión
    public function getRecaudacionesProperty()
    {
        return Expensa::select('mes_gestion', DB::raw('SUM(monto_pagado) as total_recaudado'))
            ->where('monto_pagado', '>', 0)
            ->groupBy('mes_gestion')
            ->orderByRaw("MIN(fecha_vencimiento) DESC") // Ordena por el mes más reciente
            ->get();
    }
}