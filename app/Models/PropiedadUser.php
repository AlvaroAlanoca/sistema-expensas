<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropiedadUser extends Model
{
    protected $table = 'propiedad_user'; 
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function propiedad()
    {
        return $this->belongsTo(Propiedad::class, 'propiedad_id');
    }
}