<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultasDetalle extends Model
{
    protected $table = 'multas_detalle';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id_multas',
        'id_empresa',
        'id_estado_multa',
        'año_multa',
        'monto_multa',
      ];
}
