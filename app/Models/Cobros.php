<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cobros extends Model
{
    protected $table = 'cobros';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_empresa',
        'id_usuario',
        'monto_pagado',
        'fecha_pago'
      ];
}
