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
        'impuesto_mora',
        'impuesto',
        'intereses_moratorios',
        'monto_multa_matricula',
        'monto_multaPE',
        'fondo_fiestasP',
        'pago_total',
        'fecha_cobro',
        'periodo_cobro_inicio',
        'periodo_cobro_fin',
        'periodo_cobro_inicioMatricula',
        'periodo_cobro_finMatricula',
        'tipo_cobro',
      ];
}
