<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusesDetalle extends Model
{
    protected $table = 'buses_detalle';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id_empresa',
        'cantidad',
        'monto_pagar',
        'tarifa'
       
      ];
}
