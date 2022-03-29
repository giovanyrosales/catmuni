<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatriculasDetalleEspecifico extends Model
{
    protected $table = 'matriculas_detalle_especifico';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'id_matriculas_detalle',
        'cod_municipal',
        'codigo',
        'num_serie',
        'direccion',

      ];
}
