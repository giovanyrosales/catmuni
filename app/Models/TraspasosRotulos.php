<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraspasosRotulos extends Model
{
    protected $table = 'traspaso_rotulo';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'id_rotulos',
        'contribuyente_anterior',
        'contribuyente_nuevo',
        'empresa_anterior',
        'empresa_nueva',
        'fecha_a_partir_de',
      ];
}
