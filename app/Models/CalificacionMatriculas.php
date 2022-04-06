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
          'nombre_matricula',
          'cantidad',
          'monto_matriculas',
          'pago_mensual',
          'año_calificacion',
          'estado_calificacion',
      ];
}
