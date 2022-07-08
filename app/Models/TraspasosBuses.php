<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraspasosBuses extends Model
{
    protected $table = 'traspaso_buses';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_buses',
        'propietario_anterior',
        'propietario_nuevo',
        'fecha_a_partir_de',
      ];
}
