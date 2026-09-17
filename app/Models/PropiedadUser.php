<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropiedadUser extends Model
{
    protected $table = 'propiedad_user'; // Forzamos el nombre de tu tabla
    protected $guarded = [];

    // Relación para traer el nombre del usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación para traer el número de la propiedad
    public function propiedad()
    {
        return $this->belongsTo(Propiedad::class, 'propiedad_id');
    }
}