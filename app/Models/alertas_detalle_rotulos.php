<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class alertas_detalle_rotulos extends Model
{
    protected $table = 'alertas_detalle_rotulos';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id_contribuyente',
        'id_alerta',
        'cantidad',    
      ];
}
