<?php

namespace App\Http\Controllers;

use App\Models\Expensa;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pago;
class ReporteController extends Controller
{
    public function morosidad()
    {
        // 1. Buscamos a los deudores (la misma lógica de tu tabla)
        $deudores = Expensa::with('propiedad.usuarios')
            ->where('monto_pendiente', '>', 0)
            ->whereDate('fecha_vencimiento', '<', Carbon::today())
            ->orderBy('fecha_vencimiento', 'asc')
            ->get();

        $fechaReporte = Carbon::now()->format('d/m/Y H:i');

        // 2. Cargamos la vista del diseño y le pasamos los datos
        $pdf = Pdf::loadView('pdf.morosidad', compact('deudores', 'fechaReporte'));
        
        // 3. Mostramos el PDF en el navegador (para descargar directamente usa ->download('...'))
        return $pdf->stream('Reporte_Deudores_Mes.pdf');
    }
    public function estadoCuenta($id)
    {
        // Traemos la propiedad con sus usuarios y su historial de expensas
        $propiedad = \App\Models\Propiedad::with(['usuarios', 'expensas'])->findOrFail($id);
        $fechaReporte = Carbon::now()->format('d/m/Y H:i');

        $pdf = Pdf::loadView('pdf.estado-cuenta', compact('propiedad', 'fechaReporte'));
        
        return $pdf->stream("Estado_Cuenta_Unidad_{$propiedad->numero}.pdf");
    }
    public function recaudacion()
    {
        // 1. Mismas matemáticas que en la página visual
        $recaudaciones = \App\Models\Expensa::select('mes_gestion', \Illuminate\Support\Facades\DB::raw('SUM(monto_pagado) as total_recaudado'))
            ->where('monto_pagado', '>', 0)
            ->groupBy('mes_gestion')
            ->orderByRaw("MIN(fecha_vencimiento) DESC")
            ->get();

        $fechaReporte = Carbon::now()->format('d/m/Y H:i');
        
        // 2. Cálculo del gran total para el PDF
        $granTotal = $recaudaciones->sum('total_recaudado');

        // 3. Renderizamos la vista
        $pdf = Pdf::loadView('pdf.recaudacion', compact('recaudaciones', 'fechaReporte', 'granTotal'));
        return $pdf->stream('Reporte_Recaudacion_Historica.pdf');
    }

    public function gastos()
    {
        // 1. Mismas matemáticas que en la página visual
        $gastos = \App\Models\Egreso::select(
            \Illuminate\Support\Facades\DB::raw('DATE_FORMAT(fecha_egreso, "%Y-%m") as mes_codigo'),
            \Illuminate\Support\Facades\DB::raw('SUM(monto) as total_gastado')
        )
        ->where('estado', 1)
        ->groupBy('mes_codigo')
        ->orderBy('mes_codigo', 'desc')
        ->get()
        ->map(function ($item) {
            $fecha = Carbon::createFromFormat('Y-m', $item->mes_codigo);
            $item->mes_legible = ucfirst($fecha->translatedFormat('F Y'));
            return $item;
        });

        $fechaReporte = Carbon::now()->format('d/m/Y H:i');
        
        // 2. Cálculo del gran total
        $granTotal = $gastos->sum('total_gastado');

        // 3. Renderizamos la vista
        $pdf = Pdf::loadView('pdf.gastos', compact('gastos', 'fechaReporte', 'granTotal'));
        return $pdf->stream('Reporte_Gastos_Historicos.pdf');
    }
    public function consumoAgua()
    {
        $lecturas = \App\Models\LecturaAgua::with('propiedad')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $fechaReporte = \Carbon\Carbon::now()->format('d/m/Y H:i');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.consumo-agua', compact('lecturas', 'fechaReporte'));
        
        return $pdf->stream('Reporte_Consumo_Agua.pdf');
    }

    public function consumoGas()
    {
        $lecturas = \App\Models\LecturaGas::with('propiedad')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $fechaReporte = \Carbon\Carbon::now()->format('d/m/Y H:i');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.consumo-gas', compact('lecturas', 'fechaReporte'));
        
        return $pdf->stream('Reporte_Consumo_Gas.pdf');
    }
    public function balance()
    {
        $ingresosExpensas = \App\Models\Expensa::sum('monto_pagado');
        $ingresosAgua = \App\Models\LecturaAgua::sum('monto_pagado');
        $ingresosGas = \App\Models\LecturaGas::sum('monto_pagado');
        $totalIngresos = $ingresosExpensas + $ingresosAgua + $ingresosGas;
        
        $totalEgresos = \App\Models\Egreso::where('estado', 1)->sum('monto');
        $balance = $totalIngresos - $totalEgresos;
        
        $fechaReporte = \Carbon\Carbon::now()->format('d/m/Y H:i');
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.balance', compact(
            'ingresosExpensas', 'ingresosAgua', 'ingresosGas', 'totalIngresos', 'totalEgresos', 'balance', 'fechaReporte'
        ));
        
        return $pdf->stream('Balance_General.pdf');
    }
    public function imprimirRecibo(Pago $pago)
    {
        // Validar que el pago le pertenece al usuario o que es un administrador
        if ($pago->user_id !== \Illuminate\Support\Facades\Auth::id() && \Illuminate\Support\Facades\Auth::user()->rol !== 'admin') {
            abort(403, 'No tienes permiso para ver este recibo.');
        }

        // ... tu código normal para generar el PDF ...
    }
}