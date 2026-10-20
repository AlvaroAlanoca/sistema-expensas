<?php

namespace App\Filament\Residente\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Models\Expensa;
use App\Models\LecturaAgua;
use App\Models\LecturaGas;

class MiDeuda extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-circle';
    protected static string $view = 'filament.residente.pages.mi-deuda';
    protected static ?string $title = 'Estado de Mis Deudas';
    protected static ?string $navigationLabel = 'Mi Deuda';

    // Esto sobreescribe la ruta para que sea la página de inicio del panel
    protected static string $routePath = '/';
    public function getDeudaDataProperty()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        

        $propiedadesIds = $user->propiedades()->pluck('propiedades.id');

        if ($propiedadesIds->isEmpty()) {
            return $this->estructuraVacia();
        }

        $expensas = Expensa::whereIn('propiedad_id', $propiedadesIds)->where('monto_pendiente', '>', 0)->get();
        $agua = LecturaAgua::whereIn('propiedad_id', $propiedadesIds)->where('monto_pendiente', '>', 0)->get();
        $gas = LecturaGas::whereIn('propiedad_id', $propiedadesIds)->where('monto_pendiente', '>', 0)->get();

        $totalExpensas = $expensas->sum('monto_pendiente');
        $totalAgua = $agua->sum('monto_pendiente');
        $totalGas = $gas->sum('monto_pendiente');

        return [
            'expensas' => $totalExpensas,
            'agua' => $totalAgua,
            'gas' => $totalGas,
            'total_general' => $totalExpensas + $totalAgua + $totalGas,
            'propiedades' => $user->propiedades,
            'detalles_expensas' => $expensas,
            'detalles_agua' => $agua,
            'detalles_gas' => $gas,
        ];
    }

    private function estructuraVacia()
    {
        return [
            'expensas' => 0, 'agua' => 0, 'gas' => 0, 'total_general' => 0,
            'propiedades' => collect(), 'detalles_expensas' => collect(), 'detalles_agua' => collect(), 'detalles_gas' => collect()
        ];
    }
}