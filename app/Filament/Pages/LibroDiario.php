<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use App\Models\CajaChica;

class LibroDiario extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static string $view = 'filament.pages.libro-diario';
    protected static ?string $title = 'Libro Diario (Ingresos y Egresos)';
    protected static ?string $navigationGroup = 'Módulo de Caja';

    // Variables en tiempo real para la vista
    public $cajas = [];
    public $cajaSeleccionada = null;
    public $saldoActual = 0;

    public function mount()
    {
        $this->cajas = CajaChica::all();
        
        // Seleccionamos la última caja abierta por defecto
        $caja = CajaChica::latest()->first();
        if ($caja) {
            $this->cajaSeleccionada = $caja->id;
            $this->saldoActual = $caja->total;
        }
    }

    // Esta función actualiza el saldo en tiempo real si el admin cambia de caja en el menú
    public function updatedCajaSeleccionada($value)
    {
        $caja = CajaChica::find($value);
        $this->saldoActual = $caja ? $caja->total : 0;
    }

    // El motor contable: une las dos tablas y calcula el saldo fila por fila
    public function getMovimientosProperty()
    {
        if (!$this->cajaSeleccionada) return [];

        // 1. Preparamos las consultas de Ingresos
            $ingresos = DB::table('ingresos_caja_chica')
            ->where('caja_chica_id', $this->cajaSeleccionada)
            ->select(
                'fecha_ingreso as fecha', 
                // Si existe un concepto manual lo usa, sino, usa el texto por defecto del pago
                DB::raw("COALESCE(concepto, CONCAT('Ingreso por Pago #', pago_id)) as concepto"), 
                'monto_ingreso as ingreso', 
                DB::raw('0 as egreso'), 
                'created_at'
            );

        // 2. Preparamos las consultas de Egresos
        $egresos = DB::table('egresos')
            ->where('caja_chica_id', $this->cajaSeleccionada)
            ->select(
                'fecha_egreso as fecha', 
                'concepto', 
                DB::raw('0 as ingreso'), 
                'monto as egreso', 
                'created_at'
            );

        // 3. Unimos ambas tablas y ordenamos de más antiguo a más nuevo
        $movimientos = $ingresos->unionAll($egresos)
            ->orderBy('created_at', 'asc')
            ->get();

        // 4. Calculamos el saldo contable iterativo (fila por fila)
        $saldoIterativo = 0;
        foreach ($movimientos as $mov) {
            $saldoIterativo += $mov->ingreso;
            $saldoIterativo -= $mov->egreso;
            $mov->saldo = $saldoIterativo;
        }

        // 5. Invertimos el orden para que lo más reciente aparezca arriba en la tabla
        return $movimientos->reverse();
    }
}