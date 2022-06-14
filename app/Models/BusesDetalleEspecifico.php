<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusesDetalleEspecifico extends Model
{
    protected $table = 'buses_detalle_especifico';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_buses_detalle',
        'nombre',
        'placa',
        'ruta',
        'telefono'
       
      ];
}
