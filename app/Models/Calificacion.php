<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    protected $table = 'calificacion';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_detalle_actividad_economica',
        'id_empresa',
        'fecha_calificacion',
        'tarifa',
        'tipo_tarifa',
        'estado_calificacion'
      ];
}
