<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CobrosBuses extends Model
{
    protected $table = 'cobros_buses';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_contribuyente',
        'id_usuario',
        'id_buses_detalle',
        'cantidad_meses_cobro',
        'tasa_servicio_mora_32201',
        'impuestos',
        'codigo',
        'intereses_moratorios_15302',      
        'fondo_fiestasP_12114',
        'pago_total',
        'fecha_cobro',
        'periodo_cobro_inicio',
        'periodo_cobro_fin',
    
        ];
}
