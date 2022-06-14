<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusesDetalle extends Model
{
    protected $table = 'buses_detalle';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_empresa',
        'fecha_apertura',
        'cantidad',
        'monto_pagar',
        'tarifa',
        'estado_especificacion',
       
      ];
}
