<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Propiedad extends Model
{
    protected $table = 'propiedades'; // Le indicamos el nombre exacto de la tabla
    protected $guarded = [];
    /*protected $fillable = [
        'numero', 
        'piso', 
        'nombre', 
        'tipo_propiedad', 
        'estado',
        'propietario_id', 
        'inquilino_id', 
        'estado_ocupacion',
        'torre',
    
    'metros_cuadrados',
    'porcentaje_participacion',
    ];*/

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

//XXXXXXXXXXXXXXXXNuevas FUNCIONESXXXXXXXXXXXXXXX
public function usuarios()
    {
        return $this->belongsToMany(User::class)->withPivot('rol_en_propiedad')->withTimestamps();
    }
public function inquilinosPrincipales()
    {
        return $this->belongsToMany(User::class, 'propiedad_user', 'propiedad_id', 'user_id')
                    // Aceptamos ambas variaciones de la palabra
                    ->wherePivotIn('rol_en_propiedad', ['inquilino', 'inquilino_principal'])
                    ->withPivot('rol_en_propiedad')
                    ->withTimestamps();
    }
// Historial financiero de la unidad
    public function expensas()
    {
        return $this->hasMany(Expensa::class)->orderBy('fecha_vencimiento', 'desc');
    }
    public function getNombreCompletoAttribute()
    {
        $texto = '';
        if ($this->torre) { $texto .= "Torre {$this->torre} - "; }
        if ($this->piso) { $texto .= "Piso {$this->piso} - "; }
        
        return $texto . "Unidad {$this->numero}";
    }
}