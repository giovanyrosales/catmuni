<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class alertas_detalle_buses extends Model
{
    protected $table = 'alertas_detalle_buses';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id_contribuyente',
        'id_alerta',
        'cantidad',    
      ];
}
