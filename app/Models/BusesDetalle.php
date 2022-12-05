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
        'id_contribuyente',
        'nFicha',
        'fecha_apertura',
        'cantidad',
        'monto_pagar',
        'tarifa',
        'estado_especificacion',
        'nom_empresa',
        'dir_empresa',
        'nit_empresa',
        'tel_empresa',
        'email_empresa',
        'r_comerciante'

       
      ];
}
