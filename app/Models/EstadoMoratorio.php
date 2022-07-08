<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoMoratorio extends Model
{
    protected $table = 'estado_moratorio';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'estado'
      ];

}
