<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Propiedad;
use App\Models\Expensa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class MLDatasetSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = ['departamento', 'oficina', 'local'];
        
        // 1. Crear 50 Propiedades y Usuarios simulados
        for ($i = 1; $i <= 50; $i++) {
            $user = User::create([
                'name' => "Usuario $i",
                'email' => "usuario$i@test.com",
                'password' => Hash::make('password'),
            ]);

            $propiedad = Propiedad::create([
                'numero' => $i,
                'piso' => rand(1, 10),
                'nombre' => "Unidad $i",
                'tipo_propiedad' => $tipos[array_rand($tipos)],
            ]);
            
            // Vincular usuario y propiedad
            $propiedad->users()->attach($user->id);

            // 2. Generar historial de 12 meses (1 año) de expensas para esta propiedad
            for ($mes = 1; $mes <= 12; $mes++) {
                $fechaVencimiento = Carbon::now()->subMonths(12 - $mes)->startOfMonth()->addDays(9); // Vence los días 10
                
                // LÓGICA DEL DATASET: Simulamos patrones para que el ML los descubra
                // Si es "local", tiende a pagar tarde. Si la expensa es muy alta, tiende a pagar tarde.
                $monto = rand(50, 300);
                $probabilidadAtraso = 20; // 20% base
                
                if ($propiedad->tipo_propiedad == 'local') $probabilidadAtraso += 30;
                if ($monto > 200) $probabilidadAtraso += 20;

                $esMoroso = rand(1, 100) <= $probabilidadAtraso;
                
                // Guardamos la expensa simulada en la base de datos
                Expensa::create([
                    'propiedad_id' => $propiedad->id,
                    'monto' => $monto,
                    'fecha_vencimiento' => $fechaVencimiento,
                    'estado' => $esMoroso ? 2 : 1, // 1: Pagado a tiempo, 2: Vencido/Pagado tarde
                    'created_at' => $fechaVencimiento->copy()->subDays(10),
                ]);
            }
        }
    }
}