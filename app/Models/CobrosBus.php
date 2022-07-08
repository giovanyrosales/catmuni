<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CobrosBus extends Model
{
    protected $table = 'cobros_bus';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_buses',
        'id_empresa',
        'id_usuario',        
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
