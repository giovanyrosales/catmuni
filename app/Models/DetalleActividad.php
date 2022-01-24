<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleActividad extends Model
{
    protected $table = 'detalle_actividad_economica';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id_actividad_economica',
        'limite_inferior',
        'fijo',
        'categoria',
        'millar',
        'excedente',
        
      ];
}