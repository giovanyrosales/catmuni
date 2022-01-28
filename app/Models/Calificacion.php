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
        'id_empresa',
        'fecha_calificacion',
        'tipo_tarifa',
        'tarifa',
        'estado_calificacion',
        'licencia',
        'matricula',
        'pago_anual_permisos',
        'año_calificacion',
        'pago_mensual',
        'total_impuesto'
      ];
}
