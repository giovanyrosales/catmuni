<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CobrosLicenciaLicor extends Model
{
    protected $table = 'cobros_licencia_licor';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_empresa',
        'id_usuario',
        'monto_multa_licencia_15313',
        'monto_licencia_12207',
        'codigo',
        'pago_total',
        'fecha_cobro',
        'periodo_cobro_inicio',
        'periodo_cobro_fin',
        'tipo_cobro',  
        ];
}
