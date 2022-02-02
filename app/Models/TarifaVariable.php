<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifaVariable extends Model
{
    protected $table = 'tarifa_variable';
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