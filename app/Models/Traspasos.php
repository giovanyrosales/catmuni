<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traspasos extends Model
{
    protected $table = 'traspasos';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_empresa',
        'propietario_anterior',
        'propietario_nuevo',
        'fecha_a_partir_de',
      ];
}
