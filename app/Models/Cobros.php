<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cobros extends Model
{
    protected $table = 'cobros';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_empresa',
        'id_usuario',
        'cantidad_meses_cobro',
        'impuesto_mora_32201',
        'impuestos_11801',
        'intereses_moratorios_15302',
        'monto_multa_balance_15313',
        'monto_multaPE_15313',
        'fondo_fiestasP_12114',
        'pago_total',
        'fecha_cobro',
        'periodo_cobro_inicio',
        'periodo_cobro_fin',
        'tipo_cobro',
        'cod_act_economica'
        ];
}
