<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CobrosRotulo extends Model
{
    protected $table = 'cobros_rotulo';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_empresa',
        'id_usuario',
        'id_rotulos',
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
