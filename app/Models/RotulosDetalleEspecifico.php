<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RotulosDetalleEspecifico extends Model
{
    protected $table = 'rotulos_detalle_especifico';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_rotulos_detalle',
        'nombre',
        'medidas',
        'total_medidas',
        'caras',
        'tarifa',
        'total_tarifa',
        'coordenadas_geo',
        'foto_rotulo',
              
    ];
}