<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatriculasDetalle extends Model
{
    protected $table = 'matriculas_detalle';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id_empresa',
        'id_matriculas',
        'id_estado_moratorio',
        'cantidad',
        'monto',
        'pago_mensual',
        'estado_especificacion',
      ];
}
