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
        'id_giro_empresarial',
        'limite_inferior',
        'limite_superior',
        'fijo',
        'categoria',
        'millar',
        'excedente',
        
      ];
}