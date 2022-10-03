<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalificacionMatriculas extends Model
{
    protected $table = 'calificacion_matriculas';
    public $timestamps = true;
    protected $fillable = [
          'id',
          'id_matriculas_detalle',
          'id_estado_matricula',
          'id_giro_empresarial',
          'nombre_matricula',
          'cantidad',
          'fecha_calificacion',
          'monto_matricula',
          'pago_mensual',
          'fondofp',
          'pago_anual',
          'tarifa_colones',
          'total_impuesto_mat',
          'fondofp_impuesto_mat',
          'año_calificacion',
          'estado_calificacion',
          'tipo_tarifa',
          'codigo_tarifa',

          
      ];
}
