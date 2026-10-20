<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Propiedad;
use App\Models\Expensa;

class GastoAscensor extends Model
{
    protected $table = 'gastos_ascensor';
    protected $guarded = [];

    protected static function booted()
    {
        // Este evento se dispara automáticamente DESPUÉS de guardar un nuevo gasto
        static::created(function ($gasto) {
            
            // 1. Buscamos todas las propiedades que están habitadas/activas
            $propiedadesActivas = Propiedad::where('estado', 1)->get();
            $totalPropiedades = $propiedadesActivas->count();

            if ($totalPropiedades > 0) {
                // 2. Regla de negocio: División en partes iguales
                $cuotaIndividual = $gasto->monto_total / $totalPropiedades;

                // 3. Inyectamos la cuota en la expensa de cada inquilino
                foreach ($propiedadesActivas as $propiedad) {
                    Expensa::where('propiedad_id', $propiedad->id)
                           ->where('mes_gestion', $gasto->mes_gestion)
                           ->increment('fondo_ascensor', $cuotaIndividual);
                }
            }
        });
    }
}