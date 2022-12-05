<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraspasoBuses extends Model
{
    protected $table = 'traspasos_buses';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_buses_detalle',
        'nombre',
        'propietario_anterior',
        'propietario_nuevo',        
        'fecha_a_partir_de',
      ];
}
