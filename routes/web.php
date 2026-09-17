<?php
use App\Models\Expensa;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReporteController;
use Carbon\Carbon;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/exportar-dataset', function () {
    $expensas = Expensa::with('propiedad')->get();
    
    $csvData = "monto,tipo_propiedad,piso,es_moroso\n"; // Cabeceras
    
    foreach ($expensas as $expensa) {
        $monto = $expensa->monto;
        $tipo = $expensa->propiedad->tipo_propiedad;
        $piso = $expensa->propiedad->piso;
        // 1 si está vencido/pagado tarde, 0 si pagó bien
        $esMoroso = $expensa->estado == 2 ? 1 : 0; 
        
        $csvData .= "{$monto},{$tipo},{$piso},{$esMoroso}\n";
    }
    
    return Response::make($csvData, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="dataset_expensas.csv"',
    ]);
});
Route::get('/imprimir-morosidad', function () {
    $deudores = Expensa::with('propiedad')
        ->where('monto_pendiente', '>', 0)
        ->whereDate('fecha_vencimiento', '<', Carbon::today())
        ->orderBy('fecha_vencimiento', 'asc')
        ->get();
        
    return view('imprimir-morosidad', compact('deudores'));
})->name('imprimir.morosidad');
Route::get('/imprimir/morosidad', [ReporteController::class, 'morosidad'])->name('imprimir.morosidad');
Route::get('/imprimir/estado-cuenta/{id}', [ReporteController::class, 'estadoCuenta'])->name('imprimir.estadoCuenta');
// Rutas para reportes consolidados
Route::get('/imprimir/recaudacion', [ReporteController::class, 'recaudacion'])->name('imprimir.recaudacion');
Route::get('/imprimir/gastos', [ReporteController::class, 'gastos'])->name('imprimir.gastos');
// Rutas para reportes de consumo de servicios
Route::get('/imprimir/consumo-agua', [ReporteController::class, 'consumoAgua'])->name('imprimir.agua');
Route::get('/imprimir/consumo-gas', [ReporteController::class, 'consumoGas'])->name('imprimir.gas');
//Ruta Balance general
Route::get('/imprimir/balance', [ReporteController::class, 'balance'])->name('imprimir.balance');
