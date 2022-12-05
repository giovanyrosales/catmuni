<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class alertas_detalle extends Model
{
    protected $table = 'alertas_detalle';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id_empresa',
        'id_alerta',
        'cantidad',    
      ];
}
