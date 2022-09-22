<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RotulosDetalle extends Model
{
    protected $table = 'rotulos_detalle';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_contribuyente',
        'id_estado_rotulo',
        'num_ficha',
        'fecha_apertura',
        'cantidad_rotulos',
        'nom_empresa',
        'dire_empresa',
        'nit_empresa',
        'tel_empresa',
        'email_empresa',
        'reg_comerciante',
        'actividad_economica',
        'estado_especificacion'

    ];
}