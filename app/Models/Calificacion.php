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
        'id_estado_licencia_licor',
        'fecha_calificacion',
        'tipo_tarifa',    
        'estado_calificacion',
        'licencia',
        'matricula',
        'total_mat_permisos',
        'fondofp_licencia_permisos',
        'pago_anual_permisos',
        'activo_total',
        'deducciones',
        'activo_imponible',
        'año_calificacion',
        'tarifa',
        'tarifa_colones',
        'pago_mensual',
        'pago_anual',
        'fondofp_mensual',
        'fondofp_anual',
        'total_impuesto',
        'total_impuesto_anual',
        'multa_balance',
        'codigo_tarifa',
      ];
}
