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
        'contribuyente_anterior',
        'contribuyente_nuevo',
        'empresa_anterior',
        'empresa_nueva',
        'fecha_a_partir_de',
      ];
}
