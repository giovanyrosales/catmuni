<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buses extends Model
{
    protected $table = 'buses';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_empresa',
        'id_estado_buses',
        'nom_bus',
        'fecha_inicio',
        'placa',
        'ruta',
        'telefono',
       
      ];
}
