<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CobrosMatriculas extends Model
{
    protected $table = 'cobros_matriculas';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_matriculas_detalle',
        'id_usuario',
        'cantidad_meses_cobro',
        'tasas_servicio_mora_32201',
        'tasas_servicio_12299',
        'intereses_moratorios_15302',
        'multa_matricula_15313',
        'monto_multaPE_15313',
        'fondo_fiestasP_12114',
        'pago_total',
        'fecha_cobro',
        'periodo_cobro_inicio',
        'periodo_cobro_fin',
        'periodo_cobro_inicioMatricula',
        'periodo_cobro_finMatricula',
        'tipo_cobro',
        'cod_act_economica'
      ];
}
