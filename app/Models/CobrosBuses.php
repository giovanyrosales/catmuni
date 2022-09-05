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
        'nFicha',
        'cantidad_meses_cobro',
        'impuesto_mora',
        'impuesto',
        'intereses_moratorios',      
        'fondo_fiestasP',
        'pago_total',
        'fecha_cobro',
        'periodo_cobro_inicio',
        'periodo_cobro_fin',
    
        ];
}
