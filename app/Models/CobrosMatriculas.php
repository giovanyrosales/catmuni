<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CobrosMatriculas extends Model
{
    protected $table = 'cobros_matriculas';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_matriculas_detalle',
        'id_usuario',
        'monto_pagado',
        'monto_multaPE',
        'fecha_pago',
      ];
}
