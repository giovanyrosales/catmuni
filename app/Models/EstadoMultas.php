<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoMultas extends Model
{
    protected $table = 'estado_multa';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'estado'
      ];
}
