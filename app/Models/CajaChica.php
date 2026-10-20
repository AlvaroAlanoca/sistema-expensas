<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CajaChica extends Model
{

    protected $table = 'cajas_chicas';

    protected $fillable = [
        'total',
    ];
    
}