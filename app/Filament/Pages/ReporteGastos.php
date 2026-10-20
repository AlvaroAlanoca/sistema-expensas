<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Egreso;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteGastos extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-down';
    protected static string $view = 'filament.pages.reporte-gastos';
    protected static ?string $title = 'Gastos del Edificio';
    protected static ?string $navigationGroup = 'Reportes Financieros';

    public function getGastosProperty()
    {

        return Egreso::select(
            DB::raw('DATE_FORMAT(fecha_egreso, "%Y-%m") as mes_codigo'),
            DB::raw('SUM(monto) as total_gastado')
        )
        ->where('estado', 1) // Solo sumamos egresos que no hayan sido anulados
        ->groupBy('mes_codigo')
        ->orderBy('mes_codigo', 'desc')
        ->get()
        ->map(function ($item) {

            $fecha = Carbon::createFromFormat('Y-m', $item->mes_codigo);
            $item->mes_legible = ucfirst($fecha->translatedFormat('F Y'));
            return $item;
        });
    }
}