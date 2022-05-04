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
        'impuesto_mora',
        'impuesto',
        'intereses_moratorios',
        'monto_multa_balance',
        'monto_multaPE',
        'fondo_fiestasP',
        'pago_total',
        'fecha_cobro',
        'periodo_cobro_inicio',
        'periodo_cobro_fin',
        'tipo_cobro',
        ];
}
