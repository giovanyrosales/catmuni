<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActividadEconomica extends Model
{
    protected $table = 'actividad_economica';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'rubro',
        'codigo_atc_economica',
        'mora',
        'categoria' 
      ];
}
